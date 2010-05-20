<?php

/**
 * FeatureArticle filter form base class.
 *
 * @package    LoveToKnow
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseFeatureArticleFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'channel_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Channel'), 'add_empty' => true)),
      'category_id' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'article_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Title'), 'add_empty' => true)),
      'position'    => new sfWidgetFormChoice(array('choices' => array('' => '', 'Above' => 'Above', 'Below' => 'Below', 'Right' => 'Right'))),
    ));

    $this->setValidators(array(
      'channel_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Channel'), 'column' => 'id')),
      'category_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'article_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Title'), 'column' => 'id')),
      'position'    => new sfValidatorChoice(array('required' => false, 'choices' => array('Above' => 'Above', 'Below' => 'Below', 'Right' => 'Right'))),
    ));

    $this->widgetSchema->setNameFormat('feature_article_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'FeatureArticle';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'channel_id'  => 'ForeignKey',
      'category_id' => 'Number',
      'article_id'  => 'ForeignKey',
      'position'    => 'Enum',
    );
  }
}
