<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\QuizQuestion;
use Carbon\Carbon;

class QuizAnswerUsersFlow extends Model
{
	protected $table = 'quiz_answer_users_flow';	
	
	protected $fillable = ['user_id', 'quiz_id', 'answer_id', 'question_id'];
	
}
