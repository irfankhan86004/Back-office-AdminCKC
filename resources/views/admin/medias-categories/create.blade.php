@extends('admin.layouts.app')

@section('title')
Ajouter une catégorie de médias
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-folder-open-o"></i>Catégorie de medias<br>
            <small>Ajouter une catégorie de médias</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('categories-medias.index') }}">Catégorie de medias</a></li>
    <li>Ajouter</li>
</ul>

@include('errors.list')

<!-- Example Block -->
<div class="block">
    <!-- Example Title -->
    <div class="block-title">
        <h2>Ajouter une catégorie de medias</h2>
    </div>
    <!-- END Example Title -->

    {!! Form::open(['route' => ['categories-medias.store'], 'method' => 'POST', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

        @include('admin.medias-categories._form', ['submitButtonText' => 'Ajouter'])

    {!! Form::close() !!}
</div>
<!-- END Example Block -->
@stop
