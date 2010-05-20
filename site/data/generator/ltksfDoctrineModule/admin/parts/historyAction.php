
   public function executeHistory(sfWebRequest $request)
   {
      $this-><?php echo $this->getSingularName() ?> = $this->getRoute()->getObject();
      $this->revisions = $this-><?php echo $this->getSingularName() ?>->getHistory();
      foreach($this->revisions as &$revision)
      {
         $revision['UpdatedBy'] = Doctrine::getTable('sfGuardUser')->find($revision['updated_by']);
      }
   }