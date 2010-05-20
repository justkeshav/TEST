[?php use_helper('I18N', 'Date') ?]
[?php include_partial('<?php echo $this->getModuleName() ?>/assets') ?]

<div id="sf_admin_container">
  <h1>[?php echo __('Revision #%%version%% (%%updated_at%%)', array('%%version%%' => $revision['version'], '%%updated_at%%' => false !== strtotime($revision['updated_at']) ? format_date($revision['updated_at'], "f") : '&nbsp;'), 'messages') ?]</h1>

  [?php include_partial('<?php echo $this->getModuleName() ?>/flashes') ?]

  <div id="sf_admin_content">
    [?php include_partial('<?php echo $this->getModuleName() ?>/show', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'revision' => $revision, 'configuration' => $configuration, 'helper' => $helper)) ?]
  </div>
</div>
