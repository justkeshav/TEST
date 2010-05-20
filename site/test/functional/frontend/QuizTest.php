<?php

require_once(dirname(__FILE__).'/../../bootstrap/functional.php');

test_standard_conditions('/diet-fitness/healthy-food-myths-quiz', 'diet');

// test flow for 2 question quiz
//   login = anonymous
//   show results after each question = true
//   show results at end of quiz = true
//   show quiz accuracy = true

$browser = new LtkTestFunctional('diet');
$browser->
   get('/diet-fitness/healthy-food-myths-quiz')->

   with('response')->begin()->
      isStatusCode(200)->

      // the h1 should display the title
      checkElement('h1', 'Healthy Food Myths Quiz')->

      // <div id="question" /> should display the first question
      checkElement('div#question', '1. Salads are the best choice for healthy eating.')->

      // <input label="Q257_258" /> should display the first answer
      checkElement('label[for$="Q257_258"]','Yes, but salads can become boring unless you add dressings and toppings.')->

      // check for submit button
      checkElement('input[type$="submit"]', true)->

   end()->

   // select incorrect answer -> Question #1 results
   click('input[type$="submit"]', array(
      'Q257'  => '258',
      'score' => '0',
      'sid'   => '0'
   ))->
   
   with('response')->begin()->
      isStatusCode(200)->

	   // <p class="response" /> should display the response message
       checkElement('p.response', 'Incorrect. Extras added, and salads at fast food restaurants can contain more calories than a hamburger.')->

       // check for submit button
       checkElement('input[type$="submit"]', true)->

   end()->

   // click 'Next' -> Question #2
   click('input[type$="submit"]', array(
      'score' => '0',
      'sid'   => '0'
   ))->

   with('response')->begin()->
      isStatusCode(200)->

      // <div id="question" /> should display the first question
      checkElement('div#question', '2. Eating a vegetarian diet will for sure help you lose weight.')->

	  // check for submit button
	  checkElement('input[type$="submit"]', true)->
	
   end()->

   // select correct answer -> Question #2 results
   click('input[type$="submit"]', array(
      'Q261'  => '262',
      'score' => '0',
      'sid'   => '0'
   ))->

   with('response')->begin()->
      isStatusCode(200)->

	   // <p class="response" /> should display the response message
       checkElement('p.response', 'Correct. Vegetarian options often replace meat with flavor boosters like mayonnaise, cheese, and dressings, which increase the calorie count.')->

       // check for submit button
       checkElement('input[type$="submit"]', true)->

   end()->

   // click 'Next' -> quiz summary
   click('input[type$="submit"]', array(
      'score' => '1',
      'sid'   => '0'
   ))->	

   with('response')->begin()->
      isStatusCode(200)->

      // check that quiz accuracy = 50%
      checkElement('div#accuracy', 'You are 50% accurate.')->

      // check that correct result message has been displayed 
      checkElement('div#score span', 'You need to be more careful with what you eat.')->

   end()
;


// test flow for 1 question quiz
//   login = required
//   show results after each question = false
//   show accuracy = true

$browser = new LtkTestFunctional('diet');
$browser->
   get('/diet-fitness/fast-food-statistics')->

   with('response')->begin()->
      isStatusCode(200)->

      // verify that login form has been displayed
      checkElement('label[for$="login_name"]','Name')->

      // check for submit button
      checkElement('input[type$="submit"]', true)->

   end()->

   // submit login form -> Question #1 
   // click() didn't seem to like the fieldname syntax: login[%s]
   post('/diet-fitness/fast-food-statistics~1?question', array(
	      'login[name]'  => 'Aaron',
	      'login[email]' => 'aaron@lovetoknow.com',
	      'score' => '0',
	      'sid'   => '0'
	   ))->	
   
   with('response')->begin()->
      isStatusCode(200)->

      // the h1 should display the title
      checkElement('h1', 'Fast Food Statistics')->

      // <div id="question" /> should display the first question
      checkElement('div#question', '1. Americans spent more money on fast food last year than they did on education.')->

      // <input label="Q257_258" /> should display the first answer
      checkElement('label[for$="Q265_266"]','True')->

      // check for submit button
      checkElement('input[type$="submit"]', true)->

   end()->

   // click 'Next' -> Summary
   click('input[type$="submit"]', array(
	  'Q265'  => '266',
      'score' => '0',
      'sid'   => '0'
   ))->

   with('response')->begin()->
      isStatusCode(200)->

      // check that quiz accuracy = 100%
      checkElement('div#accuracy', 'You are 100% accurate.')->

   end()
;



