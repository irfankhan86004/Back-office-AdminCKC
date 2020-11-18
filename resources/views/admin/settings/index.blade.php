@extends('admin.layouts.app')

@section('title')
Configuration
@stop

@section('content')

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fas fa-wrench"></i>Configuration</small>
        </h1>
    </div>
</div>

<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('settings.index') }}">Configuration</a></li>
</ul>

@include('errors.list')

<div class="block">

    <div class="block-title">
        <h2>Configuration</h2>
    </div>

    {!! Form::model($settings, ['route' => ['settings.update', $settings->id], 'method' => 'PATCH', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

		<ul class="nav nav-tabs push" data-toggle="tabs">
			<li class="active"><a href="#tab1"><i class="fas fa-mail-bulk"></i>&nbsp;&nbsp;Informations de contact</a></li>
			<li><a href="#tab2"><i class="far fa-thumbs-up"></i>&nbsp;&nbsp;Réseaux sociaux</a></li>
			<li><a href="#tab3"><i class="fas fa-cogs"></i>&nbsp;&nbsp;Configuration Mailgun</a></li>
		</ul>
		<div class="tab-content">

			<!-- Tab 1 -->
			<div class="tab-pane active" id="tab1">
				<div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
					{!! Form::label('address', 'Adresse', ['class'=>'col-md-3 control-label']) !!}
					<div class="col-md-6">
						{!! Form::text('address', null, ['class'=>'form-control']) !!}
						{!! $errors->first('address', '<span class="help-block">:message</span>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->has('city') ? 'has-error' : '' }}">
					{!! Form::label('city', 'Ville', ['class'=>'col-md-3 control-label']) !!}
					<div class="col-md-6">
						{!! Form::text('city', null, ['class'=>'form-control']) !!}
						{!! $errors->first('city', '<span class="help-block">:message</span>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->has('zipcode') ? 'has-error' : '' }}">
					{!! Form::label('zipcode', 'Code postal', ['class'=>'col-md-3 control-label']) !!}
					<div class="col-md-6">
						{!! Form::text('zipcode', null, ['class'=>'form-control']) !!}
						{!! $errors->first('zipcode', '<span class="help-block">:message</span>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
					{!! Form::label('phone', 'Téléphone', ['class'=>'col-md-3 control-label']) !!}
					<div class="col-md-6">
						{!! Form::text('phone', null, ['class'=>'form-control']) !!}
						{!! $errors->first('phone', '<span class="help-block">:message</span>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
					{!! Form::label('email', 'Email', ['class'=>'col-md-3 control-label']) !!}
					<div class="col-md-6">
						{!! Form::text('email', null, ['class'=>'form-control']) !!}
						{!! $errors->first('email', '<span class="help-block">:message</span>') !!}
					</div>
				</div>
			</div>

			<!-- Tab 2 -->
			<div class="tab-pane" id="tab2">
				<div class="form-group {{ $errors->has('facebook') ? 'has-error' : '' }}">
					{!! Form::label('facebook', 'Facebook', ['class'=>'col-md-3 control-label']) !!}
					<div class="col-md-6">
						{!! Form::text('facebook', null, ['class'=>'form-control', 'placeholder' => 'https://...']) !!}
						{!! $errors->first('facebook', '<span class="help-block">:message</span>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->has('twitter') ? 'has-error' : '' }}">
					{!! Form::label('twitter', 'Twitter', ['class'=>'col-md-3 control-label']) !!}
					<div class="col-md-6">
						{!! Form::text('twitter', null, ['class'=>'form-control', 'placeholder' => 'https://...']) !!}
						{!! $errors->first('twitter', '<span class="help-block">:message</span>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->has('instagram') ? 'has-error' : '' }}">
					{!! Form::label('instagram', 'Instagram', ['class'=>'col-md-3 control-label']) !!}
					<div class="col-md-6">
						{!! Form::text('instagram', null, ['class'=>'form-control', 'placeholder' => 'https://...']) !!}
						{!! $errors->first('instagram', '<span class="help-block">:message</span>') !!}
					</div>
				</div>

				<div class="form-group {{ $errors->has('linkedin') ? 'has-error' : '' }}">
					{!! Form::label('linkedin', 'LinkedIn', ['class'=>'col-md-3 control-label']) !!}
					<div class="col-md-6">
						{!! Form::text('linkedin', null, ['class'=>'form-control', 'placeholder' => 'https://...']) !!}
						{!! $errors->first('linkedin', '<span class="help-block">:message</span>') !!}
					</div>
				</div>
			</div>
			
			<!-- Tab 3 -->
			<div class="tab-pane" id="tab3">
				<div class="form-group{{ $errors->has('mailgun_use') ? ' has-error' : '' }}">
					{!! Form::label('mailgun_use', 'Utiliser Mailgun', ['class'=>'col-md-3 control-label']) !!}
					<div class="col-md-9">
						<label class="switch switch-primary">
							{!! Form::checkbox('mailgun_use', 1, $settings->mailgun_use) !!}
							<span></span>
						</label>
					</div>
				</div>
				
				<div class="form-group {{ $errors->has('mailgun_domain') ? 'has-error' : '' }}">
					{!! Form::label('mailgun_domain', 'Domain', ['class'=>'col-md-3 control-label']) !!}
					<div class="col-md-6">
						{!! Form::text('mailgun_domain', null, ['class'=>'form-control', 'placeholder' => 'mg.example.com']) !!}
						{!! $errors->first('mailgun_domain', '<span class="help-block">:message</span>') !!}
					</div>
				</div>
				
				<div class="form-group {{ $errors->has('mailgun_endpoint') ? 'has-error' : '' }}">
					{!! Form::label('mailgun_endpoint', 'API Endpoint (Région)', ['class'=>'col-md-3 control-label']) !!}
					<div class="col-md-6">
						{!! Form::text('mailgun_endpoint', null, ['class'=>'form-control', 'placeholder' => 'api.eu.mailgun.net']) !!}
						{!! $errors->first('mailgun_endpoint', '<span class="help-block">:message</span>') !!}
					</div>
				</div>
				
				<div class="form-group {{ $errors->has('mailgun_secret') ? 'has-error' : '' }}">
					{!! Form::label('mailgun_secret', 'API Key', ['class'=>'col-md-3 control-label']) !!}
					<div class="col-md-6">
						{!! Form::text('mailgun_secret', null, ['class'=>'form-control']) !!}
						{!! $errors->first('mailgun_secret', '<span class="help-block">:message</span>') !!}
					</div>
				</div>
				
				<div class="form-group {{ $errors->has('from_name') ? 'has-error' : '' }}">
					{!! Form::label('from_name', 'From Name', ['class'=>'col-md-3 control-label']) !!}
					<div class="col-md-6">
						{!! Form::text('from_name', null, ['class'=>'form-control']) !!}
						{!! $errors->first('from_name', '<span class="help-block">:message</span>') !!}
					</div>
				</div>
				
				<div class="form-group {{ $errors->has('from_email') ? 'has-error' : '' }}">
					{!! Form::label('from_email', 'From Email', ['class'=>'col-md-3 control-label']) !!}
					<div class="col-md-6">
						{!! Form::email('from_email', null, ['class'=>'form-control']) !!}
						{!! $errors->first('from_email', '<span class="help-block">:message</span>') !!}
					</div>
				</div>
			</div>
			<div class="form-group form-actions">
				<div class="col-md-9 col-md-offset-3">
					<button type="submit" class="btn btn-primary">Mettre à jour <i class="fa fa-angle-right"></i></button>
				</div>
			</div>
		</div>
    {!! Form::close() !!}
</div>

@endsection
