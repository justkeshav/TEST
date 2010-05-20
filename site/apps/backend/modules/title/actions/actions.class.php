<?php

require_once dirname(__FILE__).'/../lib/titleGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/titleGeneratorHelper.class.php';

class titleActions extends autoTitleActions
{   
   public function executeListEditContent(sfWebRequest $request)
   {
      $this->title = $this->getRoute()->getObject();
      if($this->title->Content)
      {
         $this->redirect($this->generateUrl("{$this->title->type_table_name}_edit", array('id' => $this->title->Content->id)));
      }
      else
      {
         // must claim titles before they can edit them
         if ($this->title->status == Title::STATUS_AVAILABLE)
         {
            $this->getUser()->setFlash('error', 'You must claim the title first');  
            $this->redirect('@title');                
         }
         else
         {
            // mark as in progress when first starts to edit content
            $this->title->inprogress();

            $this->redirect($this->generateUrl("{$this->title->type_table_name}_new", array('title_id' => $this->title->id)));
         }
      }
   }

   protected function buildQuery()
   {
      $query = parent::buildQuery();

      $user = $this->getuser()->getGuardUser();
      if ($user->isAdmin())
      {
         return $query;
      }

      if($user->isWriter() || $user->isEditor())
      {
         // writers can see their own and any available to claim within the channels they can write on
         $writerChannels = implode(',', $user->getAssociatedChannels(sfGuardUser::ROLE_WRITER));
         $sql = "(author_user_id = {$user->id} OR ";
         $sql .= "(status = '" . Title::STATUS_AVAILABLE . "' AND available_on <= CURDATE()";
         if ($writerChannels != '')
         {
            $sql .= " AND channel_id IN ({$writerChannels})";
         }
         $sql .= ')';

         // editors can also see anything in the channels the can edit on (site editors can only see active)
         if($user->isEditor())
         {
            $editorChannels = implode(',', $user->getAssociatedChannels(sfGuardUser::ROLE_EDITOR));
            if ($editorChannels != '')
            {
               $sql .= " OR (channel_id IN ({$editorChannels})";
               if ($user->isSiteEditor())
               {
                  $sql .= " AND (status != '" . Title::STATUS_QUEUED . "' AND status != '" . Title::STATUS_AVAILABLE . "')";
               }
               $sql .= ')';
            } 
         }

         $sql .= ')';
  
         $query->addWhere($sql);
      }
      else if($this->getUser()->getGuardUser()->isTitler())
      {
         $query->andWhereIn('status', array(Title::STATUS_QUEUED, Title::STATUS_AVAILABLE));
      }

      return $query;
   }   

   public function executeNew(sfWebRequest $request)
   {
      $response = $this->getResponse();
      $response->addJavaScript('title');

      // when user clicks 'save and add' button,  a default channel is saved for bulk title input
      $defaultChannelId = $this->getUser()->getAttribute('default_channel_id');
      if ($defaultChannelId)
      {
         $options = array('default_channel_id' => $defaultChannelId);
         $this->getUser()->getAttributeHolder()->remove('default_channel_id');
      }
      else
      {
         $options = array('default_channel_id' => null);
      }
      
      $this->form = $this->configuration->getForm(null, $options);
      $this->title = $this->form->getObject();
      
   }   
   
