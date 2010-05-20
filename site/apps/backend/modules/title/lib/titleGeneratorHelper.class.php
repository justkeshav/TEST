<?php

class titleGeneratorHelper extends BaseTitleGeneratorHelper
{
   // display form actions based on user credentials

   public function linkToDelete($title, $params)
   {
	  $user = sfContext::getInstance()->getUser()->getGuardUser();
	
      if ($user->isTitler() && $title->status != 'Queued')
      {
         return '';
      }
      
      return parent::linkToDelete($title, $params);
   }

   public function linkToActivate($title, $params)
   {
      if ($title->status != 'Queued')
      {
         return '';
      }
            
      $user = sfContext::getInstance()->getUser()->getGuardUser();      
      
      if (!$user->isGroupEditor())
      {
         return '';
      }
      
      return link_to(__('Activate', array(), 'messages'), 'title/activate?id='.$title->getId(), array());
   }
   
   public function linkToClaim($title, $params)
   {
      if ($title->status != 'Available')
      {
         return '';
      }
      
      $user = sfContext::getInstance()->getUser()->getGuardUser();
      
      if (!$user->isEditor() && !$user->isWriter())
      {
         return '';
      }
      return link_to(__('Claim', array(), 'messages'), 'title/claim?id='.$title->getId(), array());
   }
}
