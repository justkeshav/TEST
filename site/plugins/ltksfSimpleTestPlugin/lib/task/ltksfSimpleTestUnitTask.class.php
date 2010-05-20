<?php

class ltksfSimpleTestUnitTask extends sfTestBaseTask
{
   /**
   * @see sfTask
   */
   protected function configure()
   {
      $this->addArguments(array(
         new sfCommandArgument('name', sfCommandArgument::OPTIONAL | sfCommandArgument::IS_ARRAY, 'The test name'),
      ));

      $this->addOptions(array(
         new sfCommandOption('xml', null, sfCommandOption::PARAMETER_REQUIRED, 'The file name for the JUnit compatible XML file'),
      ));

      $this->namespace = 'simpletest';
      $this->name = 'unit';
      $this->briefDescription = 'Launches unit tests';

      $this->detailedDescription = <<<EOF
The [simpletest:unit|INFO] task launches SimpleTest unit tests:

  [./symfony simpletest:unit|INFO]

The task launches all tests found in [test/unit|COMMENT].

If some tests fail, you can use the [--trace|COMMENT] option to have more
information about the failures:

  [./symfony simpletest:unit -t|INFO]

You can launch unit tests for a specific name:

  [./symfony simpletest:unit testName|INFO]

You can also launch unit tests for several names:

  [./symfony simpletest:unit testName1 testName2|INFO]

The task can output a JUnit compatible XML file with the [--xml|COMMENT]
options:

  [./symfony simpletest:unit --xml=results.xml|INFO]
EOF;
   }

   /**
   * @see sfTask
   */
   protected function execute($arguments = array(), $options = array())
   {
      require_once(dirname(__FILE__).'/../vendor/simpletest/unit_tester.php');
      require_once(dirname(__FILE__).'/../vendor/simpletest/extensions/colortext_reporter.php');
      require_once(dirname(__FILE__).'/../vendor/simpletest/extensions/junit_xml_reporter.php');

      $files = array();

      if(count($arguments['name']))
      {
         foreach ($arguments['name'] as $name)
         {
            $finder = sfFinder::type('file')->follow_link()->name(basename($name).'Test.php');
            $files = array_merge($files, $finder->in(sfConfig::get('sf_test_dir').'/unit/'.dirname($name)));
         }
      }
      else
      {
         $finder = sfFinder::type('file')->follow_link()->name('*Test.php');
         $files = $finder->in(sfConfig::get('sf_test_dir').'/unit/');
      }

      if($allFiles = $this->filterTestFiles($files, $arguments, $options))
      {
         $suite = new TestSuite();

         foreach ($allFiles as $file)
         {
            $suite->addFile($file);
         }

         $reporter = new ColorTextReporter();

         if($options['xml'])
         {
            $reporter = new JUnitXMLReporter();
            ob_start();
         }

         $result = $suite->run($reporter);

         if($options['xml'])
         {
            file_put_contents($options['xml'], ob_get_contents());
            ob_end_clean();
         }

         if(SimpleReporter::inCli())
         {
            exit($result ? 0 : 1);
         }
      }
      else
      {
         $this->logSection('simpletest', 'no tests found', null, 'ERROR');
      }
   }
}
