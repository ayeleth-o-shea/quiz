<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\QuizQuestion;
use Carbon\Carbon;
use App\Models\QuizAnswerUsersFlow;

class QuizUsersFlow extends Model
{
	protected $table = 'quiz_users_flow';
	
	protected $fillable = ['user_id', 'quiz_id', 'time', 'question_id', 'final_score', 'result', 'is_complete', 'last_question', 'session'];
	
}
