@extends('gauth::master')

@section('gauth::content')
	
	<div class="container pb-10">
		
		<div class="row">
			
			<div class="col-lg-12 pt-5">
				
				<div class="mx-auto" style="width: 500px; max-width: 90%">
					
					<!-- Title -->
					<div class="">
						<h1 class="text-center text-uppercase h4">Login</h1>
					</div>
					
					<hr>
					
					@php(
						$action =   request()->exists('sentToUrl') ?
									route('login', ['sentToUrl' => request()->get('sentToUrl')] ) :
									route('login')
					)
					
					<div class="">
						
						@include('gauth::_errors')
						
						<form action="{{ $action }}" method="POST">
							{{ csrf_field() }}
							
							<fieldset class="w-100">
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
									<label for="password" class="">Password</label>
									<input type="password"
									       name="password"
									       class="form-control input"
									       id="password"
									       value=""
									       placeholder="Password...">
								</div>
								
								<div class="form-group mt-4">
									<label for="remember" class="">
										<input type="checkbox"
										       name="remember"
										       {{ old('remember') ? 'checked' : '' }}
										       id="remember">
										<span>Remember me</span>
									</label>
								</div>
								
								<div class="d-flex align-items-center justify-content-between">
									<button type="submit"
									        class="btn btn-primary">Login
									</button>
									
									<div class="">
									
										<a href="{{ route('password.request') }}"
										   class="">Forget Password</a>

										@registerationOpen()
										<a href="{{ route('register') }}"
										   class="ml-3">Sign up</a>
										@endRegisterationOpen
										
									</div>
								
								</div>
								
								@registerationOpen()
									@if(config('gauth.social_providers.enable'))
										<hr>
										
										<div class="">
											<p class="text-center">Login using following</p>
											
											@include('gauth::social-links')
											
										</div>
									@endif
								@endRegisterationOpen
							
							</fieldset>
						
						</form>
					
					</div>
				
				</div>
			
			</div>
		
		</div>
	
	</div>

@endsection