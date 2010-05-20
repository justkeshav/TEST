[?php

require_once(dirname(__FILE__).'/../lib/Base<?php echo ucfirst($this->moduleName) ?>GeneratorConfiguration.class.php');
require_once(dirname(__FILE__).'/../lib/Base<?php echo ucfirst($this->moduleName) ?>GeneratorHelper.class.php');

/**
 * <?php echo $this->getModuleName() ?> actions.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage <?php echo $this->getModuleName()."\n" ?>
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: actions.class.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class <?php echo $this->getGeneratedModuleName() ?>Actions extends <?php echo $this->getActionsBaseClass()."\n" ?>
{
  public function preExecute()
  {
    $this->configuration = new <?php echo $this->getModuleName() ?>GeneratorConfiguration();

    if (!$this->getUser()->hasCredential($this->configuration->getCredentials($this->getActionName())))
    {
      $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
    }

    $this->dispatcher->notify(new sfEvent($this, 'admin.pre_execute', array('configuration' => $this->configuration)));

    $this->helper = new <?php echo $this->getModuleName() ?>GeneratorHelper();
  }

<?php if($this->isDerivative() || $this->hasParentModel()): ?>
   public function execute($request)
   {
      $routeOptions = $this->getRoute()->getOptions();
      $action = $request->getUrlParameter('action');
<?php if($this->isDerivative()): ?>

      if($action == 'new' || $action == 'create')
      {
         $<?php echo sfInflector::underscore($this->getDerivativeBase()) ?>_id = $request->getParameter('<?php echo sfInflector::underscore($this->getDerivativeBase()) ?>_id', 0);

         // check if <?php echo $this->getDerivativeBase() ?> is coming in from a form submission and override if available
         if($request->hasParameter('<?php echo $this->getSingularName() ?>'))
         {
            $<?php echo $this->getSingularName() ?> = $request->getParameter('<?php echo $this->getSingularName() ?>');
            if(array_key_exists('<?php echo sfInflector::underscore($this->getDerivativeBase()) ?>_id', $<?php echo $this->getSingularName() ?>))
            {
               $<?php echo sfInflector::underscore($this->getDerivativeBase()) ?>_id = $<?php echo $this->getSingularName() ?>['<?php echo sfInflector::underscore($this->getDerivativeBase()) ?>_id'];
            }
         }

         $this->forward404Unless($<?php echo sfInflector::underscore($this->getDerivativeBase()) ?>_id > 0);

         $this-><?php echo $this->getDerivativeBase() ?> = Doctrine::getTable('<?php echo $this->getDerivativeBase() ?>')->find($<?php echo sfInflector::underscore($this->getDerivativeBase()) ?>_id);
         $this->forward404Unless($this-><?php echo $this->getDerivativeBase() ?>);
      }
<?php endif; ?>
<?php if($this->hasParentModel()): ?>

      if(array_key_exists('type', $routeOptions)
         && $routeOptions['type'] == 'object'
         && $action != 'new'
         && $action != 'create'
         && $this-><?php echo $this->getSingularName() ?> = $this->getRoute()->getObject())
      {
         $this-><?php echo $this->getParentSingularName() ?> = $this-><?php echo $this->getSingularName() ?>-><?php echo $this->getParentModelClass() ?>;
      }
      else
      {
         $<?php echo $this->getParentForeignKey() ?> = $request->getParameter('<?php echo $this->getParentForeignKey() ?>', 0);

         // check if <?php echo $this->getParentForeignKey() ?> is coming in from a form submission and override if available
         if($request->hasParameter('<?php echo $this->getSingularName() ?>'))
         {
            $<?php echo $this->getSingularName() ?> = $request->getParameter('<?php echo $this->getSingularName() ?>');
            if(array_key_exists('<?php echo $this->getParentForeignKey() ?>', $<?php echo $this->getSingularName() ?>))
            {
               $<?php echo $this->getParentForeignKey() ?> = $<?php echo $this->getSingularName() ?>['<?php echo $this->getParentForeignKey() ?>'];
            }
         }

         $this-><?php echo $this->getParentSingularName() ?> = Doctrine::getTable('<?php echo $this->getParentModelClass() ?>')->find($<?php echo $this->getParentForeignKey() ?>);
      }

      if($this-><?php echo $this->getParentSingularName() ?>)
      {
         $this->getContext()->getRouting()->setDefaultParameter('<?php echo $this->getParentForeignKey() ?>', $this-><?php echo $this->getParentSingularName() ?>-><?php echo $this->getParentPrimaryKey() ?>);
      }
   <?php if($this->isParentRequired()): ?>
      else
      {
         $this->forward404();
      }
   <?php endif; ?>
<?php endif; ?>

      return parent::execute($request);
   }
<?php endif; ?>

<?php include dirname(__FILE__).'/../../parts/indexAction.php' ?>

<?php if ($this->configuration->hasFilterForm()): ?>
<?php include dirname(__FILE__).'/../../parts/filterAction.php' ?>
<?php endif; ?>

<?php include dirname(__FILE__).'/../../parts/newAction.php' ?>

<?php include dirname(__FILE__).'/../../parts/createAction.php' ?>

<?php include dirname(__FILE__).'/../../parts/editAction.php' ?>

<?php if($this->isAuditable()): ?>
<?php include dirname(__FILE__).'/../../parts/historyAction.php' ?>
<?php include dirname(__FILE__).'/../../parts/showAction.php' ?>
<?php endif; ?>

<?php include dirname(__FILE__).'/../../parts/updateAction.php' ?>

<?php include dirname(__FILE__).'/../../parts/deleteAction.php' ?>

<?php if ($this->configuration->getValue('list.batch_actions')): ?>
<?php include dirname(__FILE__).'/../../parts/batchAction.php' ?>
<?php endif; ?>

<?php include dirname(__FILE__).'/../../parts/processFormAction.php' ?>

<?php if ($this->configuration->hasFilterForm()): ?>
<?php include dirname(__FILE__).'/../../parts/filtersAction.php' ?>
<?php endif; ?>

<?php include dirname(__FILE__).'/../../parts/paginationAction.php' ?>

<?php include dirname(__FILE__).'/../../parts/sortingAction.php' ?>
}
