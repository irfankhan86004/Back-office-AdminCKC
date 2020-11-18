<div class="form-group<?php if ($errors->has('name')) {
    echo ' has-error';
} ?>">
    {!! Form::label('name', 'Nom *', ['class'=>'col-md-3 control-label']) !!}
    <div class="col-md-9">
	    {!! Form::text('name', null, ['class'=>'form-control']) !!}
        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
        <span class="help-block"><i class="fa fa-warning"></i> Vous pouvez utiliser des lettres et des chiffres séparés de "-" ou "_" (aucun espace). Attention à ne pas modifier l'extension en fin de nom (.extension)</span>
	</div>
</div>
<div class="form-group<?php if ($errors->first('file')) {
    echo ' has-error';
} ?>" style="border-bottom:none;">
    {!! Form::label('file', 'Modifier le fichier', ['class'=>'col-md-3 control-label']) !!}
    <div class="col-md-9">
    	<div class="fileinput fileinput-new" data-provides="fileinput">
			<span class="btn btn-default btn-file">
				<span class="fileinput-new">Choisir un fichier</span>
				<span class="fileinput-exists">Modifier</span>
				<input type="file" name="file">
			</span>
			<span class="fileinput-filename"></span>
			<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float:none">&times;</a>
		</div>
		<span class="help-block">Formats autorisés : pdf, doc, docx, xls, xlsx, zip, rar, targ</span>
     	{!! $errors->first('file', '<span class="help-block">:message</span>') !!}
    </div>
</div>
<?php // Tabs?>
<ul class="nav nav-tabs push" data-toggle="tabs">
	@foreach($languages as $language)
	<li><a href="#tab-{{ $language->short }}"><i class="fa fa-font"></i> {{ $language->name }}</a></li>
	@endforeach
</ul>
<div class="tab-content">
    @foreach($languages as $language)
    	<div class="tab-pane" id="tab-{{ $language->short }}">
			<div class="form-group<?php if ($errors->has('title_'.$language->short)) {
    echo ' has-error';
} ?>">
			    {!! Form::label('title_'.$language->short, 'Title', ['class'=>'col-md-3 control-label']) !!}
			    <div class="col-md-9">
				    {!! Form::text('title_'.$language->short, null, ['class'=>'form-control']) !!}
			        {!! $errors->first('title_'.$language->short, '<span class="help-block">:message</span>') !!}
				</div>
			</div>
			<div class="form-group<?php if ($errors->has('desc_'.$language->short)) {
    echo ' has-error';
} ?>">
			    {!! Form::label('desc_'.$language->short, 'Description', ['class'=>'col-md-3 control-label']) !!}
			    <div class="col-md-9">
				    {!! Form::textarea('desc_'.$language->short, null, ['rows'=>'3', 'class'=>'form-control']) !!}
			        {!! $errors->first('desc_'.$language->short, '<span class="help-block">:message</span>') !!}
				</div>
			</div>
    	</div>
    @endforeach
</div>

<div class="form-group form-actions">
    <div class="col-md-9 col-md-offset-3">
        <a class="btn btn-default" href="{!! route('files.index') !!}"><i class="fa fa-angle-left"></i> Retour</a>
        <button type="submit" class="btn btn-primary">{!! $submitButtonText !!} <i class="fa fa-angle-right"></i></button>
    </div>
</div>

@push('scripts')
<script>
$('.nav-tabs a:first').tab('show');
</script>
@endpush