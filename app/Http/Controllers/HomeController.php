<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use App\Models\QuizAnswerUsersFlow;
use App\Models\QuizUsersFlow;
use App\Http\Controllers\QuizUsersFlowController;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{	
	private $quizId = 1;
	private $quiz = null;
	private $pageTitle = null;
	private $questions;
	private $answers = [];
	private $count;
	protected $user;
	
	
	protected $redirectTo = '/home';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
		
		$this->quiz = Quiz::where('id', $this->quizId)->get();
		$this->pageTitle = 'MySQL 5.7 Database Administrator';
		$this->questions = QuizQuestion::where('quiz_id', $this->quizId)->paginate(1);
		$allAnswers = QuizAnswer::all();

		foreach ($allAnswers as $a) {
			$this->answers[$a->question_id][] = $a;		
		}
		
		$this->count = QuizQuestion::where('quiz_id', $this->quizId)->count();	
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {	
		$data = ['quizId' => $this->quizId, 
				 'pageTitle' => $this->pageTitle,
				 'questions' => $this->questions,
				 'answers' => $this->answers,
				 'count' => $this->count
				 ];
		
		//dd($request->session()->all());
		
        return view('home', $data);
    }
	
	public function store(Request $request)
    {
	  if ($request->get('quiz_id'))
	  {
		  $request->validate([
			'answer'=>'required'
		  ]);
		  
		  $user = Auth::user();
		  
		  $aFlow = new QuizAnswerUsersFlow([
				'user_id' => $user->id,
				'quiz_id' => $request->get('quiz_id'),
				'answer_id'=> $request->get('answer'),
				'question_id'=> $request->get('question_id')
		  ]);
		  $aFlow->save();
		  
		  $userQuizFlow = QuizUsersFlowController::getUserFlowState($request->get('quiz_id'), $user->id);

		  //dd($userQuizFlow);	
		  
		  if ($userQuizFlow['isFirstQuestion'] == 1) {
			  $arData = [
					'user_id' => $user->id,
					'quiz_id' => $request->get('quiz_id'),
					'time'=> '',
					'is_complete'=> $userQuizFlow['isComplete'],
					'result' => $userQuizFlow['result'],
					'final_score' => $userQuizFlow['total_score'],
					'last_question' => $request->get('question_id'),
					'session' => $request->session()->get('id')
			  ];
				
		  } else {
			  $curFlow = QuizUsersFlowController::getCurrent($request->get('quiz_id'), $user->id);
			  if (count($curFlow) > 0){
				  dd($curFlow);
				 //$flowId = $curFlow->id;
				  QuizUsersFlowController::update($request, $flowId);
				  $this->redirectTo = '/quizcomplete';
			  } else {
				  $arData = [
						'user_id' => $user->id,
						'quiz_id' => $request->get('quiz_id'),
						'time'=> '',
						'is_complete'=> $userQuizFlow['isComplete'],
						'result' => $userQuizFlow['result'],
						'final_score' => $userQuizFlow['totalScore'],
						'last_question' => $request->get('question_id'),
						'session' => $request->session()->get('id')
				  ];
			  }
				
		  }
		  
		  $tFlow = new QuizUsersFlow($arData);
		  $tFlow->save();
		  
		  if ($userQuizFlow['isLastQuestion'] == 1) {
			   return redirect($this->redirectTo)->with('end', 'The quiz is done');
		  } else {
			  $pageNum = (int)$request->get('page');
			  $pageNum++;
			  return redirect($this->redirectTo);
		  }
	  }
      
    
	}

	
	
}
