 <!-- Name -->
<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
    {!! Form::label('name', 'Nom de la campagne *', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<!-- Code -->
<div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">
    {!! Form::label('code', 'Code *', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('code', null, ['class' => 'form-control']) !!}
        {!! $errors->first('code', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<!-- Value -->
<div class="form-group {{ $errors->has('value') ? 'has-error' : '' }}">
    {!! Form::label('value', 'Valeur *', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('value', null, ['class' => 'form-control']) !!}
        {!! $errors->first('value', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<!-- Type -->
<div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
    {!! Form::label('type', 'Type', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::select('type', ['amount' => 'Montant', 'percentage' => 'Pourcentage'], null, ['class'=>'form-control select-chosen']) !!}
        {!! $errors->first('type', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<!-- Date d'expiration -->
<div class="form-group {{ $errors->has('date_expire') ? 'has-error' : '' }}">
    {!! Form::label('date_expire', 'Date d\'expiration', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('date_expire', \Request::old('date',isset($promoCode) ? $promoCode->date_expire : date('d/m/Y')), ['class' => 'datepicker form-control']) !!}
        {!! $errors->first('date_expire', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<!-- Used -->
<div class="form-group {{ $errors->has('used') ? 'has-error' : '' }}">
    {!! Form::label('used', 'Utilisations', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('used', null, ['class' => 'form-control', 'disabled']) !!}
        {!! $errors->first('used', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<!-- Max use -->
<div class="form-group {{ $errors->has('max_use') ? 'has-error' : '' }}">
    {!! Form::label('max_use', 'Maximum d\'utilisations', ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('max_use', null, ['class' => 'form-control']) !!}
        {!! $errors->first('max_use', '<span class="help-block">:message</span>') !!}
    </div>
</div>

<div class="form-group form-actions">
    <div class="col-md-9 col-md-offset-3">
        <a class="btn btn-default" href="{!! route('promo-codes.index') !!}"><i class="fa fa-angle-left"></i> Retour</a>
        <button type="submit" class="btn btn-primary">{!! $submitButtonText !!} <i class="fa fa-angle-right"></i></button>
    </div>
</div>
