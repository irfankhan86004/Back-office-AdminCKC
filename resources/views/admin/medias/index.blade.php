@extends('admin.layouts.app')

@section('title')
Media
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-file-o"></i>Media<br>
            <small>Listing</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li>Media</li>
</ul>

@include('admin.partials._mediaslisting')
@include('admin.partials._uploadmedias')
@stop
