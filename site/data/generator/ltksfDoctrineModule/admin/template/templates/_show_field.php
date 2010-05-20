[?php if ($field->isPartial()): ?]
  [?php include_partial('<?php echo $this->getModuleName() ?>/'.$name, array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'revision' => $revision, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?]
[?php elseif ($field->isComponent()): ?]
  [?php include_component('<?php echo $this->getModuleName() ?>', $name, array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'revision' => $revision, 'attributes' => $attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes)) ?]
[?php else: ?]
   <div class="[?php echo $class ?]">
      <div>
         <label>[?php echo $label ? $label : sfInflector::humanize($name) ?]</label>
         <div class="content">[?php
            switch($field->getType())
            {
               case 'Audit':
                  echo $date, $user ? ' by ' . $user['username'] : '';
                  break;

               case 'ForeignKey':
                  echo $relation ? $relation : '</div><div class="help">Not Set';
                  break;

               case 'Boolean':
                  echo $revision[$name] ? 'Yes' : 'No';
                  break;

               default:
                  echo $revision[$name] ? $revision[$name] : '</div><div class="help">Not Set';
            }
         ?]</div>
      </div>
   </div>
[?php endif; ?]
