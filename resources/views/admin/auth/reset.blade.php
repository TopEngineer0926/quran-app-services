@extends('admin.auth.includes.app-main')
@section('content')
<!-- start: REGISTER BOX -->
<div class="box-register">
                @error('email')
                <div class="alert alert-danger">
                    <i class="fa fa-remove-sign"></i> {{$message}}
                </div>
                @enderror
                @error('password')
                <div class="alert alert-danger">
                    <i class="fa fa-remove-sign"></i> {{$message}}
                </div>
                @enderror
				<h3>Reset Password</h3>
				<p>
					Enter your personal details below:
				</p>
				<form class="form-register" method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $_GET['token'] }}">

					<div class="errorHandler alert alert-danger no-display">
						<i class="fa fa-remove-sign"></i> You have some form errors. Please check below.
					</div>
					<fieldset>

						<div class="form-group">
							<span class="input-icon">
								<input type="email" class="form-control" name="email" placeholder="Email"  value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
								<i class="fa fa-envelope"></i> </span>
						</div>
						<div class="form-group">
							<span class="input-icon">
								<input type="password" class="form-control" id="password" name="password" placeholder="Password">
								<i class="fa fa-lock"></i> </span>
						</div>
						<div class="form-group">
							<span class="input-icon">
								<input type="password" class="form-control" name="password_confirmation" placeholder="Repeat Password">
								<i class="fa fa-lock"></i> </span>
						</div>
						<div class="form-actions">
							<a class="btn btn-light-grey go-back" href = "{{route('admin.login')}}">
								<i class="fa fa-arrow-circle-left"></i> Login
							</a>
							<button type="submit" class="btn btn-bricky pull-right">
								Submit <i class="fa fa-arrow-circle-right"></i>
							</button>
						</div>
					</fieldset>
				</form>
			</div>
			<!-- end: REGISTER BOX -->
@endsection