   public function executeEdit(sfWebRequest $request)
   { 
      $this->title = $this->getRoute()->getObject();
      if ($this->getUser()->getGuardUser()->isWriter())
      { 
         // writers must claim titles before they can edit them
         if ($this->title->status == Title::STATUS_AVAILABLE)
         {
            $this->getUser()->setFlash('error', 'You must claim the title first');  
            $this->redirect('@title');                
         }   
         else if ($this->title->status == Title::STATUS_QUEUED)
         {
            $this->getUser()->setFlash('error', 'This title is not yet available');  
            $this->redirect('@title');          
         }
         else if ($this->title->author_user_id != $this->getUser()->getGuardUser()->id)
         {
            $this->getUser()->setFlash('error', 'You are not the author on this title');  
            $this->redirect('@title');          
         }
         // redirect writers directly to the content edit
         else
         {
            // mark as in progress when a writer starts to edit content
            if ($this->title->status == Title::STATUS_CLAIMED || $this->title->status == Title::STATUS_REJECTED)
            {
               $this->title->inprogress();
            }
            
            if($this->title->Content)
            {
               $this->redirect($this->generateUrl("{$this->title->type_table_name}_edit", array('id' => $this->title->Content->id)));
            }            
            else
            {
               $this->redirect($this->generateUrl("{$this->title->type_table_name}_new", array('title_id' => $this->title->id)));  
            }
         }
      }
      else if ($this->getUser()->getGuardUser()->isTitler())
      {    
         // titler can only access these status types
         if ($this->title->status != Title::STATUS_QUEUED && $this->title->status != Title::STATUS_AVAILABLE)
         {
            $this->getUser()->setFlash('error', 'You cannot edit this title');  
            $this->redirect('@title');                
         }           
      }
      
      $response = $this->getResponse();
      $response->addJavaScript('title');

      parent::executeEdit($request);
      
      // when user clicks 'save and add' button,  a default channel is saved for bulk title input
      $defaultChannelId = $this->getUser()->getAttribute('default_channel_id');
      if ($defaultChannelId)
      {
         $this->title->channel_id = $defaultChannelId;   
         $this->form = $this->configuration->getForm($this->title);         
         $this->getUser()->getAttributeHolder()->remove('default_channel_id');
      }      
   } 

   public function executeCreate(sfWebRequest $request)
   {
      $this->dispatcher->connect('admin.save_object', array($this, 'onObjectSaved'));
      parent::executeCreate($request);
      $this->dispatcher->disconnect('admin.save_object', array($this, 'onObjectSaved'));
   }

   public function onObjectSaved(sfEvent $event)
   {
      $params = $event->getParameters();

      // make sure the event applies to the instances we are working with
      if($event->getSubject() != $this || $params['object'] != $this->form->getObject())
      {
         return;
      }
      
      if ($this->getRequest()->hasParameter('_save_and_add'))
      {
         $this->getUser()->setAttribute('default_channel_id', $this->form->getObject()->channel_id);
      
         $this->getUser()->setFlash('notice', 'The Title was saved. You can add another one below.');

         $this->redirect('@title_new');
      }
      else
      {
        $this->getUser()->setFlash('notice', 'The Title was saved.');

        $this->redirect(array('sf_route' => 'title_edit', 'sf_subject' => $this->form->getObject()));
      }      
   }   
   
   public function executeCategoryList(sfWebRequest $request)
   {  
      // only execute if this is an ajax request
      if ($request->isXmlHttpRequest())
      {
         $this->categoryList = Doctrine::getTable('Category')->createQuery()
                                                         ->where('channel_id = ?', $request->getParameter('channel_id'))
                                                         ->andWhere('level != ?', 0)
                                                         ->execute();
      }
   }
   public function executeActivate(sfWebRequest $request)
   {
      $this->form = new TitleActivateForm();      
        
      if ($request->isMethod('post'))
      {
       $ids = $request->getParameter('title_ids');

       $titles = Doctrine_Query::create()
         ->from('Title')
         ->whereIn('id', $ids)
         ->execute();  

         $activated = 0;
         
         foreach ($titles as $title)
         {
            if ($title->status == 'Queued')
            {         
               $availableOn = $request->getParameter('available_on');
               $availableOn = 2010 + $availableOn['year'].'-'.$availableOn['month'].'-'.$availableOn['day'];
               $availableOn;
               $title->activate($availableOn);
               $activated += 1;
            }
         }
         if ($activated)
         {
            $this->getUser()->setFlash('notice', "The selected queued titles have been activated");            
         }
         else
         {
            $this->getUser()->setFlash('error', "No queued titles were selected");            
         }
         $this->redirect('@title');
      }
      else
      {
         $this->form->getWidget('title_ids')->setAttribute('value', $request->getParameter('id'));         
      }    
   }
   
