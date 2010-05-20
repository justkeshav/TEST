<?php

/**
 * Article form.
 *
 * @package    LoveToKnow
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class BackendArticleForm extends autoArticleForm
{
  public function configure()
  {
      $this->widgetSchema['text'] = new ltksfWidgetFormTextareaCKEditor();         
  
      $this->widgetSchema['show_home_page'] = new sfWidgetFormInputCheckbox();
      $this->widgetSchema['show_home_page']->setOption('value_attribute_value', 1);
      $this->widgetSchema['show_home_page']->setLabel('Display on Homepage', 1);
   
      if(!$this->getObject()->isNew())
      {
            $this->widgetSchema['show_home_page']->setAttribute('checked',$this->getObject()->show_home_page);
      }

  }
}
