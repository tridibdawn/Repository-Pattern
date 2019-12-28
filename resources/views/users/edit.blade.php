@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
		<form method="POST" action="{{url('/')}}/users/{{$user->id}}">
			@csrf
			@method('put')
			<div>
				<input type="text" name="name" required placeholder="Full Name" value="{{$user->name}}"><br>
				<input type="email" name="email" required placeholder="User Email" value="{{$user->email}}"><br>
				<input type="submit" value="Update">
			</div>
		</form>
    </div>
</div>
@endsection