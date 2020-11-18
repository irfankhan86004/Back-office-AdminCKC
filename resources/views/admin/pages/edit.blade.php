@extends('admin.layouts.app')

@section('title')
Modifier une page du site
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-font"></i>{{ $page->getAttr('name') }} <small>#{{ $page->id }}</small><br>
            <small>Modifier une page du site</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('pages.index') }}">Pages du site</a></li>
    <li>{{ $page->getAttr('name') }}</li>
    <li>Modifier une page du site</li>
</ul>

@include('errors.list')

<!-- Example Block -->
<div class="block">
    <!-- Example Title -->
    <div class="block-title">
        <h2>{{ $page->getAttr('name') }}</h2>
    </div>
    <!-- END Example Title -->

    {!! Form::model($page_arr, ['route' => ['pages.update', $page->id], 'method' => 'PATCH', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

    	@include('admin.pages._form', ['submitButtonText' => 'Mettre à jour'])

    {!! Form::close() !!}
</div>
<!-- END Example Block -->

@if(Auth::guard('admin')->user()->hasPermission(6))
@include('admin.partials._uploadmedias')
@endif

@if(isset($_GET['only_medias_block']))
@push('scripts')
<script>
	$(document).ready(function(){
		$('#medias-block').siblings().hide();
		$('#medias-block').css('marginBottom', 0);
		$('#sidebar, header').hide();
		$('#main-container').css('margin', 0);
		$('#page-content').css({'padding': 0, 'backgroundColor': '#fff'});
		$('#page-container').css('backgroundColor', '#fff');
		$('body').css('background', 'transparent');
	})
</script>
@endpush
@endif

@stop
