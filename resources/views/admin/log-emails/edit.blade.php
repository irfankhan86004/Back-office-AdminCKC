@extends('admin.layouts.app')

@section('title')
Voir un email
@stop

@section('content')

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="as fa-search"></i><small>Voir un email</small><br>
        </h1>
    </div>
</div>

<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('emails.index') }}">Emails</a></li>
    <li>Voir un email</li>
</ul>

@include('errors.list')

<div class="block">

    <div class="block-title">
        <h2>Voir un email</h2>
    </div>

    {!! Form::model($email, ['route' => ['emails.update', $email->id], 'method' => 'PATCH', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

        @include('admin.log-emails._form', ['submitButtonText' => 'Mettre à jour'])

    {!! Form::close() !!}
</div>

@stop
