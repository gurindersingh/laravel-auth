@extends('gauth::master')

@section('gauth::content')
	
	<div class="container pb-10">
		
		<div class="row">
			
			<div class="col-lg-12 d-f jc-c pt-5">
				
				<div class="mx-auto" style="width: 500px; max-width: 90%">
					
					<!-- Title -->
					<div class="">
						<h1 class="text-center text-uppercase h4">Choose New Password</h1>
					</div>
					
					<hr>
					
					<div class="">
						
						@include('gauth::_errors')
						
						<form method="POST" action="{{ route('socialite.register') }}">
							{{ csrf_field() }}
							
							<fieldset class="w-100">
								
								<input type="hidden" name="email" value="{{ $email }}">
								<input type="hidden" name="data" value="{{ $data }}">
								
								@foreach($nameFields as $nameItem)
									<input type="hidden" name="{{ $nameItem }}" value="{{ ${$nameItem} }}">
								@endforeach
								
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
									<button type="submit" class="btn btn-primary ">Set Password</button>
									<div class="">
										<a href="{{ route('password.request') }}"
										   class="mr-3">Forget Password</a>
										<a href="{{ route('login') }}"
										   class="">Login</a>
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