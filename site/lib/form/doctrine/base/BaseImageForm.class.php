<?php

/**
 * Image form base class.
 *
 * @method Image getObject() Returns the current form's model object
 *
 * @package    LoveToKnow
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseImageForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'channel_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Channel'), 'add_empty' => false)),
      'host'        => new sfWidgetFormInputText(),
      'path'        => new sfWidgetFormInputText(),
      'filename'    => new sfWidgetFormInputText(),
      'sizes'       => new sfWidgetFormInputText(),
      'source'      => new sfWidgetFormInputText(),
      'source_url'  => new sfWidgetFormInputText(),
      'permission'  => new sfWidgetFormInputText(),
      'description' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'channel_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Channel'))),
      'host'        => new sfValidatorString(array('max_length' => 255)),
      'path'        => new sfValidatorString(array('max_length' => 255)),
      'filename'    => new sfValidatorString(array('max_length' => 255)),
      'sizes'       => new sfValidatorPass(),
      'source'      => new sfValidatorString(array('max_length' => 255)),
      'source_url'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'permission'  => new sfValidatorString(array('max_length' => 255)),
      'description' => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('image[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Image';
  }

}
