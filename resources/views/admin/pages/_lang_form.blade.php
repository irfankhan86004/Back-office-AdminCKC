<div class="tab-pane" id="tab-{{ $language->short }}">
	<div class="form-group{{ $errors->has('name_'.$language->short) ? ' has-error' : '' }}">
	    {!! Form::label('name_'.$language->short, 'Nom *', ['class'=>'col-md-3 control-label']) !!}
	    <div class="col-md-6">
		    {!! Form::text('name_'.$language->short, null, ['class'=>'form-control']) !!}
	        {!! $errors->first('name_'.$language->short, '<span class="help-block">:message</span>') !!}
		</div>
	</div>
	<div class="form-group{{ $errors->has('subname_'.$language->short) ? ' has-error' : '' }}">
	    {!! Form::label('subname_'.$language->short, 'Sous-nom', ['class'=>'col-md-3 control-label']) !!}
	    <div class="col-md-6">
		    {!! Form::text('subname_'.$language->short, null, ['class'=>'form-control']) !!}
	        {!! $errors->first('subname_'.$language->short, '<span class="help-block">:message</span>') !!}
		</div>
	</div>
	<div class="form-group{{ $errors->has('url_'.$language->short) ? ' has-error' : '' }}">
	    {!! Form::label('url_'.$language->short, 'Url *', ['class'=>'col-md-3 control-label']) !!}
	    <div class="col-md-6">
		    {!! Form::text('url_'.$language->short, null, ['class'=>'form-control']) !!}
	        {!! $errors->first('url_'.$language->short, '<span class="help-block">:message</span>') !!}
		</div>
	</div>
	<div class="form-group{{ $errors->has('canonical_url_'.$language->short) ? ' has-error' : '' }}">
	    {!! Form::label('canonical_url_'.$language->short, 'Url canonique', ['class'=>'col-md-3 control-label']) !!}
	    <div class="col-md-6">
		    {!! Form::text('canonical_url_'.$language->short, null, ['class'=>'form-control']) !!}
	        {!! $errors->first('canonical_url_'.$language->short, '<span class="help-block">:message</span>') !!}
		</div>
	</div>
	<div class="form-group{{ $errors->has('title_'.$language->short) ? ' has-error' : '' }}">
	    {!! Form::label('title_'.$language->short, 'Méta title', ['class'=>'col-md-3 control-label']) !!}
	    <div class="col-md-6">
		    {!! Form::text('title_'.$language->short, null, ['class'=>'form-control']) !!}
	        {!! $errors->first('title_'.$language->short, '<span class="help-block">:message</span>') !!}
		</div>
	</div>
	<div class="form-group{{ $errors->has('keywords_'.$language->short) ? ' has-error' : '' }}">
	    {!! Form::label('keywords_'.$language->short, 'Méta mots clés', ['class'=>'col-md-3 control-label']) !!}
	    <div class="col-md-6">
		    {!! Form::text('keywords_'.$language->short, null, ['class'=>'form-control']) !!}
	        {!! $errors->first('keywords_'.$language->short, '<span class="help-block">:message</span>') !!}
		</div>
	</div>
	<div class="form-group{{ $errors->has('description_'.$language->short) ? ' has-error' : '' }}">
	    {!! Form::label('description_'.$language->short, 'Méta description', ['class'=>'col-md-3 control-label']) !!}
	    <div class="col-md-6">
		    {!! Form::textarea('description_'.$language->short, null, ['class'=>'form-control']) !!}
	        {!! $errors->first('description_'.$language->short, '<span class="help-block">:message</span>') !!}
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-md-3">Edition</label>
		<div class="col-md-9">
			@if(isset($page))
			<a href="{{ route('page_edition',[$page->id, $language->id]) }}" class="btn btn-info">Editer le contenu de la page</a>
			@else
			<div class="alert alert-info" style="margin-bottom:0">Veuillez d'abord créer la page avant de pouvoir éditer son contenu</div>
			@endif
		</div>
	</div>
</div>