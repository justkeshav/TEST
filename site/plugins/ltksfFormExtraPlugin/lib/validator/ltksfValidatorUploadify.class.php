<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorReCaptcha validates a ReCaptcha.
 *
 * This validator uses ReCaptcha: http://recaptcha.net/
 *
 * The ReCaptcha API documentation can be found at http://recaptcha.net/apidocs/captcha/
 *
 * To be able to use this validator, you need an API key: http://recaptcha.net/api/getkey
 *
 * To create a captcha validator:
 *
 *    $captcha = new sfValidatorReCaptcha(array('private_key' => RECAPTCHA_PRIVATE_KEY));
 *
 * where RECAPTCHA_PRIVATE_KEY is the ReCaptcha private key.
 *
 * @package    symfony
 * @subpackage validator
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfValidatorReCaptcha.class.php 7903 2008-03-15 13:17:41Z fabien $
 */
class ltksfValidatorUploadify extends sfValidatorBase
{
   /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * private_key:    The ReCaptcha private key (required)
   *  * remote_addr:    The remote address of the user
   *  * server_host:    The ReCaptcha server host
   *  * server_port:    The ReCaptcha server port
   *  * server_path:    The ReCatpcha server path
   *  * server_timeout: The timeout to use when contacting the ReCaptcha server
   *
   * Available error codes:
   *
   *  * captcha
   *  * server_problem
   *
   * @see sfValidatorBase
   */
   protected function configure($options = array(), $messages = array())
   {
      parent::configure($options, $messages);

      $this->addOption('file_types');
      $this->addMessage('type_not_allowed', 'Uploaded file must be an allowed type (%types%).');

      $this->addMessage('file_not_found', 'Uploaded file not found (%file%).');
   }

   /**
   * Cleans the input value.
   *
   * The input value must be an array with 2 required keys: recaptcha_challenge_field and recaptcha_response_field.
   *
   * It always returns null.
   *
   * @see sfValidatorBase
   */
   protected function doClean($value)
   {
      $clean = (string)$value;

      $filename = sfConfig::get('sf_web_dir') . $clean;

      $pathinfo = pathinfo($filename);
      if(!in_array(strtolower($pathinfo['extension']), $this->getOption('file_types')))
      {
         $types = '*.' . implode('; *.', $this->getOption('file_types'));
         throw new sfValidatorError($this, 'type_not_allowed', array('file' => $filename, 'types' => $types));
      }

      if(!file_exists($filename))
      {
         throw new sfValidatorError($this, 'file_not_found', array('file' => $filename));
      }

      return $clean;
   }
}
