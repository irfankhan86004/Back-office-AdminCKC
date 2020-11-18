<ul class="nav nav-tabs push" data-toggle="tabs">
    <li class="active"><a href="#tab1">Informations personelles</a></li>
    <li><a href="#tab2">Permissions</a></li>
    <li><a href="#tab4" data-toggle="tooltip" title="Photo de profil"><i class="fa fa-user"></i></a></li>
</ul>
<div class="tab-content">

	<!-- Informations -->
    <div class="tab-pane active" id="tab1">
	    @if(isset($admin))
		<div class="form-group">
		    {!! Form::label('id', 'ID', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-2">
			    {!! Form::text('id', null, ['class'=>'form-control', 'disabled'=>'disabled']) !!}
			</div>
		</div>
		@endif
		<div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
		    {!! Form::label('last_name', 'Nom *', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-4">
				{!! Form::text('last_name', null, ['class'=>'form-control']) !!}
		        {!! $errors->first('last_name', '<span class="help-block">:message</span>') !!}
		    </div>
		</div>
		<div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
		    {!! Form::label('first_name', 'Prénom *', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-4">
				{!! Form::text('first_name', null, ['class'=>'form-control']) !!}
		        {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
		    </div>
		</div>
		<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
		    {!! Form::label('email', 'Email *', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-4">
				{!! Form::text('email', null, array('class'=>'form-control')) !!}
		        {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
		    </div>
		</div>
		<div class="form-group{{ $errors->first('password') ? ' has-error' : '' }}">
		    {!! Form::label('password', 'Mot de passe', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-4">
				{!! Form::text('password', '', array('autocomplete'=>'off','class'=>'form-control')) !!}
				{!! $errors->first('password', '<span class="help-block">:message</span>') !!}
				@if(isset($admin))
				<span class="help-block">Laissez ce champ vide si vous ne souhaitez pas mettre le mot de passe à jour</span>
				@endif
		    </div>
		</div>

    </div>

    <!-- Permissions -->
    <div class="tab-pane" id="tab2">
		@foreach($roles as $r)
		<div class="form-group">
			{!! Form::label('roles[]', $r->name, ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-9">
				<label class="switch switch-primary"><input type="checkbox" name="roles[]" value="{!! $r->id !!}" <?php echo (in_array($r->id, \Request::old('roles', isset($admin) ? $admin_roles : []))) ? 'checked' : ''; ?>><span></span></label>
		    </div>
		</div>
		@endforeach
    </div>

	<!-- Avatar -->
    <div class="tab-pane" id="tab4">
		<div class="form-group{{ $errors->first('avatar') ? ' has-error' : '' }}">
		    <label class="col-md-3 control-label" for="">
		    	@if(isset($admin))
					Changer la photo de profil
				@else
					Ajouter une photo de profil
				@endif
		    </label>
		    <div class="col-md-9">
		    	<div class="fileinput fileinput-new" data-provides="fileinput">
					<span class="btn btn-default btn-file">
						<span class="fileinput-new">Choisir une photo de profil</span>
						<span class="fileinput-exists">Modifier</span>
						<input type="file" name="media">
					</span>
					<span class="fileinput-filename"></span>
					<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float:none">&times;</a>
				</div>
		     	{!! $errors->first('media', '<span class="help-block">:message</span>') !!}
		    </div>
		</div>
		@if(isset($admin) && $admin->media)
		<div class="form-group">
			<label class="col-md-3 control-label photo_actuelle_label" for="">Photo de profil<br><a href="#" class="btn btn-default btn-xs remove_photo">Supprimer</a></label>
			<div class="col-md-9 photo_actuelle"><a href="{{ $admin->media->route() }}" data-toggle="lightbox-image"><img src="{{ $admin->gravatar($admin->email, 100) }}" class="img-rounded"/></a></div>
		</div>
		@else
			@if(isset($admin))
			<div class="form-group">
				<label class="col-md-3 control-label">Photo de profil</label>
				<div class="col-md-9">
					<img src="{{ $admin->gravatar($admin->email, 100) }}" class="img-rounded"/>
				</div>
			</div>
			@endif
		@endif
    </div>
</div>

<div class="form-group form-actions">
    <div class="col-md-9 col-md-offset-3">
        <a class="btn btn-default" href="{!! route('admins.index') !!}"><i class="fa fa-angle-left"></i> Retour</a>
        <button type="submit" class="btn btn-primary">{!! $submitButtonText !!} <i class="fa fa-angle-right"></i></button>
    </div>
</div>

@push('scripts')
<script>
@if(isset($admin))
$('.remove_photo').on('click', function(e){
	e.preventDefault();
	bootbox.confirm("Etes-vous sûr?", function(result) {
		if(result==true){
			$.ajax({
				type: "POST",
				url: "{{ route('remove_avatar') }}",
				data: {
					_token: "{{ csrf_token() }}",
					admin_id: {{$admin->id}}
				}
			})
			.done(function(data){
				var obj = $.parseJSON(data);
				if(obj.result){
					$('.remove_photo').hide();
					$('.photo_actuelle').find('img').remove();
					$('.photo_actuelle_label').css('paddingTop','0');
					$('.photo_actuelle').append('<b>Aucune photo de profil n\'a été définie.</b>');
				}
			});
		}
	});
});
@endif
</script>
@endpush
