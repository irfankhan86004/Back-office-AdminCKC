@extends('emails.layouts.master')

@section('content')
	<p>Hello {{ $data['name'] }}</p>
@endsection