@extends('admin.layouts.app')

@section('title')
Ajouter une page
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-font"></i>Pages du site<br>
            <small>Ajouter une page</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('pages.index') }}">Pages du site</a></li>
    <li>Ajouter</li>
</ul>

@include('errors.list')

<!-- Example Block -->
<div class="block">
    <!-- Example Title -->
    <div class="block-title">
        <h2>Nouvelle page</h2>
    </div>
    <!-- END Example Title -->

    {!! Form::open(['route' => ['pages.store'], 'method' => 'POST', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered create-page-form']) !!}
    
    	@include('admin.pages._form', ['submitButtonText' => 'Ajouter'])
    
    {!! Form::close() !!}
</div>
<!-- END Example Block -->
@stop