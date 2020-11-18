<!-- Type -->
<div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
    {!! Form::label('slug', 'Type', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('slug', null, ['class' => 'form-control', 'disabled']) !!}
        {!! $errors->first('slug', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<!-- From -->
<div class="form-group {{ $errors->has('from') ? 'has-error' : '' }}">
    {!! Form::label('from', 'Expéditeur', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('from', null, ['class' => 'form-control', 'disabled']) !!}
        {!! $errors->first('from', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<!-- To -->
<div class="form-group {{ $errors->has('to') ? 'has-error' : '' }}">
    {!! Form::label('to', 'Destinataire', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('to', null, ['class' => 'form-control', 'disabled']) !!}
        {!! $errors->first('to', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<!-- Subject -->
<div class="form-group {{ $errors->has('subject') ? 'has-error' : '' }}">
    {!! Form::label('subject', 'Sujet', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('subject', null, ['class' => 'form-control', 'disabled']) !!}
        {!! $errors->first('subject', '<span class="help-block">:message</span>') !!}
    </div>
</div>

{{-- Voir l'email --}}
<div class="form-group">
    <div class="col-md-9 col-md-offset-3">
        <a class="btn btn-warning" target="_blank" href="{{ route('emails.show', [$email->id]) }}">Voir l'email</a>
    </div>
</div>

<div class="form-group form-actions">
    <div class="col-md-9 col-md-offset-3">
        <a class="btn btn-default" href="{!! route('emails.index') !!}"><i class="fa fa-angle-left"></i> Retour</a>
        <button type="submit" class="btn btn-primary">{!! $submitButtonText !!} <i class="fa fa-angle-right"></i></button>
    </div>
</div>
