<?php

/**
 * QuizVersion form base class.
 *
 * @method QuizVersion getObject() Returns the current form's model object
 *
 * @package    LoveToKnow
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseQuizVersionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'format'             => new sfWidgetFormInputText(),
      'message'            => new sfWidgetFormTextarea(),
      'description1'       => new sfWidgetFormTextarea(),
      'description2'       => new sfWidgetFormTextarea(),
      'questions_per_page' => new sfWidgetFormInputText(),
      'result_format'      => new sfWidgetFormInputText(),
      'result_type'        => new sfWidgetFormInputText(),
      'show_accuracy'      => new sfWidgetFormInputCheckbox(),
      'login_position'     => new sfWidgetFormInputText(),
      'login_text'         => new sfWidgetFormTextarea(),
      'style'              => new sfWidgetFormInputText(),
      'result20'           => new sfWidgetFormTextarea(),
      'result40'           => new sfWidgetFormTextarea(),
      'result60'           => new sfWidgetFormTextarea(),
      'result80'           => new sfWidgetFormTextarea(),
      'result100'          => new sfWidgetFormTextarea(),
      'title_id'           => new sfWidgetFormInputText(),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
      'created_by'         => new sfWidgetFormInputText(),
      'updated_by'         => new sfWidgetFormInputText(),
      'version'            => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'format'             => new sfValidatorInteger(),
      'message'            => new sfValidatorString(array('required' => false)),
      'description1'       => new sfValidatorString(array('required' => false)),
      'description2'       => new sfValidatorString(array('required' => false)),
      'questions_per_page' => new sfValidatorInteger(array('required' => false)),
      'result_format'      => new sfValidatorInteger(),
      'result_type'        => new sfValidatorInteger(),
      'show_accuracy'      => new sfValidatorBoolean(array('required' => false)),
      'login_position'     => new sfValidatorInteger(),
      'login_text'         => new sfValidatorString(array('required' => false)),
      'style'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'result20'           => new sfValidatorString(array('required' => false)),
      'result40'           => new sfValidatorString(array('required' => false)),
      'result60'           => new sfValidatorString(array('required' => false)),
      'result80'           => new sfValidatorString(array('required' => false)),
      'result100'          => new sfValidatorString(array('required' => false)),
      'title_id'           => new sfValidatorInteger(array('required' => false)),
      'created_at'         => new sfValidatorDateTime(),
      'updated_at'         => new sfValidatorDateTime(),
      'created_by'         => new sfValidatorInteger(array('required' => false)),
      'updated_by'         => new sfValidatorInteger(array('required' => false)),
      'version'            => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'version', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('quiz_version[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'QuizVersion';
  }

}
