@extends('admin.layouts.app')

@section('title')
Ajouter une redirection
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-map-signs"></i>Redirections<br>
            <small>Ajouter une redirection</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('redirects.index') }}">Redirections</a></li>
    <li>Ajouter une redirection</li>
</ul>

@include('errors.list')

<!-- Example Block -->
<div class="block">
    <!-- Example Title -->
    <div class="block-title">
        <h2>Ajouter une redirection</h2>
    </div>
    <!-- END Example Title -->

    {!! Form::open(['route' => ['redirects.store'], 'method' => 'POST', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}
    
    	@include('admin.redirects._form', ['submitButtonText' => 'Ajouter'])
    
    {!! Form::close() !!}
</div>
<!-- END Example Block -->
@stop