@extends('admin.layouts.app')

@section('title')
Modifier un menu du site
@stop

@section('content')

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-font"></i>{{ $menu->getAttr('name') }} <small>#{{ $menu->id }}</small><br>
            <small>Modifier un menu du site</small>
        </h1>
    </div>
</div>
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('menu.index') }}">Menu du site</a></li>
    <li>{{ $menu->getAttr('name') }}</li>
    <li>Modifier un menu du site</li>
</ul>

@include('errors.list')

<div class="block">
    <div class="block-title">
        <h2>{{ $menu->getAttr('name') }}</h2>
    </div>

    {!! Form::model($menu_arr, ['route' => ['menu.update', $menu->id], 'method' => 'PATCH', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

    	@include('admin.menu._form', ['submitButtonText' => 'Mettre à jour'])

    {!! Form::close() !!}
</div>

@if(Auth::guard('admin')->user()->hasPermission(3))
    <div class="alert alert-info text-center">
        <i class="fa fa-info-circle"></i>&nbsp;&nbsp;Taille d'image recommandée pour l'optimisation du chargement de la page :  <br> <strong>300px x 200px</strong>
    </div>
    @include('admin.partials._uploadmedias')
@endif

@stop
