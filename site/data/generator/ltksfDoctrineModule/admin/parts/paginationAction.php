  protected function getPager()
  {
    $pager = $this->configuration->getPager('<?php echo $this->getModelClass() ?>');
    $pager->setQuery($this->buildQuery());
    $pager->setPage($this->getPage());
    $pager->init();

    return $pager;
  }

  protected function setPage($page)
  {
    $this->getUser()->setAttribute('<?php echo $this->getModuleName() ?>.page', $page, 'admin_module');
  }

  protected function getPage()
  {
    return $this->getUser()->getAttribute('<?php echo $this->getModuleName() ?>.page', 1, 'admin_module');
  }

<?php if($this->hasParentModel()): ?>
  protected function addParentQuery($query)
  {
   <?php if($this->isParentRequired()): ?>
      $query->addWhere('<?php echo $this->getParentForeignKey() ?> = ?', $this-><?php echo $this->getParentSingularName() ?>-><?php echo $this->getParentPrimaryKey() ?>);
   <?php else: ?>
      if($this-><?php echo $this->getParentSingularName() ?>)
      {
         $query->addWhere('<?php echo $this->getParentForeignKey() ?> = ?', $this-><?php echo $this->getParentSingularName() ?>-><?php echo $this->getParentPrimaryKey() ?>);
      }
   <?php endif; ?>
  }

<?php endif; ?>
  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();
<?php if ($this->configuration->hasFilterForm()): ?>
    if (null === $this->filters)
    {
      $this->filters = $this->configuration->getFilterForm($this->getFilters());
    }

    $this->filters->setTableMethod($tableMethod);

    $query = $this->filters->buildQuery($this->getFilters());
<?php else: ?>
    $query = Doctrine::getTable('<?php echo $this->getModelClass() ?>')
      ->createQuery('a');

    if ($tableMethod)
    {
      $query = Doctrine::getTable('<?php echo $this->getModelClass() ?>')->$tableMethod($query);
    }
<?php endif; ?>

    $this->addSortQuery($query);

<?php if($this->hasParentModel()): ?>
    $this->addParentQuery($query);

<?php endif; ?>
    $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);
    $query = $event->getReturnValue();

    return $query;
  }
