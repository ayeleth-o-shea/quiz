<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizUsersFlow;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use App\Models\QuizAnswerUsersFlow;
use Illuminate\Support\Facades\Auth;

class QuizUsersFlowController extends Controller
{
	
	public function quizcomplete(Request $request)
	{
		$user = Auth::user();
		
		$curFlow = self::getCurrent($request->get('quiz_id'), $user->id);
		
		$userQuizFlow = self::getUserFlowState($request->get('quiz_id'), $user->id);

		$fData = [
					'is_complete'=> $userQuizFlow['isComplete'],
					'result' => $userQuizFlow['result'],
					'final_score' => $userQuizFlow['totalScore'],
					'last_question' => $request->get('question_id'),
					'session' => $request->session()->get('id')
			];	
		
		if ($curFlow instanceof QuizUsersFlow){
			$flowId = $curFlow->id;
			self::update($fData, $flowId);
		} 
		
		$data['result'] = 'Success!';
		$data['score'] = '30';
		$data['pageTitle'] = 'Тест окончен!';
		
		return view('quiz.quizcomplete', $data);
	}

    public static function getUserFlowState($userId, $quizId):array
	{
		$arReturn = [];
		
		$userAnswers = QuizAnswerUsersFlow::where('quiz_id', $quizId)->where('user_id', $userId)->get();
		
		
		$aSettings = QuizQuestion::where('quiz_id', $quizId)->get();
		$answers = [];
		$neededScore = 0;
		
		foreach ($aSettings as $a) {
			$answers[$a->id] = $a;
			$neededScore += $a['score'];
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
		
		$arReturn['result'] = 'fail';
		if ($neededScore == $arReturn['totalScore'])
			$arReturn['result'] = 'success';
		
		return $arReturn;
		
	}
	
	
	public static function getCurrent($userId, $quizId)
	{
		$items = [];
		$items = QuizUsersFlow::where('quiz_id', $quizId)->where('user_id', $userId)->get();
		if (count($items) > 0)
			return $items[0];
		else return $items;
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
