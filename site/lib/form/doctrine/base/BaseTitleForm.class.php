<?php

/**
 * Title form base class.
 *
 * @method Title getObject() Returns the current form's model object
 *
 * @package    LoveToKnow
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseTitleForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                        => new sfWidgetFormInputHidden(),
      'channel_id'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Channel'), 'add_empty' => false)),
      'title'                     => new sfWidgetFormInputText(),
      'slug'                      => new sfWidgetFormInputText(),
      'url'                       => new sfWidgetFormInputText(),
      'type'                      => new sfWidgetFormChoice(array('choices' => array('Category' => 'Category', 'Article' => 'Article', 'Slideshow' => 'Slideshow', 'Quiz' => 'Quiz'))),
      'status'                    => new sfWidgetFormChoice(array('choices' => array('Queued' => 'Queued', 'Available' => 'Available', 'Claimed' => 'Claimed', 'InProgress' => 'InProgress', 'Submitted' => 'Submitted', 'Rejected' => 'Rejected', 'Approved' => 'Approved', 'Published' => 'Published', 'Deleted' => 'Deleted'))),
      'published_content_version' => new sfWidgetFormInputText(),
      'notes'                     => new sfWidgetFormInputText(),
      'available_on'              => new sfWidgetFormDate(),
      'author_user_id'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'image_id'                  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Image'), 'add_empty' => true)),
      'image_link'                => new sfWidgetFormInputText(),
      'image_text'                => new sfWidgetFormInputText(),
      'image_caption'             => new sfWidgetFormInputText(),
      'image_width'               => new sfWidgetFormInputText(),
      'image_thumbnail'           => new sfWidgetFormInputCheckbox(),
      'created_at'                => new sfWidgetFormDateTime(),
      'updated_at'                => new sfWidgetFormDateTime(),
      'created_by'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Created'), 'add_empty' => true)),
      'updated_by'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Updated'), 'add_empty' => true)),
      'version'                   => new sfWidgetFormInputText(),
      'categories_list'           => new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'Category')),
    ));

    $this->setValidators(array(
      'id'                        => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'channel_id'                => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Channel'))),
      'title'                     => new sfValidatorString(array('max_length' => 255)),
      'slug'                      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'url'                       => new sfValidatorString(array('max_length' => 255)),
      'type'                      => new sfValidatorChoice(array('choices' => array('Category' => 'Category', 'Article' => 'Article', 'Slideshow' => 'Slideshow', 'Quiz' => 'Quiz'), 'required' => false)),
      'status'                    => new sfValidatorChoice(array('choices' => array('Queued' => 'Queued', 'Available' => 'Available', 'Claimed' => 'Claimed', 'InProgress' => 'InProgress', 'Submitted' => 'Submitted', 'Rejected' => 'Rejected', 'Approved' => 'Approved', 'Published' => 'Published', 'Deleted' => 'Deleted'), 'required' => false)),
      'published_content_version' => new sfValidatorInteger(array('required' => false)),
      'notes'                     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'available_on'              => new sfValidatorDate(array('required' => false)),
      'author_user_id'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'required' => false)),
      'image_id'                  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Image'), 'required' => false)),
      'image_link'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'image_text'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'image_caption'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'image_width'               => new sfValidatorInteger(array('required' => false)),
      'image_thumbnail'           => new sfValidatorBoolean(array('required' => false)),
      'created_at'                => new sfValidatorDateTime(),
      'updated_at'                => new sfValidatorDateTime(),
      'created_by'                => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Created'), 'required' => false)),
      'updated_by'                => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Updated'), 'required' => false)),
      'version'                   => new sfValidatorInteger(array('required' => false)),
      'categories_list'           => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Category', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'Title', 'column' => array('id', 'channel_id'))),
        new sfValidatorDoctrineUnique(array('model' => 'Title', 'column' => array('channel_id', 'url'))),
      ))
    );

    $this->widgetSchema->setNameFormat('title[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Title';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['categories_list']))
    {
      $this->setDefault('categories_list', $this->object->Categories->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    $this->saveCategoriesList($con);

    parent::doSave($con);
  }

  public function saveCategoriesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['categories_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->Categories->getPrimaryKeys();
    $values = $this->getValue('categories_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('Categories', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('Categories', array_values($link));
    }
  }

}
