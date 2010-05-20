<?php

/**
 * QuizContentVersion filter form base class.
 *
 * @package    LoveToKnow
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseQuizContentVersionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'quiz_id'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'message'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'format'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'image_id'    => new sfWidgetFormFilterInput(),
      'image_link'  => new sfWidgetFormFilterInput(),
      'is_valid'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'parent_id'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'total_count' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'created_by'  => new sfWidgetFormFilterInput(),
      'updated_by'  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'quiz_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'message'     => new sfValidatorPass(array('required' => false)),
      'format'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'image_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'image_link'  => new sfValidatorPass(array('required' => false)),
      'is_valid'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'parent_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'total_count' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_by'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'updated_by'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('quiz_content_version_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'QuizContentVersion';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'quiz_id'     => 'Number',
      'message'     => 'Text',
      'format'      => 'Number',
      'image_id'    => 'Number',
      'image_link'  => 'Text',
      'is_valid'    => 'Boolean',
      'parent_id'   => 'Number',
      'total_count' => 'Number',
      'created_at'  => 'Date',
      'updated_at'  => 'Date',
      'created_by'  => 'Number',
      'updated_by'  => 'Number',
      'version'     => 'Number',
    );
  }
}
