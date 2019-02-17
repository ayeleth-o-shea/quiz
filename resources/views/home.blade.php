@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $pageTitle }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
					<div class="text-right"><b>Всего вопросов:</b> <i class="badge">{{ $count }}</i></div>
					
                    @foreach ($questions as $index => $question)
						<p><i>#{{ $index+1 }} {{ $question['question_text'] }} </i></p>
							<form method="POST" action="{{ route('home') }}">
								@csrf
								<input type="hidden" value="{{ $question['id'] }}" name="question_id">
								<input type="hidden" value="{{ $quizId }}" name="quiz_id">
								@foreach ($answers[$question['id']] as $a) 
									<p><input type="radio" name="answer" value=" {{ $a['id'] }} "> {{ $a['answer_text'] }} </p>
								@endforeach 
								<button type="submit" class="btn btn-primary">
                                    Next
                                </button>
							</form>
							
					@endforeach
					<div class="text-right">
							<form method="POST" action="{{ route('quizcomplete') }}">
								<button type="submit" class="btn btn-primary">
                                    End exam
                                </button>
							</form>
					</div>
					 
					 <div class="text-center">{!! $questions->render() !!}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
