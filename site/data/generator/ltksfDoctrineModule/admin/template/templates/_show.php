<div class="sf_admin_form">
    [?php foreach ($configuration->getShowFields($<?php echo $this->getSingularName() ?>->getRawValue(), $revision) as $fieldset => $fields): ?]
      [?php include_partial('<?php echo $this->getModuleName() ?>/show_fieldset', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'revision' => $revision, 'fields' => $fields, 'fieldset' => $fieldset)) ?]
    [?php endforeach; ?]
</div>
