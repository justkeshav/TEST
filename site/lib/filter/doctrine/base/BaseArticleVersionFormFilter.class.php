<?php

/**
 * ArticleVersion filter form base class.
 *
 * @package    LoveToKnow
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseArticleVersionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'text'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'show_home_page'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'related_article_urls' => new sfWidgetFormFilterInput(),
      'title_id'             => new sfWidgetFormFilterInput(),
      'created_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'created_by'           => new sfWidgetFormFilterInput(),
      'updated_by'           => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'text'                 => new sfValidatorPass(array('required' => false)),
      'show_home_page'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'related_article_urls' => new sfValidatorPass(array('required' => false)),
      'title_id'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_by'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'updated_by'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('article_version_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ArticleVersion';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'text'                 => 'Text',
      'show_home_page'       => 'Number',
      'related_article_urls' => 'Text',
      'title_id'             => 'Number',
      'created_at'           => 'Date',
      'updated_at'           => 'Date',
      'created_by'           => 'Number',
      'updated_by'           => 'Number',
      'version'              => 'Number',
    );
  }
}
