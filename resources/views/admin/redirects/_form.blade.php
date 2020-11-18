<div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">
    {!! Form::label('active', 'Activer', ['class'=>'col-md-3 control-label']) !!}
    <div class="col-md-4">
	    <label class="switch switch-primary">
	    	{!! Form::checkbox('active', 1, isset($redirect) ? $redirect->active : false) !!}
	    	<span></span>
	    </label>
    </div>
</div>
<div class="form-group{{ $errors->has('type') ? ' has-error' : ''}}">
    {!! Form::label('type', 'Type *', ['class'=>'col-md-3 control-label']) !!}
    <div class="col-md-4">
	    {!! Form::select('type', \App\Models\RedirectU::getSelectTypes(), null, ['class'=>'form-control select-chosen']) !!}
        {!! $errors->first('type', '<span class="help-block">:message</span>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('origin_url') ? ' has-error' : ''}}">
    {!! Form::label('origin_url', 'Url *', ['class'=>'col-md-3 control-label']) !!}
    <div class="col-md-4">
	    {!! Form::text('origin_url', null, ['class'=>'form-control']) !!}
        {!! $errors->first('origin_url', '<span class="help-block">:message</span>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('page_id') ? ' has-error' : '' }}">
	{!! Form::label('page_id', 'Ouvre la page', ['class'=>'col-md-3 control-label']) !!}
	<div class="col-md-4">
		{!! Form::select('page_id', $pages, null, ['class'=>'form-control select-chosen']) !!}
		{!! $errors->first('page_id', '<span class="help-block">:message</span>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('blog_post_id') ? ' has-error' : '' }}">
	{!! Form::label('blog_post_id', '(ou) l\'article', ['class'=>'col-md-3 control-label']) !!}
	<div class="col-md-4">
		{!! Form::select('blog_post_id', $posts, null, ['class'=>'form-control select-chosen']) !!}
		{!! $errors->first('blog_post_id', '<span class="help-block">:message</span>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('link') ? ' has-error' : '' }}">
	{!! Form::label('link', '(ou) le lien', ['class'=>'col-md-3 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('link', null, ['class'=>'form-control', 'placeholder'=>'http://www...']) !!}
		{!! $errors->first('link', '<span class="help-block">:message</span>') !!}
	</div>
</div>

<div class="form-group{{ $errors->has('target') ? ' has-error' : '' }}">
	{!! Form::label('target', 'S\'ouvre dans', ['class'=>'col-md-3 control-label']) !!}
	<div class="col-md-4">
		{!! Form::select('target', \App\Models\RedirectU::getSelectTargets(), null, ['class'=>'form-control select-chosen']) !!}
		{!! $errors->first('target', '<span class="help-block">:message</span>') !!}
	</div>
</div>

<div class="form-group form-actions">
    <div class="col-md-9 col-md-offset-3">
        <a class="btn btn-default" href="{!! route('redirects.index') !!}"><i class="fa fa-angle-left"></i> Retour</a>
        <button type="submit" class="btn btn-primary">{!! $submitButtonText !!} <i class="fa fa-angle-right"></i></button>
    </div>
</div>