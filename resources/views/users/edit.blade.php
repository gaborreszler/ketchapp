@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">Account settings & preferences</div>

					<div class="card-body">
						@if ($errors->any())
							<div class="alert alert-danger">
								<ul>
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						@endif

						<form method="POST" action="{{ route('users.update', ['user' => $user->id]) }}">
							@method('PUT')
							@csrf
							{{--<div class="form-group">
								<div class="form-row">
									<label class="col-md-8" for="preferred-timezone">
										{{ ucfirst(__("preferred timezone")) }}
										<small id="timezoneHelp" class="form-text text-muted">{{ ucfirst(__("placeholder")) }}</small>
									</label>
									<select name="timezone" id="preferred-timezone" class="form-control col-md-4" aria-describedby="timezoneHelp">
										@foreach (timezone_identifiers_list() as $timezone)
											<option value="{{ $timezone }}"{{ $timezone == $user->timezone ? ' selected' : '' }}>{{ str_replace("_", " ", $timezone) }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<hr>--}}

							<div class="form-group">
								<div class="form-row">
									<div class="col-form-label col-md-8 pt-0">
										Receive e-mails about aired TV shows that you haven't seen yet
										<small class="form-text text-muted">Placeholder</small>
									</div>
									<div class="col-md-4">
										<div class="custom-control custom-switch">
											<input type="hidden" name="reminded_daily" value="0">
											<input type="checkbox" name="reminded_daily" id="daily-reminder" class="custom-control-input" value="1" @if ($user->reminded_daily) checked @endif>
											<label class="custom-control-label" for="daily-reminder">daily reminders</label>
										</div>
										<div class="custom-control custom-switch">
											<input type="hidden" name="reminded_weekly" value="0">
											<input type="checkbox" name="reminded_weekly" id="weekly-reminder" class="custom-control-input" value="1" @if ($user->reminded_weekly) checked @endif>
											<label class="custom-control-label" for="weekly-reminder">weekly reminders</label>
										</div>
										<div class="custom-control custom-switch">
											<input type="hidden" name="reminded_monthly" value="0">
											<input type="checkbox" name="reminded_monthly" id="monthly-reminder" class="custom-control-input" value="1" @if ($user->reminded_monthly) checked @endif>
											<label class="custom-control-label" for="monthly-reminder">monthly reminders</label>
										</div>
									</div>
								</div>
							</div>

							<button type="submit" class="btn btn-primary float-right">Save</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection