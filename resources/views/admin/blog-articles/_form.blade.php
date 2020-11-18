<ul class="nav nav-tabs push" data-toggle="tabs">
	<li class="active"><a href="#tab1">Caractéristiques</a></li>
	@foreach($languages as $language)
	<li><a href="#tab-{{ $language->short }}"><i class="fa fa-font"></i>&nbsp;&nbsp;{{ $language->name }}</a></li>
	@endforeach
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="tab1">
		@if(isset($post) && $post->admin)
		<div class="form-group">
			{!! Form::label('admin', 'Admin', ['class'=>'col-md-3 control-label']) !!}
			<div class="col-md-4">
				{!! Form::text('admin', $post->admin->displayName(), ['class'=>'form-control', 'disabled' => 'disabled']) !!}
			</div>
		</div>
		@endif
		<div class="form-group{{ $errors->has('published') ? ' has-error' : '' }}">
		    {!! Form::label('published', 'Publié', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-9">
			    <label class="switch switch-primary">
			    	{!! Form::checkbox('published', 1, isset($post_arr) && $post_arr['published']==1) !!}
			    	<span></span>
			    </label>
		    </div>
		</div>
		<div class="form-group{{ $errors->has('featured') ? ' has-error' : '' }}">
		    {!! Form::label('featured', 'À la une', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-9">
			    <label class="switch switch-primary">
			    	{!! Form::checkbox('featured', 1, isset($post_arr) && $post_arr['featured']==1) !!}
			    	<span></span>
			    </label>
		    </div>
		</div>
		<div class="form-group{{ $errors->has('categories') ? ' has-error' : '' }}">
		    {!! Form::label('categories', 'Catégorie(s) *', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-4">
			    {!! Form::select('categories[]', $categories, null, ['class'=>'form-control select-chosen', 'multiple']) !!}
		        {!! $errors->first('categories', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		<div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
		    {!! Form::label('date', 'Date de l\'article', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-4">
		        {!! Form::text('date', Request::old('date',isset($post) ? $post->date : date('d/m/Y')), ['class'=>'datepicker form-control']) !!}
		        <div class="input-group bootstrap-timepicker">
			        <div class="bootstrap-timepicker-widget dropdown-menu">
				        <table>
					        <tbody>
						        <tr>
							        <td><a href="#" data-action="incrementHour"><i class="fa fa-chevron-up"></i></a></td>
							        <td class="separator">&nbsp;</td>
							        <td><a href="#" data-action="incrementMinute"><i class="fa fa-chevron-up"></i></a></td>
							        <td class="separator">&nbsp;</td>
							        <td><a href="#" data-action="incrementSecond"><i class="fa fa-chevron-up"></i></a></td>
							    </tr>
								<tr>
									<td><input type="text" class="form-control bootstrap-timepicker-hour" maxlength="2"></td>
									<td class="separator">:</td><td><input type="text" class="form-control bootstrap-timepicker-minute" maxlength="2"></td>
									<td class="separator">:</td><td><input type="text" class="form-control bootstrap-timepicker-second" maxlength="2"></td>
								</tr>
								<tr>
									<td><a href="#" data-action="decrementHour"><i class="fa fa-chevron-down"></i></a></td>
									<td class="separator"></td><td><a href="#" data-action="decrementMinute"><i class="fa fa-chevron-down"></i></a></td>
									<td class="separator">&nbsp;</td><td><a href="#" data-action="decrementSecond"><i class="fa fa-chevron-down"></i></a></td>
								</tr>
							</tbody>
						</table>
					</div>
					{!! Form::text('heure', null, ['class'=>'input-timepicker24 form-control', 'id'=>'example-timepicker24']) !!}
					<span class="input-group-btn">
						<a href="javascript:void(0)" class="btn btn-primary"><i class="fa fa-clock-o"></i></a>
					</span>
				</div>
				{!! $errors->first('date', '<span class="help-block">:message</span>') !!}
		    </div>
		</div>
		<div class="form-group{{ $errors->has('date_hide') ? ' has-error' : '' }}">
		    {!! Form::label('date_hide', 'Ne pas afficher la date sur le site', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-9">
			    <label class="switch switch-primary"><input type="checkbox" name="date_hide" value="1" <?php echo (isset($post_arr) && $post_arr['date_hide']==1) ? 'checked' : ''; ?>><span></span></label>
		    </div>
		</div>
		<div class="form-group{{ $errors->has('written_by') ? ' has-error' : '' }}">
		    {!! Form::label('written_by', 'Rédigé par', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-4">
			    {!! Form::text('written_by', Request::old('written_by',isset($post) ? $post->written_by : Auth::guard('admin')->user()->displayName()), ['class'=>'form-control']) !!}
		        {!! $errors->first('written_by', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		<div class="form-group{{ $errors->has('written_by') ? ' has-error' : '' }}">
		    {!! Form::label('Tag', 'Tag', ['class'=>'col-md-3 control-label']) !!}
			
		    <div class="col-md-4">
				<input type="text" name="meta_tag" placeholder="Meta Tag" class="typeahead tm-input input-field form-control tm-input-info"/>
			</div>
		</div>
    </div>
    @foreach($languages as $language)
    	@include('admin.blog-articles._lang_form')
    @endforeach
</div>
@if(!isset($post) && (Auth::guard('admin')->user()->hasPermission(6)))
<div class="form-group">
    <div class="col-md-9 col-md-offset-3">
	    <a href="#" class="btn btn-info btn-files">Enregistrer les informations saisies et y associer des médias</a>
		{{ Form::hidden('upload_medias',0) }}
    </div>
</div>
@endif
<div class="form-group form-actions">
    <div class="col-md-9 col-md-offset-3">
        <a class="btn btn-default" href="{!! route('articles-blog.index') !!}"><i class="fa fa-angle-left"></i> Retour</a>
        <button type="submit" class="btn btn-primary">{!! $submitButtonText !!} <i class="fa fa-angle-right"></i></button>
    </div>
</div>

@push('scripts')
<script src="{{ asset('admin1/js/helpers/ckeditor/ckeditor.js') }}"></script>
<script>
	$(document).ready(function(){

        $('#date_featured').click(function() {
            $('#date-hidden').toggle(this.checked);
        });
        if($('#date_featured').prop('checked')) {
            $('#date-hidden').toggle(this.checked);
        }

        $('.btn-files').click(function(e){
			e.preventDefault();
			$('input[name="upload_medias"]').val(1);
			$('form.create-blog-post-form').submit();
		});

	});
</script>
@endpush
