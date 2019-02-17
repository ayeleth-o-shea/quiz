<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizUsersFlow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_users_flow', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('quiz_id')->index();
			$table->integer('user_id')->index();
			$table->string('time');
			$table->boolean('is_complete')->index();
			$table->enum('result', ['success', 'fail'])->index();
			$table->string('final_score');
			$table->string('last_question');
			$table->string('session');
            $table->timestamps();
        });
		
		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::dropIndex('is_complete');
		Schema::dropIndex('result');
    }
}
