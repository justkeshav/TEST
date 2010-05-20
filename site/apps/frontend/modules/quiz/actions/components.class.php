<?php

class quizComponents extends sfComponents
{
   public function executeShow(sfWebRequest $request)
   {
	  $this->quiz = $this->content;

	  $this->action = 'question';
	  $this->form = new BaseForm();	
		
	  if ($request->isMethod(sfRequest::GET))
	  {
		 // display login if required
         if ($this->quiz->login_position == Quiz::LOGIN_REQUIRED 
	             || $this->quiz->login_position == Quiz::LOGIN_OPTIONAL)
	     {
		    $this->quiz->setPage($request['page']);
		    $this->action = 'login';
	        $this->form = new QuizLoginForm();
	     }
	     else
	     {
		    $this->quiz->setPage($request['page'] + 1);
		    $this->updateSession($request);
		 }
	  }
	  else
	  {
	     $this->quiz->setPage($request['page']);
	     $this->score = $request['score'];
	
	     // process login if needed
         if (isset($request['login']))
         {
	        if (is_string($request['login']))
	        {
		       $this->updateSession($request);
		    }
		    else
		    {
		       $this->form = new QuizLoginForm();
		       $this->form->bind($request['login']);
		       if (!$this->form->isValid())
		       {
			      $this->action = 'login';
			   }
			   else
			   {
			      $this->updateSession($request, $this->form->getValue('name'), $this->form->getValue('email'));
			      $this->form = new BaseForm();
			      if (isset($request['summary']))
			      {
				     $this->action = 'summary';
				  }
			   }
		    }
		 }
		 else
		 {
			// increment aggregate counts & grade each selected answer
		    $this->processResponse($request);

            // set action for next page
	        if (isset($request['result']))
	        {
		       $this->action = 'result';  
		    }
		    else if (isset($request['summary']))
		    {
			   if ($this->quiz->login_position == Quiz::LOGIN_RESULT)
			   {
			      $this->action = 'login';
			      $this->form = new QuizLoginForm();
			   }
			   else
			   {
	              $this->action = 'summary';
	           }   
	        }
	        $this->sid = $request['sid'];
	     }
      }
   }

   private function processResponse(sfWebRequest $request)
   {
      $this->selectedAnswers = array();
      $fields = $request->getPostParameters();
      foreach (array_keys($fields) as $key)
	  {
         if (strncmp($key,'Q',1) == 0)
         {
	        $value = $fields[$key];

	        $query = Doctrine::getTable('QuizContent')->createQuery('a')
		     ->where('a.id = ?', $value);
	        $answer = $query->execute();
	        $answer[0]->total_count++;
	        $answer[0]->save();

	        $this->selectedAnswers[$key] = $answer[0];

	        if ($answer[0]->is_valid == true)
	        {
		       $this->score += 1;
		    }
	     }
	  }
	}
	
   private function updateSession(sfWebRequest $request, $name = '', $email = '')
   {
      if ($request['sid'] != 0)
      {
	     $session = Doctrine::getTable('QuizSession')->find($request['sid']);
	  }
	  else
	  {
	     $session = new QuizSession();
		 $session->quiz_id = $this->quiz->id;
	     $session->ip = $_SERVER['REMOTE_ADDR'];
	     $session->agent_string = $_SERVER['HTTP_USER_AGENT'];
	  }

	  $session->name = $name;
      $session->email = $email;
	  $session->save();	

	  // subscribe to newsletter
	  if (!empty($email))
	  {
		$uri = explode('.', $request->getHost());
	    $this->subscribe(strtoupper($uri[0]), $email, $name);
	  }

	  $this->sid = $session->id;
   }

   private function subscribe($channel, $email, $name)
   {
	   // check if user is already subscribed to newsletter
       $query = Doctrine::getTable('Newsletter')->createQuery('n')
	     ->where('n.email = ?', $email);
       if (sizeof($query->execute()) != 0)
       {
          return;
	   }
	
	   // submit to remote server
	
	   if (!function_exists('curl_init'))
	   { 
	        throw new RuntimeException("Newsletter subscribe failed, CURL is not installed.");
	   }
       $ch = curl_init();
       $url = sfConfig::get('app_urls_newsletter');
       $queryString = "Default:{$channel}=TRUE&redirect=&email_address={$email}&server_action=subscribe&msgid=_MSGID_&list=LoveToKnow1";
       curl_setopt($ch, CURLOPT_URL, $url . '?' . $queryString);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       $output = curl_exec($ch);
       curl_close($ch);

	   // was newsletter subscribe successful?
	   if (strpos($output,"Your Address Has Been Added") === false)
	   {
          return;
       }
	
	   // add subscriber to ltk newsletter table
	   $sub = new Newsletter();
	   $sub->email = $email;
	   $sub->name  = $name;
	   $sub->ip = $_SERVER['REMOTE_ADDR'];
	   $sub->save();	
   }	
}
