@extends('admin.layouts.app')

@section('title')
Modifier fichier du site
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-files-o"></i>Fichier du site <small>#{{ $file->id }}</small><br>
            <small>Modifier un fichier</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('files.index') }}">Fichiers du site</a></li>
    <li>#{{ $file->id }}</li>
    <li>Modifier un fichier</li>
</ul>

@include('errors.list')

<div class="row">
    <div class="col-md-6 col-lg-5 text-center">
	    <div class="block">
		    <div class="block-title">
		        <h2>Fichier #<strong>{{ $file->id }}</strong></h2>
		    </div>
	        <div class="block-section">
		        <table class="table table-striped table-borderless table-vcenter">
		            <tbody>
		                <tr>
		                    <td class="text-right" style="width:50%;">
		                        <strong>ID</strong>
		                    </td>
		                    <td class="text-left">{{ $file->id }}</td>
		                </tr>
		                <tr>
		                    <td class="text-right">
		                        <strong>Nom</strong>
		                    </td>
		                    <td class="text-left">{{ $file->name }}</td>
		                </tr>
		                <tr>
		                    <td class="text-right">
		                        <strong>Nom d'origine</strong>
		                    </td>
		                    <td class="text-left">{{ $file->name_origine }}</td>
		                </tr>
		                <tr>
		                    <td class="text-right">
		                        <strong>Poids</strong>
		                    </td>
		                    <td class="text-left">{{ octet_convert($file->poids) }}</td>
		                </tr>
		                <tr>
		                    <td class="text-right">
		                        <strong>Provenance</strong>
		                    </td>
		                    <td class="text-left">{!! str_replace('App\Models\\', '', $file->file_type) .' #<strong>'. $file->file_id .'</strong>' !!}</td>
		                </tr>
		                <tr>
		                    <td class="text-right">
		                        <strong>Lien</strong>
		                    </td>
		                    <td class="text-left"><a href="{{ asset('/uploads/files/'.$file->name) }}" target="_blank">Visualiser <i class="fa fa-eye"></i></a></td>
		                </tr>
		            </tbody>
		        </table>
	            <a href="{{ asset('/uploads/files/'.$file->name) }}" target="_blank" style="padding:8px; display:inline-block;"><i class="fi fi-{{ $file->extension }} fa-5x text-primary"></i></a>
	        </div>
	    </div>
    </div>
    <div class="col-md-6 col-lg-7">
	    <div class="block">
		    <div class="block-title">
		        <h2>Fichier : <strong>{{ $file->name }}</strong></h2>
		    </div>
	        {!! Form::model($file_arr, ['route' => ['files.update', $file->id], 'method' => 'PATCH', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

		    	@include('admin.files._form', ['submitButtonText' => 'Mettre à jour'])

		    {!! Form::close() !!}
	    </div>
    </div>
</div>
@stop
