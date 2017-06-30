@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Register</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="/auth/register">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">Choose a User Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name" value="{{ old('name') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">E-Mail Address</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">UK Six Figure Flock Number</label>
							<div class="col-md-6">
								<input type="integer" class="form-control" name="flock" value="{{ old('flock') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Business Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="business" value="{{ old('business') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Farm Address</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="address" value="{{ old('address') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Holding Number (CPH)</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="holding" value="{{ old('holding') }}">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-4 control-label">Dead Animal Disposal Route</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="disposal" value="{{ old('disposal') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Confirm Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Register
								</button>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="opacity:0.6">Already registered?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="login"
								   class="btn btn-default ">
								   Log in
								</a>
								</span>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
