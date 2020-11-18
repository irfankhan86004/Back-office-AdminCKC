@extends('admin.layouts.app')

@section('title')
Modifier un administrateur
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-lock"></i>{{ $admin->last_name.' '.$admin->first_name }} <small>#{{ $admin->id }}</small><br>
            <small>Modifier un administrateur</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('admins.index') }}">Administrateurs</a></li>
    <li>{{ $admin->last_name.' '.$admin->first_name }}</li>
    <li>Modifier un administrateur</li>
</ul>

@include('errors.list')

<!-- Example Block -->
<div class="block">
    <!-- Example Title -->
    <div class="block-title">
        <h2>{{ $admin->last_name.' '.$admin->first_name }}</h2>
    </div>
    <!-- END Example Title -->

    {!! Form::model($admin, ['route' => ['admins.update', $admin->id], 'method' => 'PATCH', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

        @include('admin.admins._form', ['submitButtonText' => 'Mettre à jour'])

    {!! Form::close() !!}
</div>
<!-- END Example Block -->
@stop
