<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizUsersFlow;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use App\Models\QuizAnswerUsersFlow;

class QuizAnswerFlowController extends Controller
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
	
	
	public static function getCurrent($userId, $quizId)
	{
		$items = [];
		$items = QuizAnswerUsersFlow::where('quiz_id', $quizId)->where('user_id', $userId)->get();
		if (count($items) > 0)
			return $items[0];
		else return $items;
	}
	
	public static function update(array $data, $id)
	{

		  $flow = QuizAnswerUsersFlow::find($id);
		  $flow->answer_id = $data['answer_id'];
		  
		  $flow->save();

	}
}
