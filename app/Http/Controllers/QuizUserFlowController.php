<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizUsersFlow;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;

class QuizUserFlowController extends Controller
{
	private $userId;
	private $quiz;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($userId, $quizId)
    {
		$this->userId = $userId;
		$this->quizId = $quizId;
    }

    public static function getUserFlowState($userId, $quizId):array
	{
		$arReturn = [];
		
		$userAnswers = QuizAnswerUsersFlow::where('quiz_id', $quizId)->where('user_id', $userId)->get();
		
		$aSettings = QuizQuestion::where('quiz_id', $quizId)->get();
		$answers = [];
		
		foreach ($aSettings as $a) {
			$answers[$a->id] = $a;
		}
		
		$qCount = QuizQuestion::where('quiz_id', $quizId)->count();
		if ($qCount == count($userAnswers))
			$arReturn['isComplete'] = true;
		else $arReturn['isComplete'] = false;
		
		if (count($userAnswers) == 0)
			$arReturn['isFirstQuestion'] = 1;
		else $arReturn['isFirstQuestion'] = 0;
		
		if (count($userAnswers) == $qCount-1)
			$arReturn['isLastQuestion'] = 1;
		else $arReturn['isLastQuestion'] = 0;
		
		$arReturn['totalScore'] = 0;
		
		foreach ($userAnswers as $answer) {
			if ($answer->is_correct == 1)
				$arReturn['totalScore'] += $answers[$answer->question_id]['score'];
		}
		
		return $arReturn;
		
	}
	
	
	public static function getCurrent($userId, $quizId)
	{
		return self::where('quiz_id', $quizId)->where('user_id', $userId)->get();
	}
	
	public static function update(array $data, $id)
	{

		  $flow = QuizUsersFlow::find($id);
		  $flow->final_score = $data['final_score'];
		  $flow->result = $data['result'];
		  $flow->is_complete = $data['is_complete'];
		  $flow->last_question = $data['last_question'];

		  $flow->save();

	}
}
