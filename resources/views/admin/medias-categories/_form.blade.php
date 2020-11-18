<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
    {!! Form::label('name', 'Nom *', ['class'=>'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::text('name', null, ['class'=>'form-control']) !!}
        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('parent_id') ? ' has-error' : '' }}">
    {!! Form::label('parent_id', 'Catégorie parente', ['class'=>'col-md-3 control-label']) !!}
    <div class="col-md-4">
        {!! Form::select('parent_id', $select, null, ['class'=>'form-control select-chosen']) !!}
        {!! $errors->first('parent_id', '<span class="help-block">:message</span>') !!}
    </div>
</div>
@if(isset($category) && isset($other_categories) && count($other_categories))
<div class="form-group">
    {!! Form::label('positions', 'Ordre des catégories du même niveau', ['class'=>'col-md-3 control-label']) !!}
    <div class="col-md-4">
        <div class="alert alert-info">L'ordre des catégories peut être modifié par glisser-déposer</div>
        <ul class="list-group" style="margin-bottom:0">
            @foreach($other_categories as $c)
            <li class="list-group-item categories-listing{{ $c->id == $category->id ? ' active' : '' }}" style="cursor:move" data-id="{{ $c->id }}">{{ $c->name }}</li>
            @endforeach
        </ul>
    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function(){
        $('.list-group').sortable({
            stop: function( event, ui ) {
                var categories = [];
                $('.categories-listing').each(function(){
                    categories.push($(this).attr('data-id'));
                });
                $.post({
                    url: "{{ route('categories_medias_order') }}",
                    data:{
                        _token: "{{ csrf_token() }}",
                        categories: categories,
                    }
                })
            }
        });
    });
</script>
@endpush
@endif
<div class="form-group form-actions">
    <div class="col-md-9 col-md-offset-3">
        <a class="btn btn-default" href="{!! route('categories-medias.index') !!}"><i class="fa fa-angle-left"></i> Back</a>
        <button type="submit" class="btn btn-primary">{!! $submitButtonText !!} <i class="fa fa-angle-right"></i></button>
    </div>
</div>
