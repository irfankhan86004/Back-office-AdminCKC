@extends('admin.layouts.app')

@section('title')
Modifier une redirection
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-map-signs"></i><small>#{{ $redirect->id }}</small><br>
            <small>Modifier une redirection</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('redirects.index') }}">Redirections</a></li>
    <li>{{ $redirect->id }}</li>
    <li>Modifier une redirection</li>
</ul>

@include('errors.list')

<!-- Example Block -->
<div class="block">
    <!-- Example Title -->
    <div class="block-title">
        <h2>#{{ $redirect->id }}</h2>
    </div>
    <!-- END Example Title -->
    {!! Form::model($redirect, ['route' => ['redirects.update', $redirect->id], 'method' => 'PATCH', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}
    
    	@include('admin.redirects._form', ['submitButtonText' => 'Mettre à jour'])
    
    {!! Form::close() !!}
</div>
<!-- END Example Block -->
@stop