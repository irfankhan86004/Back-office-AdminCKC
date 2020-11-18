@extends('admin.layouts.app')

@section('title')
Ajouter un tag
@stop

@section('content')

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-tag"></i>Tags<br>
            <small>Ajouter un tag</small>
        </h1>
    </div>
</div>

<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('tags.index') }}">tags</a></li>
    <li>Ajouter un tag</li>
</ul>

@include('errors.list')

<div class="block">

    <div class="block-title">
        <h2>Ajouter un tag</h2>
    </div>

    {!! Form::open(['route' => ['tags.store'], 'method' => 'POST', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

        @include('admin.tags._form', ['submitButtonText' => 'Créer'])

    {!! Form::close() !!}
</div>

@stop
