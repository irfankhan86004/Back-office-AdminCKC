{{-- Name --}}
<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Form::label('title', 'Titre *', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('title', null, ['class' => 'form-control']) !!}
        {!! $errors->first('title', '<span class="help-block">:message</span>') !!}
    </div>
</div>

{{-- Tage --}}
<div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
    {!! Form::label('content', 'Contenu *', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::textarea('content', null, ['class' => 'form-control']) !!}
        {!! $errors->first('content', '<span class="help-block">:message</span>') !!}
    </div>
</div>

{{-- Position --}}
<div class="form-group {{ $errors->has('position') ? 'has-error' : ''}}">
    {!! Form::label('position', 'Position', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::select('position', $positions, null, ['class' => 'form-control select-chosen']) !!}
        {!! $errors->first('position', '<span class="help-block">:message</span>') !!}
    </div>
</div>

{{-- Alert info --}}
<div class="form-group">
    <div class="col-md-4 col-md-offset-3">
        <div class="alert alert-info" style="margin-bottom:0">Si aucune options n'est sélectionnée, le tag apparaîtra sur toutes les pages</div>
    </div>
</div>

{{-- Pages --}}
<div class="form-group {{ $errors->has('pages') ? 'has-error' : ''}}">
    {!! Form::label('pages[]', 'Pages', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::select('pages[]', $pages, null, ['class' => 'form-control select-chosen', 'multiple']) !!}
        {!! $errors->first('pages', '<span class="help-block">:message</span>') !!}
    </div>
</div>

{{-- Blog posts --}}
<div class="form-group {{ $errors->has('posts') ? 'has-error' : ''}}">
    {!! Form::label('posts[]', 'Articles de blogs', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::select('posts[]', $blogPosts, null, ['class' => 'form-control select-chosen', 'multiple']) !!}
        {!! $errors->first('posts', '<span class="help-block">:message</span>') !!}
    </div>
</div>

{{-- Blog Categorie --}}
<div class="form-group {{ $errors->has('blogCategories') ? 'has-error' : ''}}">
    {!! Form::label('blogCategories[]', 'Blog categories', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::select('blogCategories[]', $blogCategories, null, ['class' => 'form-control select-chosen', 'multiple']) !!}
        {!! $errors->first('blogCategories', '<span class="help-block">:message</span>') !!}
    </div>
</div>

{{-- Actions --}}
<div class="form-group form-actions">
    <div class="col-md-9 col-md-offset-3">
        <a class="btn btn-default" href="{!! route('tags.index') !!}"><i class="fa fa-angle-left"></i> Retour</a>
        <button type="submit" class="btn btn-primary">{!! $submitButtonText !!} <i class="fa fa-angle-right"></i></button>
    </div>
</div>
