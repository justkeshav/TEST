<?php

/**
 * QuizVersion filter form base class.
 *
 * @package    LoveToKnow
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseQuizVersionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'format'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'message'            => new sfWidgetFormFilterInput(),
      'description1'       => new sfWidgetFormFilterInput(),
      'description2'       => new sfWidgetFormFilterInput(),
      'questions_per_page' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'result_format'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'result_type'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'show_accuracy'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'login_position'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'login_text'         => new sfWidgetFormFilterInput(),
      'style'              => new sfWidgetFormFilterInput(),
      'result20'           => new sfWidgetFormFilterInput(),
      'result40'           => new sfWidgetFormFilterInput(),
      'result60'           => new sfWidgetFormFilterInput(),
      'result80'           => new sfWidgetFormFilterInput(),
      'result100'          => new sfWidgetFormFilterInput(),
      'title_id'           => new sfWidgetFormFilterInput(),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'created_by'         => new sfWidgetFormFilterInput(),
      'updated_by'         => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'format'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'message'            => new sfValidatorPass(array('required' => false)),
      'description1'       => new sfValidatorPass(array('required' => false)),
      'description2'       => new sfValidatorPass(array('required' => false)),
      'questions_per_page' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'result_format'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'result_type'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'show_accuracy'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'login_position'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'login_text'         => new sfValidatorPass(array('required' => false)),
      'style'              => new sfValidatorPass(array('required' => false)),
      'result20'           => new sfValidatorPass(array('required' => false)),
      'result40'           => new sfValidatorPass(array('required' => false)),
      'result60'           => new sfValidatorPass(array('required' => false)),
      'result80'           => new sfValidatorPass(array('required' => false)),
      'result100'          => new sfValidatorPass(array('required' => false)),
      'title_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_by'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'updated_by'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('quiz_version_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'QuizVersion';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'format'             => 'Number',
      'message'            => 'Text',
      'description1'       => 'Text',
      'description2'       => 'Text',
      'questions_per_page' => 'Number',
      'result_format'      => 'Number',
      'result_type'        => 'Number',
      'show_accuracy'      => 'Boolean',
      'login_position'     => 'Number',
      'login_text'         => 'Text',
      'style'              => 'Text',
      'result20'           => 'Text',
      'result40'           => 'Text',
      'result60'           => 'Text',
      'result80'           => 'Text',
      'result100'          => 'Text',
      'title_id'           => 'Number',
      'created_at'         => 'Date',
      'updated_at'         => 'Date',
      'created_by'         => 'Number',
      'updated_by'         => 'Number',
      'version'            => 'Number',
    );
  }
}
