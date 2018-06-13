@extends('gauth::master')

@section('gauth::content')
	
	<div class="container pb-10">
		
		<div class="row">
			
			<div class="col-lg-12 pt-5">
				
				<div class="mx-auto" style="width: 500px; max-width: 90%">
					
					<!-- Title -->
					<div class="">
						<h1 class="text-center text-uppercase h4">Confirm Your Email Or Resend Email Confirmation</h1>
					</div>
					
					<hr>
					
					@if(session('status'))
						<div class="alert alert-success" role="alert">{{ session('status') }}</div>
					@endif
					
					<div class="">
						
						@include('gauth::_errors')
						
						<form method="POST" action="{{ route('email.confirmation.create') }}">
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
								
								<div class="d-flex align-items-center justify-content-between">
									<button type="submit" class="btn btn-primary">
										Resend Confirmation
									</button>
									
									<div class="">
										<div class="d-flex">
											<a href="{{ route('login') }}" class="mr-3">Login</a>
											<span class="text-white fsz-sm tt-u ls-12 fw-600 mr-3">|</span>
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