@extends('admin.layouts.app')

@section('title')
Nouveau slide
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-picture"></i>Carousel du site<br>
            <small>Nouveau slide</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('carousel.index') }}">Carousel du site</a></li>
    <li>Ajouter</li>
</ul>

@include('errors.list')

<!-- Example Block -->
<div class="block">
    <!-- Example Title -->
    <div class="block-title">
        <h2>Carousel du site</h2>
    </div>
    <!-- END Example Title -->

    {!! Form::open(['route' => ['carousel.store'], 'method' => 'POST', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

    	@include('admin.carousels._form', ['submitButtonText' => 'Ajouter'])

    {!! Form::close() !!}
</div>
<!-- END Example Block -->
@stop
