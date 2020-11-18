@extends('admin.layouts.app')

@section('title')
Modifier une catégorie
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-rss"></i>{{ $category->getAttr('name') }} <small>#{{ $category->id }}</small><br>
            <small>Modifier une categorie</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('categories-blog.index') }}">Catégories du blog</a></li>
    <li>{{ $category->getAttr('name') }}</li>
    <li>Modifier une catégorie</li>
</ul>

@include('errors.list')

<!-- Example Block -->
<div class="block">
    <!-- Example Title -->
    <div class="block-title">
        <h2>{{ $category->getAttr('name') }}</h2>
    </div>
    <!-- END Example Title -->

    {!! Form::model($category_arr, ['route' => ['categories-blog.update', $category->id], 'method' => 'PATCH', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}
    
    	@include('admin.blog-categories._form', ['submitButtonText' => 'Mettre à jour'])
    
    {!! Form::close() !!}
</div>
<!-- END Example Block -->
@stop