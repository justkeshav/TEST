<?php

/**
 * ChannelDetails filter form base class.
 *
 * @package    LoveToKnow
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseChannelDetailsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'channel_id'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Channel'), 'add_empty' => true)),
      'highlight_title_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Highlight'), 'add_empty' => true)),
      'highlight_content'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'popular1_title_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Popular1'), 'add_empty' => true)),
      'popular1_content'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'popular2_title_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Popular2'), 'add_empty' => true)),
      'popular2_content'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'about_title_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('AboutChannel'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'channel_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Channel'), 'column' => 'id')),
      'highlight_title_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Highlight'), 'column' => 'id')),
      'highlight_content'  => new sfValidatorPass(array('required' => false)),
      'popular1_title_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Popular1'), 'column' => 'id')),
      'popular1_content'   => new sfValidatorPass(array('required' => false)),
      'popular2_title_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Popular2'), 'column' => 'id')),
      'popular2_content'   => new sfValidatorPass(array('required' => false)),
      'about_title_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('AboutChannel'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('channel_details_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ChannelDetails';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'channel_id'         => 'ForeignKey',
      'highlight_title_id' => 'ForeignKey',
      'highlight_content'  => 'Text',
      'popular1_title_id'  => 'ForeignKey',
      'popular1_content'   => 'Text',
      'popular2_title_id'  => 'ForeignKey',
      'popular2_content'   => 'Text',
      'about_title_id'     => 'ForeignKey',
    );
  }
}
