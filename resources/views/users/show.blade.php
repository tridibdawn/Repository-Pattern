@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
		Name: {{$user->name}}<br>
		Email: {{$user->email}}<br>
		<a href="{{url('/')}}/users">Back</a>
    </div>
</div>
@endsection