<?php

/**
 * Channel form base class.
 *
 * @method Channel getObject() Returns the current form's model object
 *
 * @package    LoveToKnow
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseChannelForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'vertical_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Vertical'), 'add_empty' => false)),
      'title'       => new sfWidgetFormInputText(),
      'short_title' => new sfWidgetFormInputText(),
      'slug'        => new sfWidgetFormInputText(),
      'settings'    => new sfWidgetFormInputText(),
      'content'     => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'vertical_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Vertical'))),
      'title'       => new sfValidatorString(array('max_length' => 255)),
      'short_title' => new sfValidatorString(array('max_length' => 255)),
      'slug'        => new sfValidatorString(array('max_length' => 255)),
      'settings'    => new sfValidatorPass(),
      'content'     => new sfValidatorString(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Channel', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('channel[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Channel';
  }

}
