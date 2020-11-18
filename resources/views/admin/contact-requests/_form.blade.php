{{-- Lu --}}
<div class="form-group{{ $errors->has('lu') ? ' has-error' : '' }}">
    {!! Form::label('lu', 'Lu', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-9">
        <label class="switch switch-primary">
            {!! Form::checkbox('lu', 1, isset($contact_request) && $contact_request->lu == 1) !!}
            <span></span>
        </label>
    </div>
</div>

{{-- Nom --}}
<div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
    {!! Form::label('last_name', 'Nom', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('last_name', null, ['class' => 'form-control', 'disabled']) !!}
        {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
    </div>
</div>

{{-- Prénom --}}
<div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
    {!! Form::label('first_name', 'Prénom', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('first_name', null, ['class' => 'form-control', 'disabled']) !!}
        {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
    </div>
</div>

{{-- Message --}}
<div class="form-group {{ $errors->has('message') ? 'has-error' : '' }}">
    {!! Form::label('message', 'Message', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::textarea('message', null, ['class' => 'form-control', 'disabled']) !!}
        {!! $errors->first('message', '<span class="help-block">:message</span>') !!}
    </div>
</div>

{{-- Note privée --}}
<div class="form-group {{ $errors->has('note') ? 'has-error' : '' }}">
    {!! Form::label('note', 'Notes privées', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::textarea('note', null, ['class' => 'form-control', 'placeholder' => 'Cet encart vous est réservé, l\'internaute ne verra jamais ces notes']) !!}
        {!! $errors->first('note', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group form-actions">
    <div class="col-md-9 col-md-offset-3">
        <a class="btn btn-default" href="{!! route('sms.index') !!}"><i class="fa fa-angle-left"></i> Retour</a>
        <button type="submit" class="btn btn-primary">{!! $submitButtonText !!} <i class="fa fa-angle-right"></i></button>
    </div>
</div>
