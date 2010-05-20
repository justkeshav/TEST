<?php

/**
 * Vertical filter form base class.
 *
 * @package    LoveToKnow
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseVerticalFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'title' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'url'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'abbv'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'rank'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'title' => new sfValidatorPass(array('required' => false)),
      'url'   => new sfValidatorPass(array('required' => false)),
      'abbv'  => new sfValidatorPass(array('required' => false)),
      'rank'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('vertical_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Vertical';
  }

  public function getFields()
  {
    return array(
      'id'    => 'Number',
      'title' => 'Text',
      'url'   => 'Text',
      'abbv'  => 'Text',
      'rank'  => 'Number',
    );
  }
}
