<div class="tab-pane" id="tab-{{ $language->short }}">

    {{-- Titre --}}
    <div class="form-group {{ $errors->has('title_' . $language->short) ? 'has-error' : '' }}">
        {!! Form::label('title_' . $language->short, 'Titre *', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-6">
            {!! Form::text('title_' . $language->short, null, ['class' => 'form-control']) !!}
            {!! $errors->first('title_' . $language->short, '<span class="help-block">:message</span>') !!}
        </div>
    </div>

    {{-- Sous-titre --}}
    <div class="form-group {{ $errors->has('subtitle_' . $language->short) ? 'has-error' : '' }}">
        {!! Form::label('subtitle_' . $language->short, 'Sous-titre *', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-6">
            {!! Form::text('subtitle_' . $language->short, null, ['class' => 'form-control']) !!}
            {!! $errors->first('subtitle_' . $language->short, '<span class="help-block">:message</span>') !!}
        </div>
    </div>

    {{-- Description --}}
    <div class="form-group {{ $errors->has('description_' . $language->short) ? 'has-error' : '' }}">
        {!! Form::label('description_' . $language->short, 'Description *', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-6">
            {!! Form::textarea('description_' . $language->short, null, ['class' => 'form-control']) !!}
            {!! $errors->first('description_' . $language->short, '<span class="help-block">:message</span>') !!}
        </div>
    </div>

    {{-- Button --}}
    <div class="form-group {{ $errors->has('btn_' . $language->short) ? 'has-error' : '' }}">
        {!! Form::label('btn_' . $language->short, 'Bouton *', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-6">
            {!! Form::text('btn_' . $language->short, null, ['class' => 'form-control']) !!}
            {!! $errors->first('btn_' . $language->short, '<span class="help-block">:message</span>') !!}
        </div>
    </div>

</div>
