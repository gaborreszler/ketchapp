@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">Account</div>

					<div class="card-body">
						@if(session('success'))
							<div class="alert alert-success" role="alert">
								{{ session('success') }}
							</div>
						@endif

						<dl class="row">
							<dt class="col-sm-4">Name</dt>
							<dd class="col-sm-8">{{ $user->name }}</dd>

							<dt class="col-sm-4">E-mail address</dt>
							<dd class="col-sm-8">{{ $user->email }}</dd>

							<dt class="col-sm-4">Joined</dt>
							<dd class="col-sm-8">{{ $user->created_at }}</dd>
						</dl>

						<hr>

						<dl class="row">
							<dt class="col-sm-4">Receiving</dt>
							<dd class="col-sm-8">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="daily-reminder" @if($user->reminded_daily) checked @endif disabled>
									<label class="custom-control-label" for="daily-reminder">daily reminders</label>
								</div>
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="weekly-reminder" @if($user->reminded_weekly) checked @endif disabled>
									<label class="custom-control-label" for="weekly-reminder">weekly reminders</label>
								</div>
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="monthly-reminder" @if($user->reminded_monthly) checked @endif disabled>
									<label class="custom-control-label" for="monthly-reminder">monthly reminders</label>
								</div>
							</dd>
						</dl>

						<a href="{{ route('users.edit', ['user' => Auth::id()]) }}" class="btn btn-warning" role="button">
							Account settings & preferences
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection