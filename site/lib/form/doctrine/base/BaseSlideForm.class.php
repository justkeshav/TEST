<?php

/**
 * Slide form base class.
 *
 * @method Slide getObject() Returns the current form's model object
 *
 * @package    LoveToKnow
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSlideForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'slideshow_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Slideshow'), 'add_empty' => false)),
      'rank'         => new sfWidgetFormInputText(),
      'image_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Image'), 'add_empty' => false)),
      'image_link'   => new sfWidgetFormInputText(),
      'heading'      => new sfWidgetFormInputText(),
      'text'         => new sfWidgetFormTextarea(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
      'created_by'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Created'), 'add_empty' => true)),
      'updated_by'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Updated'), 'add_empty' => true)),
      'version'      => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'slideshow_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Slideshow'))),
      'rank'         => new sfValidatorInteger(),
      'image_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Image'))),
      'image_link'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'heading'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'text'         => new sfValidatorString(),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
      'created_by'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Created'), 'required' => false)),
      'updated_by'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Updated'), 'required' => false)),
      'version'      => new sfValidatorInteger(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Slide', 'column' => array('slideshow_id', 'rank')))
    );

    $this->widgetSchema->setNameFormat('slide[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Slide';
  }

}
