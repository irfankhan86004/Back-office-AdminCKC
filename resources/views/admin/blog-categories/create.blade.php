@extends('admin.layouts.app')

@section('title')
Ajouter une catégorie
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-rss"></i>Catégories du blog<br>
            <small>Ajouter une catégorie</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('categories-blog.index') }}">Catégories du blog</a></li>
    <li>Ajouter</li>
</ul>

@include('errors.list')

<!-- Example Block -->
<div class="block">
    <!-- Example Title -->
    <div class="block-title">
        <h2>Catégorie du blog</h2>
    </div>
    <!-- END Example Title -->

    {!! Form::open(['route' => ['categories-blog.store'], 'method' => 'POST', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}
    
    	@include('admin.blog-categories._form', ['submitButtonText' => 'Ajouter'])
    
    {!! Form::close() !!}
</div>
<!-- END Example Block -->
@stop