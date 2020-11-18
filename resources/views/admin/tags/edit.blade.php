@extends('admin.layouts.app')

@section('title')
Modifier un tag
@stop

@section('content')

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-tag"></i>{{ $tag->title }} <small>#{{ $tag->id }}</small><br>
            <small>Modifier un tag</small>
        </h1>
    </div>
</div>
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('tags.index') }}">Tags</a></li>
    <li>{{ $tag->title }}</li>
    <li>Modifier un tag</li>
</ul>

@include('errors.list')

<div class="block">
    <div class="block-title">
        <h2>{{ $tag->title }}</h2>
    </div>

    {!! Form::model($tag, ['route' => ['tags.update', $tag->id], 'method' => 'PATCH', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

        @include('admin.tags._form', ['submitButtonText' => 'Mettre à jour'])

    {!! Form::close() !!}
</div>

@stop
