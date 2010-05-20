<?php

/**
 * Channel filter form base class.
 *
 * @package    LoveToKnow
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseChannelFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'vertical_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Vertical'), 'add_empty' => true)),
      'title'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'short_title' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'slug'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'settings'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'content'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'vertical_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Vertical'), 'column' => 'id')),
      'title'       => new sfValidatorPass(array('required' => false)),
      'short_title' => new sfValidatorPass(array('required' => false)),
      'slug'        => new sfValidatorPass(array('required' => false)),
      'settings'    => new sfValidatorPass(array('required' => false)),
      'content'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('channel_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Channel';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'vertical_id' => 'ForeignKey',
      'title'       => 'Text',
      'short_title' => 'Text',
      'slug'        => 'Text',
      'settings'    => 'Text',
      'content'     => 'Text',
    );
  }
}
