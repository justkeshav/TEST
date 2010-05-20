<?php

/**
 * Title filter form base class.
 *
 * @package    LoveToKnow
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseTitleFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'channel_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Channel'), 'add_empty' => true)),
      'title'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'slug'            => new sfWidgetFormFilterInput(),
      'url'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'            => new sfWidgetFormChoice(array('choices' => array('' => '', 'Category' => 'Category', 'Article' => 'Article', 'Slideshow' => 'Slideshow', 'Quiz' => 'Quiz'))),
      'status'          => new sfWidgetFormChoice(array('choices' => array('' => '', 'Queued' => 'Queued', 'Available' => 'Available', 'Claimed' => 'Claimed', 'InProgress' => 'InProgress', 'Submitted' => 'Submitted', 'Rejected' => 'Rejected', 'Approved' => 'Approved', 'Published' => 'Published', 'Deleted' => 'Deleted'))),
      'notes'           => new sfWidgetFormFilterInput(),
      'available_on'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'author_user_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'image_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Image'), 'add_empty' => true)),
      'image_link'      => new sfWidgetFormFilterInput(),
      'image_text'      => new sfWidgetFormFilterInput(),
      'image_caption'   => new sfWidgetFormFilterInput(),
      'image_width'     => new sfWidgetFormFilterInput(),
      'image_thumbnail' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'created_by'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Created'), 'add_empty' => true)),
      'updated_by'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Updated'), 'add_empty' => true)),
      'version'         => new sfWidgetFormFilterInput(),
      'categories_list' => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Category')),
    ));

    $this->setValidators(array(
      'channel_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Channel'), 'column' => 'id')),
      'title'           => new sfValidatorPass(array('required' => false)),
      'slug'            => new sfValidatorPass(array('required' => false)),
      'url'             => new sfValidatorPass(array('required' => false)),
      'type'            => new sfValidatorChoice(array('required' => false, 'choices' => array('Category' => 'Category', 'Article' => 'Article', 'Slideshow' => 'Slideshow', 'Quiz' => 'Quiz'))),
      'status'          => new sfValidatorChoice(array('required' => false, 'choices' => array('Queued' => 'Queued', 'Available' => 'Available', 'Claimed' => 'Claimed', 'InProgress' => 'InProgress', 'Submitted' => 'Submitted', 'Rejected' => 'Rejected', 'Approved' => 'Approved', 'Published' => 'Published', 'Deleted' => 'Deleted'))),
      'notes'           => new sfValidatorPass(array('required' => false)),
      'available_on'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'author_user_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('sfGuardUser'), 'column' => 'id')),
      'image_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Image'), 'column' => 'id')),
      'image_link'      => new sfValidatorPass(array('required' => false)),
      'image_text'      => new sfValidatorPass(array('required' => false)),
      'image_caption'   => new sfValidatorPass(array('required' => false)),
      'image_width'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'image_thumbnail' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_by'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Created'), 'column' => 'id')),
      'updated_by'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Updated'), 'column' => 'id')),
      'version'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'categories_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Category', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('title_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function addCategoriesListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query->leftJoin('r.TitleCategory TitleCategory')
          ->andWhereIn('TitleCategory.category_id', $values);
  }

  public function getModelName()
  {
    return 'Title';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'channel_id'      => 'ForeignKey',
      'title'           => 'Text',
      'slug'            => 'Text',
      'url'             => 'Text',
      'type'            => 'Enum',
      'status'          => 'Enum',
      'notes'           => 'Text',
      'available_on'    => 'Date',
      'author_user_id'  => 'ForeignKey',
      'image_id'        => 'ForeignKey',
      'image_link'      => 'Text',
      'image_text'      => 'Text',
      'image_caption'   => 'Text',
      'image_width'     => 'Number',
      'image_thumbnail' => 'Boolean',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
      'created_by'      => 'ForeignKey',
      'updated_by'      => 'ForeignKey',
      'version'         => 'Number',
      'categories_list' => 'ManyKey',
    );
  }
}
