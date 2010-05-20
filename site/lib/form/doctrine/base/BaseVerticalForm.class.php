<?php

/**
 * Vertical form base class.
 *
 * @method Vertical getObject() Returns the current form's model object
 *
 * @package    LoveToKnow
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseVerticalForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'    => new sfWidgetFormInputHidden(),
      'title' => new sfWidgetFormInputText(),
      'url'   => new sfWidgetFormInputText(),
      'abbv'  => new sfWidgetFormInputText(),
      'rank'  => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'    => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'title' => new sfValidatorString(array('max_length' => 255)),
      'url'   => new sfValidatorString(array('max_length' => 255)),
      'abbv'  => new sfValidatorString(array('max_length' => 5)),
      'rank'  => new sfValidatorInteger(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Vertical', 'column' => array('url')))
    );

    $this->widgetSchema->setNameFormat('vertical[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Vertical';
  }

}
