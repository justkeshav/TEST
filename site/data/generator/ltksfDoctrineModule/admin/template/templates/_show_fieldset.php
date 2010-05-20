<fieldset id="sf_fieldset_[?php echo preg_replace('/[^a-z0-9_]/', '_', strtolower($fieldset)) ?]">
  [?php if ('NONE' != $fieldset): ?]
    <h2>[?php echo __($fieldset, array(), '<?php echo $this->getI18nCatalogue() ?>') ?]</h2>
  [?php endif; ?]

  [?php foreach ($fields as $name => $field): ?]
    [?php include_partial('<?php echo $this->getModuleName() ?>/show_field', array(
      '<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>,
      'revision'   => $revision,
      'name'       => $name,
      'attributes' => $field->getConfig('attributes', array()),
      'label'      => $field->getConfig('label'),
      'relation'   => $field->getConfig('relation'),
      'date'       => $field->getConfig('date'),
      'user'       => $field->getConfig('user'),
      'field'      => $field,
      'class'      => 'sf_admin_form_row sf_admin_'.strtolower($field->getType()).' sf_admin_form_field_'.$name,
    )) ?]
  [?php endforeach; ?]
</fieldset>
