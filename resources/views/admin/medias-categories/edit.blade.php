@extends('admin.layouts.app')

@section('title')
Modifier une catégorie de medias
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-folder-open-o"></i>{{ $category->name }} <small>#{{ $category->id }}</small><br>
            <small>Modifier une catégorie de medias</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('categories-medias.index') }}">Catégorie de medias</a></li>
    <li>{{ $category->name }}</li>
    <li>Modifier</li>
</ul>

@include('errors.list')

<!-- Example Block -->
<div class="block">
    <!-- Example Title -->
    <div class="block-title">
        <h2>{{ $category->name }}</h2>
    </div>
    <!-- END Example Title -->

    {!! Form::model($category, ['route' => ['categories-medias.update', $category->id], 'method' => 'PATCH', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

        @include('admin.medias-categories._form', ['submitButtonText' => 'Mettre à jour'])

    {!! Form::close() !!}
</div>
<!-- END Example Block -->
@stop
