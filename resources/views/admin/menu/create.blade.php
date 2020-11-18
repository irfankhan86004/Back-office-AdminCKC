@extends('admin.layouts.app')

@section('title')
Ajouter un menu
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-font"></i>Menu du site<br>
            <small>Ajouter un menu au site</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('menu.index') }}">Menu du site</a></li>
    <li>Ajouter</li>
</ul>

@include('errors.list')

<!-- Example Block -->
<div class="block">
    <!-- Example Title -->
    <div class="block-title">
        <h2>Menu du site</h2>
    </div>
    <!-- END Example Title -->

    {!! Form::open(['route' => ['menu.store'], 'method' => 'POST', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}
    
    	@include('admin.menu._form', ['submitButtonText' => 'Ajouter'])
    
    {!! Form::close() !!}
</div>
<!-- END Example Block -->
@stop