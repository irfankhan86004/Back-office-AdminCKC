@extends('admin.layouts.app')

@section('title')
Modifier carousel du site
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-picture"></i>Carousel du site <small>#{{ $carousel->id }}</small><br>
            <small>Modifier un carousel</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('carousel.index') }}">Carousel du site</a></li>
    <li>Carousel #{{ $carousel->id }}</li>
    <li>Modifier un carousel</li>
</ul>

@include('errors.list')

<!-- Example Block -->
<div class="block">
    <!-- Example Title -->
    <div class="block-title">
        <h2>Carousel #{{ $carousel->id }}</h2>
    </div>
    <!-- END Example Title -->

    {!! Form::model($carousel_arr, ['route' => ['carousel.update', $carousel->id], 'method' => 'PATCH', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

    	@include('admin.carousels._form', ['submitButtonText' => 'Mettre à jour'])

    {!! Form::close() !!}
</div>

@if(Auth::guard('admin')->user()->hasPermission(6))
    @include('admin.partials._uploadmedias')
@endif

@stop
