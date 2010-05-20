<?php if($this->isAuditable()): ?>
[?php use_helper('I18N', 'Date') ?]
[?php include_partial('<?php echo $this->getModuleName() ?>/assets') ?]

<div id="sf_admin_container">
  <h1>[?php echo <?php echo $this->getI18NString('history.title') ?> ?]</h1>

  [?php include_partial('<?php echo $this->getModuleName() ?>/flashes') ?]

   <div id="sf_admin_content">
      <div class="sf_admin_list">
         <table cellspacing="0">
            <thead>
               <tr>
                  <th class="sf_admin_text">#</th>
                  <th class="sf_admin_text">Revision Date</th>
                  <th class="sf_admin_text">User</th>
                  <th id="sf_admin_list_th_actions">Actions</th>
               </tr>
            </thead>
            <tfoot>
               <tr>
                  <th colspan="4">
                     [?php echo format_number_choice('[0] no revisions|[1] 1 revision|(1,+Inf] %1% revisions', array('%1%' => count($revisions)), count($revisions), 'sf_admin') ?]
                  </th>
               </tr>
            </tfoot>
            <tbody>
               [?php $i = 0; foreach($revisions as $revision): $odd = fmod(++$i, 2) ? 'odd' : 'even'; ?]
               <tr class="sf_admin_row[?php echo " $odd" ?]">
                  <td class="sf_admin_text">[?php echo $revision['version'] ?]</td>
                  <td class="sf_admin_text">[?php echo $revision['updated_at'] ?]</td>
                  <td class="sf_admin_text">[?php if(!empty($revision['updated_by'])) echo $revision['UpdatedBy'] ?]</td>
                  <td>
                     <ul class="sf_admin_td_actions">
                        <li class="sf_admin_action_edit">
                           [?php echo link_to('View', '<?php echo $this->params['route_prefix'] ?>/show?<?php echo $this->getPrimaryKeyUrlParams() ?>.'&version='.$revision['version']) ?]
                        </li>
                     </ul>
                  </td>
               </tr>
               [?php endforeach; ?]
            </tbody>
         </table>
      </div>
   </div>

</div>
<?php endif; ?>
