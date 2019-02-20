@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $pageTitle }}</div>

                <div class="card-body">
					{{ $result }}
                        <div class="alert alert-success" role="alert">
                            You score is {{ $score }}
                        </div>
				</div>
            </div>
        </div>
    </div>
</div>
@endsection	