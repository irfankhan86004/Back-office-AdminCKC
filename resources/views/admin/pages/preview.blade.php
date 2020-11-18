@extends('layouts.app')

@section('title', $title)
@section('description', $description)

@section('content')
	{!! displayContent($html) !!}
@endsection