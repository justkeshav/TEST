<?php
/**
* Batch process to import quiz data from the old LTK databases.
*/

require_once(dirname(__FILE__).'/helpers/wiki.php');

class QuizImportBatch extends WikiChannelImportBatch
{
   protected function getScriptFile()
   {
      return __FILE__;
   }

   protected function doImport()
   {
      $this->logNotice("Importing quizzes");

       $quizzes = Doctrine_Manager::connection()->fetchAssoc("
          select
             p.page_id, p.page_title, q.survey_id, q.survey_title, q.channel_name, q.channel_category, q.created_date,
             q.description, q.bottom_description, q.survey_end_message, q.survey_url,
             qp.result_type, qp.page_questions, qp.result_20, qp.result_40, qp.result_80, qp.result_100,
             qp.anonymous_login, qp.all_user_results, qp.login_page_text, qp.login_position, qp.poll_style,
             qp.is_poll, qp.accuracy
          from
             `OLD_ltk_master`.SURVEYS q
             join `OLD_ltk_master`.SURVEY_PROPERTIES qp on q.survey_id = qp.survey_id
	         join `{$this->database}`.page p on q.survey_url = p.page_title or concat('Survey:', q.survey_url) = p.page_title
	         join `{$this->database}`.revision r on p.page_id = r.rev_page
	         where
	            q.PUBLISH = 1
	            and q.DELETED = 0
	            and length(p.page_title) > 0
	            and p.page_namespace = 0
	         group by
	            p.page_id");

       foreach($quizzes as $q)
       {
          $this->importQuiz($q);
       }
   }

   private function importQuiz($query)
   {
      $this->logInfo("Importing quiz '$title'");

      $quiz = new Quiz();

      $quiz->Channel      = $this->channel;
      $quiz->title        = str_replace('_', ' ', $query['survey_title']);
      $quiz->type         = 'Quiz';
      $quiz->url          = str_replace(' ', '_', $query['page_title']);
      $quiz->created_at   = date('Y-m-d H:i:s', strtotime($query['created_date']));
      $quiz->updated_at   = $quiz->created_at;

      $quiz->Title->status = Title::STATUS_PUBLISHED;
      $quiz->Title->published_content_version = 1;
      $quiz->Title->author_user_id = 1;

      // legacy quiz data has only one category
      $category = Doctrine::getTable('Category')->findOneByUrl("{$this->urlPrefix}Category:{$query['channel_category']}", $this->channel);
      if ($category != null)
      {
	      $quiz->Categories[] = $category;
	  }

      $quiz->description1 = $query['description'];
      $quiz->description2 = $query['bottom_description'];
      $quiz->message      = $query['survey_end_message'];
      $quiz->format       = ( strcmp($query['is_poll'],'1') == 0 ? Quiz::TYPE_POLL : Quiz::TYPE_QUIZ );

      switch ($query['result_type'])
      {
	     case ('A'):
	        $quiz->result_format = Quiz::RESULT_AFTER;
	        break;
	     case ('S'):
	        $quiz->result_format = Quiz::RESULT_END;
	        break;
	     case ('E'):
	        $quiz->result_format = Quiz::RESULT_EMAIL;
	        break;
	     case ('P'):
	        $quiz->result_format = Quiz::RESULT_PATTERN;
	        break;
	     case ('B'):
	     default:
            $quiz->result_format = Quiz::RESULT_BOTH;
            break;
      }

      switch ($query['login_position'])
      {
	     case ('1'):
	        $quiz->login_position = Quiz::LOGIN_OPTIONAL;
	        break;
	     case ('2'):
	        $quiz->login_position = Quiz::LOGIN_ANON;
	        break;
	     case ('999'):
	        $quiz->login_position = Quiz::LOGIN_RESULT;
	        break;
	     case ('99'):
	        $quiz->login_position = Quiz::LOGIN_AFTER;
	        break;
	     case ('0'):
	     default:
            $quiz->login_position = Quiz::LOGIN_OPTIONAL;
            break;
      }

      $quiz->result_type        = ( strcmp($query['all_user_results'], '1') == 0 ? Quiz::SHOW_RESULTS : Quiz::SHOW_VALID );
      $quiz->questions_per_page = $query['page_questions'];
      $quiz->show_accuracy      = $query['accuracy'];
      $quiz->login_text         = $query['login_page_text'];
      $quiz->result20           = $query['result_20'];
      $quiz->result40           = $query['result_40'];
      $quiz->result60           = $query['result_60'];
      $quiz->result80           = $query['result_80'];
      $quiz->result100          = $query['result_100'];

      $quiz->save();

      $this->importQuestions($quiz->id, $query['survey_id']);

      //$this->importSessions($quiz->id, $query['survey_id']);

      $quiz->free();
   }

   private function importQuestions($new_id, $old_id)
   {
      $questions = Doctrine_Manager::connection()->fetchAssoc("
          select
             q.question_id, q.question, q.image_name, q.question_type, q.image_link
          from
             `OLD_ltk_master`.QUESTIONS q
	      where
	         q.survey_id = {$old_id}
	         and q.deleted = 0
	   ");

       foreach($questions as $q)
       {
	      $quizContent = new QuizContent();
	      $quizContent->quiz_id = $new_id;
	      $quizContent->message = $q['question'];
	      $quizContent->format = ( strcmp($q['question_type'], 'O') == 0 ? QuizContent::FORMAT_MULTIPLE : QuizContent::FORMAT_FREETEXT );
	      $quizContent->parent_id = 0;

	      $this->importImage($quizContent, "$old_id/", $q['image_name'], $q['image_link'] );

          $quizContent->save();

          $this->importAnswers($quizContent->quiz_id, $quizContent->id, $q['question_id']);

          $quizContent->free();
       }
   }

   private function importAnswers($quiz_id, $new_id, $old_id)
   {
      $answers = Doctrine_Manager::connection()->fetchAssoc("
          select
             qa.answer, qa.acceptable, qa.remarks, qa.total_count
          from
             `OLD_ltk_master`.QUESTION_ANSWERS qa
	      where
	         qa.question_id = {$old_id}
	         and qa.deleted = 0
	   ");

       foreach($answers as $qa)
       {
	      $quizContent = new QuizContent();
	      $quizContent->quiz_id = $quiz_id;
	      $quizContent->message = $qa['answer'] . "|" . $qa['remarks'];
	      $quizContent->is_valid = ( strcmp($qa['acceptable'], '1') == 0 );
	      $quizContent->parent_id = $new_id;
	      $quizContent->total_count = $qa['total_count'];

          $quizContent->save();

          $quizContent->free();
       }
   }

   private function importImage($quizContent, $filepath, $filename, $image_name, $image_link)
   {
      $this->logInfo("Importing quiz image '$image_name'");

      $image = new Image();
      $image->channel_id = $this->channel->id;

      $tempFile = '/tmp/' . str_replace('/', '-', $image_name);

      if(copy("/shared/lovetoknow/wwwvhost/channels/skins/Survey/Upload/$filepath", $tempFile))
      {
         if(ltksfImageProvider::getInstance()->create($tempFile, $image))
         {
            if(ltksfImageProvider::getInstance()->createSize($image, 'quiz', 600, 500))
            {
               $quizContent->Image = $image;
               $quizContent->image_link = $image_link;
            }
            else
            {
               $this->logError("Unable to create 'quiz' size image from '$tempFile' for slide '$image_name'");
            }
         }
         else
         {
            $this->logError("Unable to create quiz image from '$tempFile' for quiz '$image_name'");
         }
      }
      else
      {
         $this->logError("Unable to copy quiz image '$filepath' to '$tempFile' for quiz '$image_name'");
      }
   }

   private function importSessions($quiz_id, $old_id)
   {
      $sessions = Doctrine_Manager::connection()->fetchAssoc("
          select
             u.name, u.email, u.date_entered, u.ip, u.browser
          from
             `OLD_ltk_master`.SURVEY_USERS u
	      where
	         u.survey_id = {$old_id}
	   ");

       foreach($sessions as $s)
       {
	      $quizSession = new QuizSession();
	      $quizSession->quiz_id = $quiz_id;
	      $quizSession->name = $s['name'];
	      $quizSession->email = $s['email'];
	      $quizSession->ip = $s['ip'];
	      $quizSession->agent_string = $s['browser'];
	      $quizSession->created_at = date('Y-m-d H:i:s', strtotime($query['date_entered']));
	      $quizSession->updated_at = $quizSession->created_at;

          $quizSession->save();

          $quizSession->free();
       }
   }
}

QuizImportBatch::run();
