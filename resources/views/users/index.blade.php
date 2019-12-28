@extends('layouts.app')

@section('content')
<div class="container">
	<b>Total Users:</b> {{$user_counts}}
    <div class="row">
    	<div>
    		<a href="{{url('/')}}/users/create">Create User</a>
    	</div>
	<ol>
		@forelse($users as $user)
			<li style="display: block;">
				{{ $user->name }}
				<a href="{{url('/')}}/users/{{$user->id}}">View</a>
				<a href="{{url('/')}}/users/edit/{{$user->id}}">Edit</a>
				<form action="{{url('/')}}/users/{{$user->id}}" method="POST">
					@csrf
					@method('delete')
				<input type="submit" value="Delete">
				</form>
			</li>
			<br>
		@empty

		@endforelse
	</ol>
	</div>
</div>
@endsection