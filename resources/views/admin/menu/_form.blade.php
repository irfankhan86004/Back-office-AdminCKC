<ul class="nav nav-tabs push" data-toggle="tabs">
	<li class="active"><a href="#tab1">Caractéristiques</a></li>
	@foreach($languages as $language)
	<li><a href="#tab-{{ $language->short }}"><i class="fa fa-font"></i>&nbsp;&nbsp;{{ $language->name }}</a></li>
	@endforeach
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="tab1">
		<div class="form-group{{ $errors->has('position') ? ' has-error' : '' }}">
		    {!! Form::label('position', 'Position *', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-2">
			    {!! Form::text('position', null, ['class'=>'form-control']) !!}
		        {!! $errors->first('position', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		<div class="form-group{{ $errors->has('parent_id') ? ' has-error' : '' }}">
		    {!! Form::label('parent_id', 'Menu parent *', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-5">
			    {!! Form::select('parent_id', $select, null, ['class'=>'form-control select-chosen']) !!}
		        {!! $errors->first('parent_id', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		<div class="form-group{{ $errors->has('page_id') ? ' has-error' : '' }}">
		    {!! Form::label('page_id', 'Ouvrir la page', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-5">
			    {!! Form::select('page_id', $pages, null, ['class'=>'form-control select-chosen']) !!}
		        {!! $errors->first('page_id', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		<div class="form-group{{ $errors->has('blog_post_id') ? ' has-error' : '' }}">
		    {!! Form::label('blog_post_id', '(ou) L\'article', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-5">
			    {!! Form::select('blog_post_id', $posts, null, ['class'=>'form-control select-chosen']) !!}
		        {!! $errors->first('blog_post_id', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		<div class="form-group{{ $errors->has('link') ? ' has-error' : '' }}">
		    {!! Form::label('link', '(ou) le Lien', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-5">
			    {!! Form::text('link', null, ['class'=>'form-control', 'placeholder'=>'http://www...']) !!}
		        {!! $errors->first('link', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		<div class="form-group{{ $errors->has('anchor') ? ' has-error' : '' }}">
		    {!! Form::label('anchor', 'Ancre', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-5">
			    {!! Form::text('anchor', null, ['class'=>'form-control']) !!}
		        {!! $errors->first('anchor', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		<div class="form-group{{ $errors->has('target') ? ' has-error' : '' }}">
		    {!! Form::label('target', 'Ouvrir dans', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-2">
			    {!! Form::select('target', ['_self'=>'La même fenêtre', '_blank'=>'Une nouvelle fenêtre'], null, ['class'=>'form-control select-chosen']) !!}
		        {!! $errors->first('target', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
    </div>
    @foreach($languages as $language)
    	@include('admin.menu._lang_form')
    @endforeach
</div>

<div class="form-group form-actions">
    <div class="col-md-9 col-md-offset-3">
        <a class="btn btn-default" href="{!! route('menu.index') !!}"><i class="fa fa-angle-left"></i> Retour</a>
        <button type="submit" class="btn btn-primary">{!! $submitButtonText !!} <i class="fa fa-angle-right"></i></button>
    </div>
</div>
