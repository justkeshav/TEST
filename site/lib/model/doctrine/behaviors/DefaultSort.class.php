<?php

class DefaultSort extends Doctrine_Template
{
    /**
     * Array of DefaultSort options
     *
     * @var string
     */
    protected $_options = array(
        'column'    => null,
        'sorting'   => 'ASC',
        'indexName' => 'default_sort'
    );

    /**
     * Set table definition for DefaultSort behavior
     *
     * @return void
     */
    public function setTableDefinition()
    {
        $columnName = $this->_options['column'];

        if(!isset($columnName))
        {
           throw new Doctrine_Exception("DefaultSort behavior enabled for '{$this->_table->getComponentName()}' but required option 'column' is not set.");
        }

        if(!$this->_table->hasColumn($columnName))
        {
           throw new Doctrine_Exception("DefaultSort behavior enabled for '{$this->_table->getComponentName()}' but sort column '$columnName' not found.");
        }

        $sorting = strtoupper($this->_options['sorting']);

        if($sorting != 'ASC' && $sorting != 'DESC')
        {
           throw new Doctrine_Exception("DefaultSort behavior 'sorting' option must be either 'ASC' or 'DESC'.");
        }

        $this->index($this->_options['indexName'], array('fields' => array($columnName => array('sorting' => $sorting))));

        $this->addListener(new DefaultSortListener($this->_options));
    }
}
