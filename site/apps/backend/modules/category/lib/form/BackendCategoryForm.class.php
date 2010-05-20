<?php

/**
 * Category form.
 *
 * @package    LoveToKnow
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class BackendCategoryForm extends AutoCategoryForm
{
   public function configure()
   {
      // define query for parent category choice select
      $parentCategoryQuery = Doctrine::GetTable('Category')
                              ->createQuery('c')
                              ->where('c.channel_id = ?', $this->getObject()->Title->channel_id);
                              
      if (!$this->getObject()->isNew())
      {      
         // change query exclude this category if not a new one
         $parentCategoryQuery = $parentCategoryQuery->andWhere('id != ?', $this->getObject()->id);
         
         if ($this->getObject()->getNode()->getParent())
         {
            $this->setDefault('parent_category', $this->getObject()->getNode()->getParent()->id);
         }
      }
      else
      {
         // set default parent = to the root for the channel
         $this->setDefault('parent_category', Doctrine::getTable('Category')->findRootByChannelId($this->getObject()->Title->channel_id)->id);
      }
      
      $this->widgetSchema['parent_category'] = new sfWidgetFormDoctrineChoice(array(
               'model' => 'Category',
               'add_empty' => false,
               'query' => $parentCategoryQuery));
                                        
      $this->validatorSchema['parent_category'] = new sfValidatorString();
            
   }
   
   protected function doSave($con = null)
   {

      parent::doSave($con);

      $parentID = $this->getValue('parent_category');
      
      $parent = Doctrine::getTable('Category')->find($parentID);
      
      if ($this->getObject()->getNode()->getParent())
      {
         $this->getObject()->getNode()->moveAsLastChildOf($parent);
      }
      else
      {
         $this->getObject()->getNode()->insertAsLastChildOf($parent);
      }
      
   }  
   protected function doUpdateObject($values)
   {
      parent::doUpdateObject($values);

      $this->getObject()->channel_id = "{$this->getObject()->Title->channel_id}";
   }
}