   public function executeBatchActivate(sfWebRequest $request)
   {  
      $titleIds = $request->getParameter('ids');
      $this->redirect($this->generateUrl('title').'/activate?id='.implode(',', $titleIds)); 
   }
   
   public function executeClaim(sfWebRequest $request)
   {
      $title = Doctrine::getTable('Title')->find($request->getParameter('id'));
      
      $error = $title->claim($this->getUser()->getGuardUser()->id);
      if (!$error)
      {
         $this->getUser()->setFlash('notice', "The title has been claimed");             
      }
      else
      {
         $this->getUser()->setFlash('error', $error);             
      }     
      $this->redirect('@title');      
   }
   
   public function executeBatchClaim(sfWebRequest $request)
   {  
       $ids = $request->getParameter('ids');

       $titles = Doctrine_Query::create()
         ->from('Title')
         ->whereIn('id', $ids)
         ->execute();
      
      $claimed = 0;
      
      foreach($titles as $title)
      {   
         $error = $title->claim($this->getUser()->getGuardUser()->id);      
         if (!$error)
         {
            $claimed += 1;
         }
      }
      
      if ($claimed)
      {
         $this->getUser()->setFlash('notice', "The available titles have been claimed");            
      }
      else
      {
         $this->getUser()->setFlash('error', "No available titles were selected");
      }
      
      $this->redirect('@title');      
   }
   
   public function executeUnclaim(sfWebRequest $request)
   {  
      $this->title = $this->getRoute()->getObject();
         
      $error = $this->title->unclaim();
      if (!$error)
      {
         $this->getUser()->setFlash('notice', "The title has been unclaimed");             
      }
      else
      {
         $this->getUser()->setFlash('error', $error);             
      }
      $this->redirect('@title');     
   }
   
   public function executeBatchUnClaim(sfWebRequest $request)
   {  
       $ids = $request->getParameter('ids');

       $titles = Doctrine_Query::create()
         ->from('Title')
         ->whereIn('id', $ids)
         ->execute();
      
      $claimed = 0;
      
      foreach($titles as $title)
      {   
         $error = $title->unclaim();      
         if (!$error)
         {
            $unclaimed += 1;
         }
      }
      
      if ($unclaimed)
      {
         $this->getUser()->setFlash('notice', "Titles have been un-claimed");            
      }
      else
      {
         $this->getUser()->setFlash('error', "No claimed titles were selected");
      }
      
      $this->redirect('@title');      
   }
   
   public function executeSubmit(sfWebRequest $request)
   {  
      $this->title = $this->getRoute()->getObject();
         
      $error = $this->title->submit();
      if (!$error)
      {
         $this->getUser()->setFlash('notice', "The title has been submitted for approval");             
      }
      else
      {
         $this->getUser()->setFlash('error', $error);             
      }
      $this->redirect('@title');     
   }
   
   public function executeApprove(sfWebRequest $request)
   {  
      $this->title = $this->getRoute()->getObject();
         
      $error = $this->title->approve();
      if (!$error)
      {
         $this->getUser()->setFlash('notice', "The title has been approved");             
      }
      else
      {
         $this->getUser()->setFlash('error', $error);             
      }
      $this->redirect('@title');     
   }
   
   public function executeReject(sfWebRequest $request)
   {  
      $this->title = $this->getRoute()->getObject();
         
      $error = $this->title->reject();
      if (!$error)
      {
         $this->getUser()->setFlash('notice', "The title has been rejected");             
      }
      else
      {
         $this->getUser()->setFlash('error', $error);             
      }
      $this->redirect('@title');    
   }
   
   public function executePublish(sfWebRequest $request)
   {  
      $this->title = $this->getRoute()->getObject();
         
      $error = $this->title->publish();
      if (!$error)
      {
         $this->getUser()->setFlash('notice', "The title has been published");             
      }
      else
      {
         $this->getUser()->setFlash('error', $error);             
      }
      $this->redirect('@title');      
   }      
}
