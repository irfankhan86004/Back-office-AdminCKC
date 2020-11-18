<ul class="nav nav-tabs push" data-toggle="tabs">
    <li class="active"><a href="#tab1">Caractéristiques</a></li>
    @foreach($languages as $language)
    <li><a href="#tab-{{ $language->short }}"><i class="fa fa-font"></i>&nbsp;&nbsp;{{ $language->name }}</a></li>
    @endforeach
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="tab1">

        <div class="form-group {{ $errors->has('published') ? 'has-error' : '' }}">
            {!! Form::label('published', 'Publié sur le site', ['class'=>'col-md-3 control-label']) !!}
            <div class="col-md-9">
        	    <label class="switch switch-primary"><input type="checkbox" name="published" value="1" <?php echo (isset($carousel_arr) && $carousel_arr['published'] == 1) ? 'checked' : ''; ?>><span></span></label>
            </div>
        </div>

        <div class="form-group {{ $errors->has('link') ? 'has-error' : '' }}">
            {!! Form::label('link', 'Lien', ['class'=>'col-md-3 control-label']) !!}
            <div class="col-md-6">
        	    {!! Form::text('link', null, ['class'=>'form-control', 'placeholder'=>'http://www...']) !!}
                {!! $errors->first('link', '<span class="help-block">:message</span>') !!}
        	</div>
        </div>

    	<div class="form-group {{ $errors->has('position') ? 'has-error' : '' }}">
    	    {!! Form::label('position', 'Position *', ['class'=>'col-md-3 control-label']) !!}
    	    <div class="col-md-2">
    		    {!! Form::text('position', null, ['class'=>'form-control']) !!}
    	        {!! $errors->first('position', '<span class="help-block">:message</span>') !!}
    		</div>
    	</div>
    	<div class="form-group {{ $errors->has('page_id') ? 'has-error' : '' }}">
    	    {!! Form::label('page_id', 'Ouvrir la page', ['class'=>'col-md-3 control-label']) !!}
    	    <div class="col-md-6">
    		    {!! Form::select('page_id', $pages, null, ['class'=>'form-control select-chosen']) !!}
    	        {!! $errors->first('page_id', '<span class="help-block">:message</span>') !!}
    		</div>
    	</div>
    	<div class="form-group {{ $errors->has('blog_post_id') ? 'has-error' : '' }}">
    	    {!! Form::label('blog_post_id', '(ou) L\'article', ['class'=>'col-md-3 control-label']) !!}
    	    <div class="col-md-6">
    		    {!! Form::select('blog_post_id', $posts, null, ['class'=>'form-control select-chosen']) !!}
    	        {!! $errors->first('blog_post_id', '<span class="help-block">:message</span>') !!}
    		</div>
    	</div>

    	<div class="form-group {{ $errors->has('link') ? 'has-error' : '' }}">
    	    {!! Form::label('link', '(ou) le Lien', ['class'=>'col-md-3 control-label']) !!}
    	    <div class="col-md-6">
    		    {!! Form::text('link', null, ['class'=>'form-control', 'placeholder'=>'http://www...']) !!}
    	        {!! $errors->first('link', '<span class="help-block">:message</span>') !!}
    		</div>
    	</div>

    	<div class="form-group {{ $errors->has('target') ? 'has-error' : '' }}">
    	    {!! Form::label('target', 'Ouvrir dans', ['class'=>'col-md-3 control-label']) !!}
    	    <div class="col-md-4">
    		    {!! Form::select('target', ['_self'=>'La même fenêtre', '_blank'=>'Une nouvelle fenêtre'], null, ['class'=>'form-control select-chosen']) !!}
    	        {!! $errors->first('target', '<span class="help-block">:message</span>') !!}
    		</div>
    	</div>

    	<div class="form-group {{ $errors->has('background_slide') ? 'has-error' : '' }}">
    		{!! Form::label('background_slide', 'Couleur de fond (arrière-plan)', ['class'=>'col-md-3 control-label']) !!}
    		<div class="col-md-3">
    			<div class="input-group input-colorpicker">
    				{!! Form::text('background_slide', null, ['class'=>'form-control', 'id'=>'example-colorpicker2']) !!}
    				<span class="input-group-addon"><i></i></span>
    			</div>
    		</div>
    	</div>

    	<div class="form-group {{ $errors->has('background_btn') ? 'has-error' : '' }}">
    		{!! Form::label('background_btn', 'Couleur du bouton d\'action', ['class'=>'col-md-3 control-label']) !!}
    		<div class="col-md-3">
    			<div class="input-group input-colorpicker">
    				{!! Form::text('background_btn', null, ['class'=>'form-control', 'id'=>'example-colorpicker2']) !!}
    				<span class="input-group-addon"><i></i></span>
    			</div>
    		</div>
    	</div>
    </div>

    @foreach($languages as $language)
        @include('admin.carousels._lang_form')
    @endforeach

</div>

@if(!isset($carousel_arr) && (Auth::guard('admin')->user()->hasPermission(6)))
<div class="form-group">
    <div class="col-md-5 col-md-offset-3">
        <div class="alert alert-info">Veuillez d'abord créer le slide avant de pouvoir éditer son image</div>
    </div>
</div>
@endif

<div class="form-group form-actions">
    <div class="col-md-9 col-md-offset-3">
        <a class="btn btn-default" href="{!! route('carousel.index') !!}"><i class="fa fa-angle-left"></i> Retour</a>
        <button type="submit" class="btn btn-primary">{!! $submitButtonText !!} <i class="fa fa-angle-right"></i></button>
    </div>
</div>
