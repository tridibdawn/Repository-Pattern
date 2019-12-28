@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
		<form method="POST" action="{{url('/')}}/users">
			@csrf
			<div>
				<input type="text" name="name" required placeholder="Full Name"><br>
				<input type="email" name="email" required placeholder="User Email"><br>
				<input type="password" name="password" required placeholder="Password"><br>
				<input type="password" name="password_confirmation" required placeholder="Re-Type Password"><br>
				<input type="submit" value="Create">
			</div>
		</form>
    </div>
</div>
@endsection