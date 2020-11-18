@extends('admin.layouts.app')

@section('title')
Voir une demande de contact
@stop

@section('content')

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="as fa-envelope"></i><small>Voir une demande de contact</small><br>
        </h1>
    </div>
</div>

<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('contact-requests.index') }}">Demande de contact</a></li>
    <li>Voir une demande de contact</li>
</ul>

@include('errors.list')

<div class="block">

    <div class="block-title">
        <h2>Voir une demande de contact</h2>
    </div>

    {!! Form::model($contact_request, ['route' => ['contact-requests.update', $contact_request->id], 'method' => 'PATCH', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

        @include('admin.contact-requests._form', ['submitButtonText' => 'Mettre à jour'])

    {!! Form::close() !!}
</div>

@stop
