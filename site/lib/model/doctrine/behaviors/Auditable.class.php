<?php

class Auditable extends Doctrine_Template_Versionable
{
   public function __construct(array $options = array())
   {
      $defaultOptions = array(
         'template_name_suffixes' => array('_at'),
         'user_name_suffix'       => '_by',
         'user'                   => array('model'  => 'sfGuardUser',
                                           'name'   => 'id',
                                           'type'   => 'integer',
                                           'length' => 4)
      );

      parent::__construct(Doctrine_Lib::arrayDeepMerge($defaultOptions, $options));

      $this->_plugin = new RevisionHistory($this->_options);
   }

   protected function parseBaseName($name)
   {
      $baseName = $name;

      foreach($this->_options['template_name_suffixes'] as $templateSuffix)
      {
         $nameLen = strlen($baseName);
         $suffixLen = strlen($templateSuffix);
         $suffix = substr($name, $nameLen - $suffixLen);

         if($suffix == $templateSuffix)
         {
            return substr($baseName, 0, $nameLen - $suffixLen);
         }
      }

      return $baseName;
   }

   protected function setUpFieldAudit(&$options, $nameKey = 'name')
   {
      $baseName = $this->parseBaseName($options[$nameKey]);
      $name = $baseName . $this->_options['user_name_suffix'];
      $options["user_$nameKey"] = $name;

      $this->hasColumn($name, $this->_options['user']['type'], $this->_options['user']['length']);

      $relationName = $this->_options['user']['model'] . ' as ' . Doctrine_Inflector::classify($baseName);
      $this->hasOne($relationName, array('local' => $name, 'foreign' => $this->_options['user']['name']));
   }

   protected function setUpTemplateAudit($templateName, array $fields = array(), $nameKey = 'name', $disabledKey = 'disabled')
   {
      if($this->_table->hasTemplate($templateName))
      {
         $template = $this->_table->getTemplate($templateName);
         $this->_options[$templateName] = $template->getOptions();

         if(empty($fields))
         {
            if(!array_key_exists($disabledKey, $this->_options[$templateName]) || !$this->_options[$templateName][$disabledKey])
            {
               $this->setUpFieldAudit($this->_options[$templateName], $nameKey);
            }
         }
         else
         {
            foreach($fields as $field)
            {
               if(!array_key_exists($disabledKey, $this->_options[$templateName][$field]) || !$this->_options[$templateName][$field][$disabledKey])
               {
                  $this->setUpFieldAudit($this->_options[$templateName][$field], $nameKey);
               }
            }
         }
      }
   }

   /**
   * Setup the Auditable behavior for the template
   *
   * @return void
   */
   public function setUp()
   {
      if(!$this->_table->hasTemplate('Timestampable'))
      {
         $this->actAs('Timestampable');
      }

      $this->setUpTemplateAudit('Timestampable', array('created', 'updated'));
      $this->setUpTemplateAudit('Publishable');
      $this->setUpTemplateAudit('SoftDelete');

      $this->addListener(new AuditableListener($this->_options));

      parent::setUp();
   }

   public function getHistory()
   {
      return $this->_plugin->getHistory($this->getInvoker());
   }

   public function getRevision($version)
   {
      $auditLog = $this->_plugin;

      if ( ! $auditLog->getOption('auditLog'))
      {
         throw new Doctrine_Record_Exception('Audit log is turned off, no version history is recorded.');
      }

      $data = $auditLog->getVersion($this->getInvoker(), $version);

      if ( ! isset($data[0]))
      {
         throw new Doctrine_Record_Exception('Version ' . $version . ' does not exist!');
      }

      return $data[0];
   }
}
