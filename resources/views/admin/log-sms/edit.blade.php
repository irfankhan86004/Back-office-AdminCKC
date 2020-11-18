@extends('admin.layouts.app')

@section('title')
Voir un sms
@stop

@section('content')

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fas fa-search"></i><small>Voir un sms</small><br>
        </h1>
    </div>
</div>

<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('sms.index') }}">SMS</a></li>
    <li>Voir un sms</li>
</ul>

@include('errors.list')

<div class="block">

    <div class="block-title">
        <h2>Voir un sms</h2>
    </div>

    {!! Form::model($sms, ['route' => ['emails.update', $sms->id], 'method' => 'PATCH', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

        @include('admin.log-sms._form', ['submitButtonText' => 'Mettre à jour'])

    {!! Form::close() !!}
</div>

@stop
