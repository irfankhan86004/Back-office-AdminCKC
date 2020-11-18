@extends('admin.layouts.app')

@section('title')
Ajouter un code promo
@stop

@section('content')

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-euro-sign"></i>Codes promo<br>
            <small>Ajouter un code promo</small>
        </h1>
    </div>
</div>

<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('promo-codes.index') }}">Codes promo</a></li>
    <li>Ajouter un code promo</li>
</ul>

@include('errors.list')

<div class="block">

    <div class="block-title">
        <h2>Ajouter un code promo</h2>
    </div>


    {!! Form::open(['route' => ['promo-codes.store'], 'method' => 'POST', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

    	@include('admin.promo-codes._form', ['submitButtonText' => 'Créer'])

    {!! Form::close() !!}
</div>

@stop
