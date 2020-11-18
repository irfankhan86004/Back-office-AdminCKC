@extends('admin.layouts.app')

@section('title')
Ajouter un administrateur
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-lock"></i>Administrateurs<br>
            <small>Ajouter un administrateur</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('admins.index') }}">Administrateurs</a></li>
    <li>Ajouter un administrateur</li>
</ul>

@include('errors.list')

<!-- Example Block -->
<div class="block">
    <!-- Example Title -->
    <div class="block-title">
        <h2>Ajouter un administrateur</h2>
    </div>
    <!-- END Example Title -->

    {!! Form::open(['route' => ['admins.store'], 'method' => 'POST', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

        @include('admin.admins._form', ['submitButtonText' => 'Créer'])

    {!! Form::close() !!}
</div>
<!-- END Example Block -->
@stop
