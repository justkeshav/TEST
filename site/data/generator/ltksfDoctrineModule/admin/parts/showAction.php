
   public function executeShow(sfWebRequest $request)
   {
      $this-><?php echo $this->getSingularName() ?> = $this->getRoute()->getObject();
      $this->revision = $this-><?php echo $this->getSingularName() ?>->getRevision($request->getParameter('version'));
   }