@extends('admin.layouts.app')

@section('title')
Modifier un utilisateur
@stop

@section('content')

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-user"></i>{{ $user->fullName() }} <small>#{{ $user->id }}</small><br>
            <small>Modifier un utilisateur</small>
        </h1>
    </div>
</div>
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('users.index') }}">Utilisateurs</a></li>
    <li>{{ $user->fullName() }}</li>
    <li>Modifier un utilisateur</li>
</ul>

@include('errors.list')

<div class="block">
    <div class="block-title">
        <h2>{{ $user->title }}</h2>
    </div>

    {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'PATCH', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

        @include('admin.users._form', ['submitButtonText' => 'Mettre à jour'])

    {!! Form::close() !!}
</div>

@stop
