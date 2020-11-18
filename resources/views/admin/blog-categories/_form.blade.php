<div class="form-group{{ $errors->has('position') ? ' has-error' : '' }}" style="border-bottom:none;">
    {!! Form::label('position', 'Position *', ['class'=>'col-md-3 control-label']) !!}
    <div class="col-md-2">
	    {!! Form::text('position', null, ['class'=>'form-control']) !!}
        {!! $errors->first('position', '<span class="help-block">:message</span>') !!}
	</div>
</div>
<ul class="nav nav-tabs push" data-toggle="tabs">
	@foreach($languages as $language)
	<li><a href="#tab-{{ $language->short }}"><i class="fa fa-font"></i>&nbsp;&nbsp;{{ $language->name }}</a></li>
	@endforeach
</ul>
<div class="tab-content">
    @foreach($languages as $language)
    	@include('admin.blog-categories._lang_form')
    @endforeach
</div>

<div class="form-group form-actions">
    <div class="col-md-9 col-md-offset-3">
        <a class="btn btn-default" href="{!! route('categories-blog.index') !!}"><i class="fa fa-angle-left"></i> Retour</a>
        <button type="submit" class="btn btn-primary">{!! $submitButtonText !!} <i class="fa fa-angle-right"></i></button>
    </div>
</div>

@push('scripts')
<script>
$('.nav-tabs a:first').tab('show');
</script>
@endpush