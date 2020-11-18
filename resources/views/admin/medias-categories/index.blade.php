@extends('admin.layouts.app')

@section('title')
Catégorie de média
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-folder-open-o"></i>Catégorie de média<br>
            <small>Listing</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li>Catégorie de média</li>
</ul>
<p>
    <a class="btn btn-primary" href="{{ route('categories-medias.create') }}"><i class="fa fa-plus"></i> Ajouter une catégorie de média</a>
</p>
@if(!count($categories))
<p>
    <em>Pas de catégorie de média.</em>
</p>
@else
<div class="block full">
    <div class="block-content-full">
        <div class="table-responsive">
            <table id="categories" class="table table-vcenter table-striped table-bordered table-hover" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Nom</th>
                        <th class="text-center">Media</th>
                        <th>Date de création</th>
                        <th>Par</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category_array)
                    @php $c = $category_array['category'] @endphp
                    <tr>
                        <td class="text-center">{{ $c->id }}</td>
                        <td><a href="{{ route('categories-medias.edit',[$c->id]) }}">{{ $category_array['label'] }}</a></td>
                        <td class="text-center"><span class="label label-info">{{ $c->medias->count() }}</span></td>
                        <td>{{ format_date($c->created_at) }}</td>
                        <td>{{ $c->admin->last_name.' '.$c->admin->first_name }}</td>
                        <td>
                            <div class="text-right">
                                {!! Form::open(['route' => ['categories-medias.destroy', $c->id], 'method' => 'DELETE', 'id' => 'delete-'.$c->id]) !!}
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('categories-medias.edit', [$c->id]) }}" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Details"><i class="hi hi-search"></i></a>
                                        <a href="#" type="submit" class="btn btn-danger delete" data-entry="{{ $c->id }}" data-toggle="tooltip" data-original-title="Supprimer"><i class="gi gi-remove_2"></i></a>
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@stop

@push('scripts')
<script>
$(function(){
    $('.delete').on('click', function(e){
        e.preventDefault();
        var $entry = $(this).data('entry');
        bootbox.confirm("Êtes-vous sûr ?", function(result) {
            if(result==true)
                $('#delete-'+$entry).submit();
        });
    });

    /* Initialize Datatables */
    $('#categories').dataTable({
        "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 5 ] } ],
        "iDisplayLength": 50,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tout"]],
        "aaSorting": [],
        "stateSave": true,
    });
});
</script>
@endpush
