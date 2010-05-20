<?php

/**
 * TitleVersion form base class.
 *
 * @method TitleVersion getObject() Returns the current form's model object
 *
 * @package    LoveToKnow
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseTitleVersionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                        => new sfWidgetFormInputHidden(),
      'channel_id'                => new sfWidgetFormInputText(),
      'title'                     => new sfWidgetFormInputText(),
      'slug'                      => new sfWidgetFormInputText(),
      'url'                       => new sfWidgetFormInputText(),
      'type'                      => new sfWidgetFormChoice(array('choices' => array('Category' => 'Category', 'Article' => 'Article', 'Slideshow' => 'Slideshow', 'Quiz' => 'Quiz'))),
      'status'                    => new sfWidgetFormChoice(array('choices' => array('Queued' => 'Queued', 'Available' => 'Available', 'Claimed' => 'Claimed', 'InProgress' => 'InProgress', 'Submitted' => 'Submitted', 'Rejected' => 'Rejected', 'Approved' => 'Approved', 'Published' => 'Published', 'Deleted' => 'Deleted'))),
      'published_content_version' => new sfWidgetFormInputText(),
      'notes'                     => new sfWidgetFormInputText(),
      'available_on'              => new sfWidgetFormDate(),
      'author_user_id'            => new sfWidgetFormInputText(),
      'image_id'                  => new sfWidgetFormInputText(),
      'image_link'                => new sfWidgetFormInputText(),
      'image_text'                => new sfWidgetFormInputText(),
      'image_caption'             => new sfWidgetFormInputText(),
      'image_width'               => new sfWidgetFormInputText(),
      'image_thumbnail'           => new sfWidgetFormInputCheckbox(),
      'created_at'                => new sfWidgetFormDateTime(),
      'updated_at'                => new sfWidgetFormDateTime(),
      'created_by'                => new sfWidgetFormInputText(),
      'updated_by'                => new sfWidgetFormInputText(),
      'version'                   => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'                        => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'channel_id'                => new sfValidatorInteger(),
      'title'                     => new sfValidatorString(array('max_length' => 255)),
      'slug'                      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'url'                       => new sfValidatorString(array('max_length' => 255)),
      'type'                      => new sfValidatorChoice(array('choices' => array('Category' => 'Category', 'Article' => 'Article', 'Slideshow' => 'Slideshow', 'Quiz' => 'Quiz'), 'required' => false)),
      'status'                    => new sfValidatorChoice(array('choices' => array('Queued' => 'Queued', 'Available' => 'Available', 'Claimed' => 'Claimed', 'InProgress' => 'InProgress', 'Submitted' => 'Submitted', 'Rejected' => 'Rejected', 'Approved' => 'Approved', 'Published' => 'Published', 'Deleted' => 'Deleted'), 'required' => false)),
      'published_content_version' => new sfValidatorInteger(array('required' => false)),
      'notes'                     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'available_on'              => new sfValidatorDate(array('required' => false)),
      'author_user_id'            => new sfValidatorInteger(array('required' => false)),
      'image_id'                  => new sfValidatorInteger(array('required' => false)),
      'image_link'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'image_text'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'image_caption'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'image_width'               => new sfValidatorInteger(array('required' => false)),
      'image_thumbnail'           => new sfValidatorBoolean(array('required' => false)),
      'created_at'                => new sfValidatorDateTime(),
      'updated_at'                => new sfValidatorDateTime(),
      'created_by'                => new sfValidatorInteger(array('required' => false)),
      'updated_by'                => new sfValidatorInteger(array('required' => false)),
      'version'                   => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'version', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('title_version[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TitleVersion';
  }

}
