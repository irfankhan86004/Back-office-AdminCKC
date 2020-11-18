@extends('admin.layouts.app')

@section('title')
Editer un média du site
@stop

@push('styles')
<link rel="stylesheet" href="{{ asset('admin1/css/animate.css') }}">
@endpush

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-file-image-o"></i>Média <small>#{{ $media->id }}</small><br>
            <small>Editer un média</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('medias.index') }}">Listing de médias</a></li>
    <li>#{{ $media->id }}</li>
    <li>Editer un média</li>
</ul>

@include('errors.list')
<div class="row" id="row-picture">
    <div class="col-md-6 col-lg-5 text-center">
	    <div class="block">
		    <div class="block-title">
		        <h2>Média #<strong>{{ $media->id }}</strong></h2>
		    </div>
	        <div class="block-section">
		        <table class="table table-striped table-borderless table-vcenter">
		            <tbody>
		                <tr>
		                    <td class="text-right" style="width:50%;">
		                        <strong>ID</strong>
		                    </td>
		                    <td class="text-left">{{ $media->id }}</td>
		                </tr>
		                <tr>
		                    <td class="text-right">
		                        <strong>Nom</strong>
		                    </td>
		                    <td class="text-left">{{ $media->name }}</td>
		                </tr>
		                <tr>
		                    <td class="text-right">
		                        <strong>Nom d'origine</strong>
		                    </td>
		                    <td class="text-left">{{ $media->original_name }}</td>
		                </tr>
		                @if($media->type == 'picture')
		                <tr>
		                    <td class="text-right">
		                        <strong>Résolution</strong>
		                    </td>
		                    <td class="text-left">{{ $media->resolution }} px <a href="{{ $media_route }}" data-toggle="lightbox-image">(Image d'origine <i class="fa fa-eye"></i>)</a></td>
		                </tr>
		                @elseif($media->type == 'file' || $media->type == 'video')
		                <tr>
		                    <td class="text-right">
		                        <strong>Lien</strong>
		                    </td>
		                    <td class="text-left"><a href="{{ $media_route }}">Voir le fichier&nbsp;&nbsp;<i class="fa fa-eye"></i></a></td>
		                </tr>
							@if($media->type == 'video')
								<video id="my-video" class="vjs-matrix video-js vjs-big-play-centered" controls preload="auto" data-setup="{}">
								    <source src="{{ $media_route }}" type='video/mp4'>
								     <p class="vjs-no-js">
					                    Pour visionner cette vidéo, veuillez activer JavaScript et envisager de passer à un navigateur Web qui prend en charge la <a href="https://videojs.com/html5-video-support/" target="_blank">vidéo HTML5</a>
					                </p>
								</video>
							@endif
		                @endif
		                <tr>
		                    <td class="text-right">
		                        <strong>Poids</strong>
		                    </td>
		                    <td class="text-left">{{ octet_convert($media->size) }}</td>
		                </tr>
		                @if($media->admin)
		                <tr>
		                    <td class="text-right">
		                        <strong>Auteur</strong>
		                    </td>
		                    <td class="text-left">
			                    @if(auth()->guard('admin')->user()->hasPermission(1))
			                    <a href="{{ route('admins.edit', [$media->admin->id]) }}">{{ $media->admin->displayName() }}</a>
			                    @else
			                    {{ $media->admin->displayName() }}
			                    @endif
			                </td>
		                </tr>
		                @endif
		            </tbody>
		        </table>
		        @if($media->type == 'picture')
		        <div id="block-picture">
	            	<div class="flipD flip-n">
		            	<a href="{{ $media_route }}" data-toggle="lightbox-image">
			            	<img src="{{ $media_route }}?t={{time()}}" width="300" alt="{{ $media->alt }}"/>
			            </a>
			        </div>
	            	<div class="flipD flip-h-y">
		            	<img src="{{ $media_route }}?t={{time()}}" class="flip_h" width="300" alt="{{ $media->alt }}"/>
		            </div>
	            	<div class="flipD flip-v-y">
		            	<img src="{{ $media_route }}?t={{time()}}" class="flip_v" width="300" alt="{{ $media->alt }}" />
		            </div>
		        </div>
		        @endif
	        </div>
	    </div>
    </div>
    <div class="col-md-6 col-lg-7">
	    <div class="block">
		    <div class="block-title">
		    	<h2>Média : <strong>{{ $media->media_name }}</strong></h2>
		    </div>
	        {!! Form::model($media, ['route' => ['medias.update', $media->id], 'method' => 'PATCH', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}
				<div class="form-group{{ $errors->first('media_name') ? ' has-error' : '' }}">
				    <label class="col-md-3 control-label" for="media_name">Nom *&nbsp;&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" title="Le nom du média peut être modifié s'il n'est pas correctement formaté"></i></label>
				    <div class="col-md-9">
					    {!! Form::text('media_name', null, ['class'=>'form-control', 'id' => 'media_name']) !!}
					    {!! $errors->first('media_name', '<span class="help-block">:message</span>') !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('language', 'Language'.($media->type == 'file' ? ' *' : ''), ['class'=>'col-md-3 control-label']) !!}
				    <div class="col-md-9">
					    {!! Form::select('language', $languages, null, ['class'=>'form-control select-chosen']) !!}
					    {!! $errors->first('news_type', '<span class="help-block">:message</span>') !!}
					</div>
				</div>
				@if(auth()->guard('admin')->user()->medias_status != 'subsidiary_contributor')
				<div class="form-group">
				    <label class="col-md-3 control-label" for="status">Status&nbsp;&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" title='Les médias qui ne sont pas "public" ne seront pas visibles sur le site'></i></label>
				    <div class="col-md-9">
					    @if(auth()->guard('admin')->user()->medias_status == 'standard' || $media->status != 'published')
						{!! Form::select('status', auth()->guard('admin')->user()->availableMediaStatus(), null, ['class'=>'form-control select-chosen']) !!}
						@else
						<div style="padding-top:7px">
							{!! $media->displayAdminStatus() !!}
						</div>
						@endif
					</div>
				</div>
				@endif
				<div class="form-group">
				    <label class="col-md-3 control-label" for="public">Public&nbsp;&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" title="Les médias public seront visibles par tous les visiteurs, et les médias privés ne seront visibles que par les utilisateurs connectés"></i></label>
				    <div class="col-md-9">
						<label class="switch switch-primary">
							{{ Form::checkbox('public', 1, null) }}
							<span></span>
						</label>
					</div>
				</div>
				<div class="form-group{{ $errors->has('categories') ? ' has-error' : '' }}">
					{!! Form::label('categories[]', 'Categories *', ['class'=>'col-md-3 control-label']) !!}
				    <div class="col-md-9">
					    {!! Form::select('categories[]', $categories, null, ['class'=>'form-control select-chosen', 'multiple']) !!}
					    {!! $errors->first('categories', '<span class="help-block">:message</span>') !!}
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label" for="links[]">Médias liés&nbsp;&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" title="Ce champs permet de lier plusieurs médias identiques mais traduis dans différentes langues"></i></label>
				    <div class="col-md-9">
					    <div class="media-links">
					    	{!! Form::select('links[]', $media->linksArray(), $media->linksArray(true), ['class'=>'form-control', 'id' => 'links[]', 'multiple']) !!}
					    </div>
					</div>
				</div>
				<div class="form-group{{ $errors->has('robots') ? ' has-error' : '' }}">
					{!! Form::label('robots', 'Robots', ['class'=>'col-md-3 control-label']) !!}
				    <div class="col-md-9">
					    {!! Form::select('robots', getRobots(), null, ['class'=>'form-control select-chosen']) !!}
					    {!! $errors->first('robots', '<span class="help-block">:message</span>') !!}
					</div>
				</div>
				@if($media->types->count())
				<div class="form-group">
					{!! Form::label('relations', 'Relations', ['class'=>'col-md-3 control-label']) !!}
				    <div class="col-md-9">
					    <ul style="margin-bottom:0;margin-top:7px">
						    @foreach($media->types as $t)
						    <li>{{ getModelClass($t->type) }} #{{ $t->type_id }}</li>
						    @endforeach
					    </ul>
					</div>
				</div>
				@endif
				<div class="form-group{{ $errors->first('file') ? ' has-error' : '' }}" style="border-bottom:none;">
				    {!! Form::label('image', 'Modifier un média', ['class'=>'col-md-3 control-label']) !!}
				    <div class="col-md-9">
				    	<div class="fileinput fileinput-new" data-provides="fileinput">
							<span class="btn btn-default btn-file">
								<span class="fileinput-new">Choisir un fichier</span>
								<span class="fileinput-exists">Modifier</span>
								<input type="file" name="file">
							</span>
							<span class="fileinput-filename"></span>
							<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float:none">&times;</a>
						</div>
						<span class="help-block">Formats autorisés : {{ $media->type == 'file' ? 'all except ' : ''}}<i>{{ implode(', ', picturesExtensions()) }}</i></span>
				     	{!! $errors->first('file', '<span class="help-block">:message</span>') !!}
				    </div>
				</div>
				<ul class="nav nav-tabs push" data-toggle="tabs">
					<li><a href="#tab-infos"><i class="fa fa-info-circle"></i>&nbsp;&nbsp;Informations</a></li>
					@if($media->type == 'picture')
					<li><a href="#tab-effects"><i class="fa fa-eyedropper"></i>&nbsp;&nbsp;Édition d'image</a></li>
					@endif
				</ul>
				<div class="tab-content">
			    	<div class="tab-pane" id="tab-infos">
				    	<div class="form-group{{ $errors->has('alt') ? ' has-error' : '' }}">
						    <label class="col-md-3 control-label" for="alt">Alt&nbsp;&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" title="Ce champs sera affiché si le média est introuvable sur le serveur"></i></label>
						    <div class="col-md-9">
							    {!! Form::text('alt', null, ['class'=>'form-control', 'id' => 'alt']) !!}
						        {!! $errors->first('alt', '<span class="help-block">:message</span>') !!}
							</div>
						</div>
						<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
						    <label class="col-md-3 control-label" for="title">Title&nbsp;&nbsp;<i class="fa fa-question-circle" data-toggle="tooltip" title="Ce champs donne des informations supplémentaires au survol"></i></label>
						    <div class="col-md-9">
							    {!! Form::text('title', null, ['class'=>'form-control', 'id' => 'title']) !!}
						        {!! $errors->first('title', '<span class="help-block">:message</span>') !!}
							</div>
						</div>
						<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
						    {!! Form::label('description', 'Description', ['class'=>'col-md-3 control-label']) !!}
						    <div class="col-md-9">
							    {!! Form::textarea('description', null, ['rows'=>'3', 'class'=>'form-control', 'rows' => 5]) !!}
						        {!! $errors->first('description', '<span class="help-block">:message</span>') !!}
							</div>
						</div>
			    	</div>
			    	@if($media->type == 'picture')
				    <div class="tab-pane" id="tab-effects">
						<div class="form-group">
						    {!! Form::label('flip_x', 'Flip horizontal', ['class'=>'col-md-3 control-label']) !!}
						    <div class="col-md-9">
								<label class="switch switch-primary"><input type="checkbox" name="flip_x" class="flip_checkbox" data-animated="flipInX" data-flip="flip-h-y" value="1"><span></span></label>
							</div>
						</div>
						<div class="form-group">
						    {!! Form::label('flip_y', 'Flip vertical', ['class'=>'col-md-3 control-label']) !!}
						    <div class="col-md-9">
								<label class="switch switch-primary"><input type="checkbox" name="flip_y" class="flip_checkbox" data-animated="flipInY" data-flip="flip-v-y" value="1"><span></span></label>
							</div>
						</div>
				    </div>
				    @endif
				</div>
				<div class="form-group form-actions">
				    <div class="col-md-9 col-md-offset-3">
				        <a class="btn btn-default" href="{!! route('medias.index') !!}"><i class="fa fa-angle-left"></i> Retour</a>
				        <button type="submit" class="btn btn-primary">Envoyer <i class="fa fa-angle-right"></i></button>
				    </div>
				</div>
		    {!! Form::close() !!}
	    </div>
    </div>
</div>
@stop

@push('styles')

<!-- Video JS -->
<link rel="stylesheet" href="{{ asset('admin1/plugins/videoJS/video-js.css') }}">

<style>
	#block-picture .flip_v{-moz-transform: scaleX(-1);-o-transform: scaleX(-1);-webkit-transform: scaleX(-1);transform: scaleX(-1);filter: FlipH;-ms-filter: "FlipH";}
	#block-picture .flip_h{-moz-transform: scaleY(-1);-o-transform: scaleY(-1);-webkit-transform: scaleY(-1);transform: scaleY(-1);filter: FlipV;-ms-filter: "FlipV";}
	.video-js {
		width: 100%;
		max-height: 400px;
	}
	/* Change all text and icon colors in the player. */
    .vjs-matrix.video-js {
      color: #FFFFFF;
    }
    /* Change the border of the big play button. */
    .vjs-matrix .vjs-big-play-button {
      border-color: #FFFFFF;
      color: #FFFFFF;
    }
    /* Change the color of various "bars". */
    .vjs-matrix .vjs-volume-level,
    .vjs-matrix .vjs-play-progress,
    .vjs-matrix .vjs-slider-bar {
      background: #eb766a;
    }
</style>
@endpush
@push('scripts')

<script type="text/javascript" src="{{ asset('admin1/plugins/videoJS/video-js.js') }}"></script>

<script>
$(document).ready(function(){
	$('.nav-tabs a:first').tab('show');
	$('.flipD').hide();
	$('.flip-n').fadeIn();
	$('input[class="flip_checkbox"]').change(function(){
		$('input[class="flip_checkbox"]').not($(this)).prop('checked', false);
		if($(this).is(':checked')){
			$('.flipD').hide();
			$('.'+$(this).data('flip')).removeClass('animated flipInX flipInY fadeIn').addClass('animated '+$(this).data('animated')).show();
		}
		else {
			$('.flipD').hide();
			$('.flip-n').removeClass('animated flipInX flipInY fadeIn').addClass('animated '+$(this).data('animated')).show();
		}
	});
	$('.media-links select').select2({
		ajax: {
			dataType: 'json',
		    url: "{{ route('medias_autocomplete') }}?media_id={{ $media->id }}",
		    processResults: function (data) {
				return data;
		    }
		}
    });
});
</script>
@endpush
