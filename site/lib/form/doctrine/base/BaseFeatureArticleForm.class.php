<?php

/**
 * FeatureArticle form base class.
 *
 * @method FeatureArticle getObject() Returns the current form's model object
 *
 * @package    LoveToKnow
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseFeatureArticleForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'channel_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Channel'), 'add_empty' => true)),
      'category_id' => new sfWidgetFormInputText(),
      'article_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Title'), 'add_empty' => false)),
      'position'    => new sfWidgetFormChoice(array('choices' => array('Above' => 'Above', 'Below' => 'Below', 'Right' => 'Right'))),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'channel_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Channel'), 'required' => false)),
      'category_id' => new sfValidatorInteger(),
      'article_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Title'))),
      'position'    => new sfValidatorChoice(array('choices' => array('Above' => 'Above', 'Below' => 'Below', 'Right' => 'Right'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('feature_article[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'FeatureArticle';
  }

}
