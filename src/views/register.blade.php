@extends('gauth::master')

@section('gauth::content')
	
	<div class="container pb-10">
		
		<div class="row">
			
			<div class="col-lg-12 d-f jc-c pt-5">
				
				<div class="mx-auto" style="width: 500px; max-width: 90%">
					
					<!-- Title -->
					<div class="">
						<h1 class="text-center text-uppercase h4">Register</h1>
					</div>
					
					<hr>
					
					<div class="">
						
						@include('gauth::_errors')
						
						<form action="{{ route('register') }}" method="POST">
							{{ csrf_field() }}
							
							<fieldset class="w-100">
								
								@foreach($nameFields as $nameItem)
									<div class="form-group">
										<label for="{{ $nameItem }}"
										       class="label">
											{{ title_case(str_replace('_', ' ', $nameItem)) }}
										</label>
										<input type="text"
										       name="{{ $nameItem }}"
										       class="form-control input"
										       id="{{ $nameItem }}"
										       value="{{ old($nameItem) }}"
										       placeholder="{{ title_case(str_replace('_', ' ', $nameItem)) }}...">
									</div>
								@endforeach
								
								<div class="form-group">
									<label for="email" class="label">Email</label>
									<input type="email"
									       name="email"
									       class="form-control input"
									       id="email"
									       value="{{ old('email') }}"
									       placeholder="Email...">
								</div>
								
								<div class="form-group">
									<label for="password" class="">Password</label>
									<input type="password"
									       name="password"
									       class="form-control input"
									       id="password"
									       placeholder="Password...">
								</div>
								
								<div class="form-group">
									<label for="password_confirmation" class="">Confirm Password</label>
									<input type="password"
									       name="password_confirmation"
									       class="form-control input"
									       id="password_confirmation"
									       placeholder="Confirm password...">
								</div>
								
								<div class="d-flex justify-content-between align-content-center">
									<button type="submit" class="btn btn-primary ">Signup</button>
									<div class="">
										<a href="{{ route('password.request') }}"
										   class="mr-3">Forget Password</a>
										<a href="{{ route('login') }}"
										   class="">Login</a>
									</div>
								</div>
								
								@if(config('gauth.social_providers.enable'))
								
									<hr>
									
									<div class="">
										<p class="text-center">Sign up using following</p>
										
										@include('gauth::social-links')
										
									</div>
									
								@endif
							
							</fieldset>
						</form>
					
					</div>
				
				</div>
			
			</div>
		
		</div>
	
	</div>

@endsection