@extends('gauth::master')

@section('gauth::content')
	
	<div class="container pb-10">
		
		<div class="row">
			
			<div class="col-lg-12 pt-5">
				
				<div class="mx-auto" style="width: 500px; max-width: 90%">
					
					<!-- Title -->
					<div class="">
						<h1 class="text-center text-uppercase h4">Change Password</h1>
					</div>
					<hr>
					
					<div class="">
						
						@include('gauth::_errors')
						
						<form method="POST" action="{{ route('password.request') }}">
							{{ csrf_field() }}
							
							<fieldset class="w-100">
								
								<input type="hidden" name="token" value="{{ $token }}">
								
								<div class="form-group">
									<label for="email" class="label">Email</label>
									<input type="email"
									       name="email"
									       class="form-control input"
									       value="{{ old('email') }}"
									       id="email"
									       placeholder="Email...">
								</div>
								
								<div class="form-group">
									<label for="password" class="label">New Password</label>
									<input type="password"
									       name="password"
									       class="form-control input"
									       value=""
									       id="password"
									       placeholder="New Password...">
								</div>
								
								<div class="form-group">
									<label for="password_confirmation" class="label">Confirm New Password</label>
									<input type="password"
									       name="password_confirmation"
									       class="form-control input"
									       value=""
									       id="password_confirmation"
									       placeholder="Confirm New Password...">
								</div>
								
								<div class="d-flex align-items-center justify-content-between">
									<button type="submit" class="btn btn-primary">
										Change Password
									</button>
									
									<div class="">
										<div class="d-flex">
											<a href="{{ route('login') }}" class="mr-3">Login</a>
											<a href="{{ route('register') }}" class="">Sign up</a>
										</div>
									</div>
								
								</div>
							
							</fieldset>
						
						</form>
					
					</div>
				
				</div>
			
			</div>
		
		</div>
	
	</div>

@endsection