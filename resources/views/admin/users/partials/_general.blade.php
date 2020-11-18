<div class="tab-pane active" id="tabGeneral">

    @if(isset($user))
        <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
            {!! Form::label('user_id', 'ID Utilisateur *', ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-4">
                {!! Form::text('user_id', null, ['class' => 'form-control', 'disabled']) !!}
                {!! $errors->first('user_id', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
    @endif

    <div class="form-group{{ $errors->has('civility') ? ' has-error' : '' }}">
        {!! Form::label('civility', 'Civilité *', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-4">
            {!! Form::select('civility', ['Mr' => 'Mr', 'Mme' => 'Mme'], null, ['class' => 'form-control select-chosen']) !!}
            {!! $errors->first('civility', '<span class="help-block">:message</span>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
        {!! Form::label('last_name', 'Nom *', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-4">
            {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
            {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
        {!! Form::label('first_name', 'Prénom *', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-4">
            {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
            {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('telephone') ? 'has-error' : '' }}">
        {!! Form::label('telephone', 'Téléphone *', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-4">
            {!! Form::text('telephone', null, array('class' => 'form-control')) !!}
            {!! $errors->first('telephone', '<span class="help-block">:message</span>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
        {!! Form::label('email', 'Email *', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-4">
            {!! Form::text('email', null, array('class' => 'form-control')) !!}
            {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->has('login') ? 'has-error' : '' }}">
        {!! Form::label('login', 'Login *', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-4">
            {!! Form::text('login', null, ['class' => 'form-control']) !!}
            {!! $errors->first('login', '<span class="help-block">:message</span>') !!}
        </div>
    </div>

    <div class="form-group {{ $errors->first('password') ? 'has-error' : '' }}">
        {!! Form::label('password', 'Mot de passe', ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-4">
            {!! Form::text('password', '', array('autocomplete' => 'off','class' => 'form-control')) !!}
            {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
            @if(isset($user))
            <span class="help-block">Laissez ce champ vide si vous ne souhaitez pas mettre le mot de passe à jour</span>
            @endif
        </div>
    </div>



</div>
