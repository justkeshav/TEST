<?php if($this->isDerivative()): ?>
[?php echo "{$<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>} (Status: {$<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->status})" ?]
<ul class="sf_admin_td_actions" style="display: inline; padding-left: 1em;">
   <li class="sf_admin_action_edit">
      [?php 
         if (!$sf_user->getGuardUser()->isWriter())
         {
            echo link_to('Edit Title', "@<?php echo sfInflector::underscore($this->getDerivativeBase()) ?>_edit?id={$<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->id}");
         }
         else if ($<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->status == Title::STATUS_INPROGRESS)
         {
            echo link_to('Edit Image', "<?php echo sfInflector::underscore($this->getDerivativeBase()) ?>/edit?id={$<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->id}");
         }
      ?]
   </li>
   <li class="sf_admin_action_submit">
      [?php 
         if ($<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->status == Title::STATUS_INPROGRESS)
         {
            echo link_to('Submit', "<?php echo sfInflector::underscore($this->getDerivativeBase()) ?>/submit?id={$<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->id}");
         }
      ?]
   </li>
   [?php if ($sf_user->getGuardUser()->isEditor()): ?]
      <li class="sf_admin_action_approve">   
         [?php
            if ($<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->status == Title::STATUS_SUBMITTED)
            {
               echo link_to('Approve', "<?php echo sfInflector::underscore($this->getDerivativeBase()) ?>/approve?id={$<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->id}");
            }
         ?]
      </li>
      <li class="sf_admin_action_reject">   
         [?php
            if ($<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->status == Title::STATUS_SUBMITTED)
            {   
               echo link_to('Reject', "<?php echo sfInflector::underscore($this->getDerivativeBase()) ?>/reject?id={$<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->id}");
            }
         ?]
      </li>
      <li class="sf_admin_action_publish">   
         [?php
            if ($<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->status == Title::STATUS_SUBMITTED)
            {   
               echo link_to('Publish', "<?php echo sfInflector::underscore($this->getDerivativeBase()) ?>/publish?id={$<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->id}");
            }
         ?]
      </li>
      <li class="sf_admin_action_reject">   
         [?php
            if ($<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->status == Title::STATUS_APPROVED)
            {
               echo link_to('Reject', "<?php echo sfInflector::underscore($this->getDerivativeBase()) ?>/reject?id={$<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->id}");
            }
         ?]
      </li>
      <li class="sf_admin_action_publish">   
         [?php
            if ($<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->status == Title::STATUS_APPROVED)
            {   
               echo link_to('Publish', "<?php echo sfInflector::underscore($this->getDerivativeBase()) ?>/publish?id={$<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->id}");
            }     
         ?]
      </li>
      <li class="sf_admin_action_reject">   
         [?php
            if ($<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->status == Title::STATUS_PUBLISHED)
            {
               echo link_to('Reject', "<?php echo sfInflector::underscore($this->getDerivativeBase()) ?>/reject?id={$<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->id}");
            }            
         ?]
      </li>
   [?php endif; ?]
</ul>
   [?php if ($<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->notes):?]
      </div></div></div>
      <div class="sf_admin_form_row sf_admin_text sf_admin_form_field_Title_notes">
      <div>
      <label for="title_notes">Notes:</label>
      <div class="content">
      [?php echo $<?php echo $this->getSingularName() ?>-><?php echo $this->getDerivativeBase() ?>->notes; ?]
   [?php endif; ?]

<?php endif; ?>
