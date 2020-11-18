@extends('admin.layouts.app')

@section('title')
Modifier un code promo
@stop

@section('content')

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-euro-sign"></i>{{ $promoCode->name }}<small>#{{ $promoCode->id }}</small><br>
            <small>Modifier un code promo</small>
        </h1>
    </div>
</div>

<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('promo-codes.index') }}">Codes promo</a></li>
    <li>{{ $promoCode->name }}</li>
    <li>Modifier un code promo</li>
</ul>

@include('errors.list')

<div class="block">

    <div class="block-title">
        <h2>{{ $promoCode->name }}</h2>
    </div>

    {!! Form::model($promoCode, ['route' => ['promo-codes.update', $promoCode->id], 'method' => 'PATCH', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

    	@include('admin.promo-codes._form', ['submitButtonText' => 'Mettre à jour'])

    {!! Form::close() !!}
</div>

@stop
