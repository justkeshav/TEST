<?php

/**
 * ArticleVersion form base class.
 *
 * @method ArticleVersion getObject() Returns the current form's model object
 *
 * @package    LoveToKnow
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseArticleVersionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'text'                 => new sfWidgetFormTextarea(),
      'show_home_page'       => new sfWidgetFormInputText(),
      'related_article_urls' => new sfWidgetFormTextarea(),
      'title_id'             => new sfWidgetFormInputText(),
      'created_at'           => new sfWidgetFormDateTime(),
      'updated_at'           => new sfWidgetFormDateTime(),
      'created_by'           => new sfWidgetFormInputText(),
      'updated_by'           => new sfWidgetFormInputText(),
      'version'              => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'text'                 => new sfValidatorString(),
      'show_home_page'       => new sfValidatorInteger(array('required' => false)),
      'related_article_urls' => new sfValidatorString(array('required' => false)),
      'title_id'             => new sfValidatorInteger(array('required' => false)),
      'created_at'           => new sfValidatorDateTime(),
      'updated_at'           => new sfValidatorDateTime(),
      'created_by'           => new sfValidatorInteger(array('required' => false)),
      'updated_by'           => new sfValidatorInteger(array('required' => false)),
      'version'              => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'version', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('article_version[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ArticleVersion';
  }

}
