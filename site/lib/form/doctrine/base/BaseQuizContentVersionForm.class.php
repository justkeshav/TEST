<?php

/**
 * QuizContentVersion form base class.
 *
 * @method QuizContentVersion getObject() Returns the current form's model object
 *
 * @package    LoveToKnow
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseQuizContentVersionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'quiz_id'     => new sfWidgetFormInputText(),
      'message'     => new sfWidgetFormTextarea(),
      'format'      => new sfWidgetFormInputText(),
      'image_id'    => new sfWidgetFormInputText(),
      'image_link'  => new sfWidgetFormInputText(),
      'is_valid'    => new sfWidgetFormInputCheckbox(),
      'parent_id'   => new sfWidgetFormInputText(),
      'total_count' => new sfWidgetFormInputText(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
      'created_by'  => new sfWidgetFormInputText(),
      'updated_by'  => new sfWidgetFormInputText(),
      'version'     => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'quiz_id'     => new sfValidatorInteger(),
      'message'     => new sfValidatorString(),
      'format'      => new sfValidatorInteger(),
      'image_id'    => new sfValidatorInteger(array('required' => false)),
      'image_link'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_valid'    => new sfValidatorBoolean(),
      'parent_id'   => new sfValidatorInteger(array('required' => false)),
      'total_count' => new sfValidatorInteger(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
      'created_by'  => new sfValidatorInteger(array('required' => false)),
      'updated_by'  => new sfValidatorInteger(array('required' => false)),
      'version'     => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'version', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('quiz_content_version[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'QuizContentVersion';
  }

}
