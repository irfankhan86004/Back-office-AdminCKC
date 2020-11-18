@extends('admin.layouts.app')

@section('title')
Ajouter un utilisateur
@stop

@section('content')

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-user"></i>Utilisateurs<br>
            <small>Ajouter un utilisateur</small>
        </h1>
    </div>
</div>

<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('users.index') }}">Utilisateurs</a></li>
    <li>Ajouter un utilisateur</li>
</ul>

@include('errors.list')

<div class="block">

    <div class="block-title">
        <h2>Ajouter un utilisateur</h2>
    </div>

    {!! Form::open(['route' => ['users.store'], 'method' => 'POST', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

        @include('admin.users._form', ['submitButtonText' => 'Créer'])

    {!! Form::close() !!}
</div>

@stop
