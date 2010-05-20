<?php

require_once dirname(__FILE__).'/../lib/imageGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/imageGeneratorHelper.class.php';

/**
 * image actions.
 *
 * @package    LoveToKnow
 * @subpackage image
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class imageActions extends autoImageActions
{
   public function executeCreate(sfWebRequest $request)
   {
      $this->shouldPreventAjaxRedirect = true;

      parent::executeCreate($request);

      if($this->ajaxRedirectAttempted)
      {
         // the form was submitted via ajax so we just want to send back the new object's id
         $this->getResponse()->setContentType('application/json');
         return $this->renderText(json_encode(array('success' => true, 'value' => (int)$this->image->id, 'thumb' => $this->image->thumbUrl)));
      }
   }

   public function executeBrowse(sfWebRequest $request)
   {
      $this->getResponse()->setContentType('application/json');

      $images = Doctrine::getTable('Image')->searchByFilename($this->channel, $request->getParameter('term'), 5);

      $results = array();

      foreach($images as $image)
      {
         $results[] = array('thumb' => $image->thumbUrl, 'label' => $image->filename, 'value' => $image->id);
      }

      return $this->renderText(json_encode($results));
   }

   public function redirect($url, $statusCode = 302)
   {
      // we dont want to redirect some ajax requests
      if($this->shouldPreventAjaxRedirect && $this->getRequest()->isXmlHttpRequest())
      {
         $this->ajaxRedirectAttempted = true;
      }
      else
      {
         parent::redirect($url, $statusCode);
      }
   }
}
