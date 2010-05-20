<?php

/**
 * SlideVersion form base class.
 *
 * @method SlideVersion getObject() Returns the current form's model object
 *
 * @package    LoveToKnow
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSlideVersionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'slideshow_id' => new sfWidgetFormInputText(),
      'rank'         => new sfWidgetFormInputText(),
      'image_id'     => new sfWidgetFormInputText(),
      'image_link'   => new sfWidgetFormInputText(),
      'heading'      => new sfWidgetFormInputText(),
      'text'         => new sfWidgetFormTextarea(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
      'created_by'   => new sfWidgetFormInputText(),
      'updated_by'   => new sfWidgetFormInputText(),
      'version'      => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'slideshow_id' => new sfValidatorInteger(),
      'rank'         => new sfValidatorInteger(),
      'image_id'     => new sfValidatorInteger(),
      'image_link'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'heading'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'text'         => new sfValidatorString(),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
      'created_by'   => new sfValidatorInteger(array('required' => false)),
      'updated_by'   => new sfValidatorInteger(array('required' => false)),
      'version'      => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'version', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('slide_version[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SlideVersion';
  }

}
