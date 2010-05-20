<?php

/**
 * Title filter form.
 *
 * @package    LoveToKnow
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TitleFormFilter extends BaseTitleFormFilter
{
   public function configure()
   {
      // alter filter options based on user role & permissions
      
      $this->user = sfContext::getInstance()->getUser()->getGuardUser();
      
      if ($this->user->isWriter())
      {
         unset($this['created_by']);
         unset($this['updated_by']);
         unset($this['author_user_id']);
         
         $this->widgetSchema['channel_id']->setOption('query',
               Doctrine::getTable("Channel")
                  ->createQuery()
                  ->whereIn('id', $this->user->getAssociatedChannels(sfGuardUser::ROLE_WRITER)));
      }
      else if ($this->user->isTitler())
      {
         $this->widgetSchema['status'] = new sfWidgetFormChoice(array('choices' => array('Queued' => 'Queued', 'Available' => 'Available')));   
      }
   }
   
   public function buildQuery(array $values)
   {
      if ($this->user->isTitler())
      {
         if (!isset($values['status']))
         {
            $values['status'] = 'Queued';
         }
      }

      return parent::buildQuery($values);

   }
}
