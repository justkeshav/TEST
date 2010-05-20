<?php

class BackendSlideshowForm extends autoSlideshowForm
{
   public function configure()
   {     
      $this->widgetSchema['show_home_page'] = new sfWidgetFormInputCheckbox();
      $this->widgetSchema['show_home_page']->setOption('value_attribute_value', 1);
      $this->widgetSchema['show_home_page']->setLabel('Display on Homepage', 1);
      if(!$this->getObject()->isNew())
      {
            $this->widgetSchema['show_home_page']->setAttribute('checked',$this->getObject()->show_home_page);
      }
      parent::configure();
   }
}
