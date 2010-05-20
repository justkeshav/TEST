<?php

/**
 * BaseQuizSession
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $quiz_id
 * @property string $name
 * @property string $email
 * @property string $ip
 * @property string $agent_string
 * @property Quiz $Quiz
 * 
 * @method integer     getQuizId()       Returns the current record's "quiz_id" value
 * @method string      getName()         Returns the current record's "name" value
 * @method string      getEmail()        Returns the current record's "email" value
 * @method string      getIp()           Returns the current record's "ip" value
 * @method string      getAgentString()  Returns the current record's "agent_string" value
 * @method Quiz        getQuiz()         Returns the current record's "Quiz" value
 * @method QuizSession setQuizId()       Sets the current record's "quiz_id" value
 * @method QuizSession setName()         Sets the current record's "name" value
 * @method QuizSession setEmail()        Sets the current record's "email" value
 * @method QuizSession setIp()           Sets the current record's "ip" value
 * @method QuizSession setAgentString()  Sets the current record's "agent_string" value
 * @method QuizSession setQuiz()         Sets the current record's "Quiz" value
 * 
 * @package    LoveToKnow
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class BaseQuizSession extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('quiz_session');
        $this->hasColumn('quiz_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('email', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('ip', 'string', 20, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '20',
             ));
        $this->hasColumn('agent_string', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));

        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Quiz', array(
             'local' => 'quiz_id',
             'foreign' => 'id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}