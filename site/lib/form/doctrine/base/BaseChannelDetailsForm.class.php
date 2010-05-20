<?php

/**
 * ChannelDetails form base class.
 *
 * @method ChannelDetails getObject() Returns the current form's model object
 *
 * @package    LoveToKnow
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseChannelDetailsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'channel_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Channel'), 'add_empty' => false)),
      'highlight_title_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Highlight'), 'add_empty' => false)),
      'highlight_content'  => new sfWidgetFormTextarea(),
      'popular1_title_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Popular1'), 'add_empty' => false)),
      'popular1_content'   => new sfWidgetFormTextarea(),
      'popular2_title_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Popular2'), 'add_empty' => false)),
      'popular2_content'   => new sfWidgetFormTextarea(),
      'about_title_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('AboutChannel'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'channel_id'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Channel'))),
      'highlight_title_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Highlight'))),
      'highlight_content'  => new sfValidatorString(),
      'popular1_title_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Popular1'))),
      'popular1_content'   => new sfValidatorString(),
      'popular2_title_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Popular2'))),
      'popular2_content'   => new sfValidatorString(),
      'about_title_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('AboutChannel'))),
    ));

    $this->widgetSchema->setNameFormat('channel_details[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ChannelDetails';
  }

}
