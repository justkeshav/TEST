<?php

/**
 * SlideVersion filter form base class.
 *
 * @package    LoveToKnow
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSlideVersionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'slideshow_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'rank'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'image_id'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'image_link'   => new sfWidgetFormFilterInput(),
      'heading'      => new sfWidgetFormFilterInput(),
      'text'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'created_by'   => new sfWidgetFormFilterInput(),
      'updated_by'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'slideshow_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'rank'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'image_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'image_link'   => new sfValidatorPass(array('required' => false)),
      'heading'      => new sfValidatorPass(array('required' => false)),
      'text'         => new sfValidatorPass(array('required' => false)),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_by'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'updated_by'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('slide_version_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SlideVersion';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'slideshow_id' => 'Number',
      'rank'         => 'Number',
      'image_id'     => 'Number',
      'image_link'   => 'Text',
      'heading'      => 'Text',
      'text'         => 'Text',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
      'created_by'   => 'Number',
      'updated_by'   => 'Number',
      'version'      => 'Number',
    );
  }
}
