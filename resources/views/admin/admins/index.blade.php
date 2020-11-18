@extends('admin.layouts.app')

@section('title')
Administrateurs
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-lock"></i>Administrateurs<br>
            <small>Listing</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li>Administrateurs</li>
</ul>

<p>
    <a class="btn btn-primary" href="{{ route('admins.create') }}"><i class="fa fa-plus"></i> Ajouter un administrateur</a>
</p>
@if(count($admins)===0)
<p>
    <em>Aucun administrateur.</em>
</p>
@else
<div class="block full">
    <div class="block-content-full">
        <div class="table-responsive">
            <table id="admins" class="table table-vcenter table-striped table-bordered table-hover" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center"><i class="fa fa-user"></i></th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Date de création</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($admins as $c)
                    <tr>
                        <td class="text-center">{{ $c->id }}</td>
                        <td class="text-center"><a href="{{ route('admins.edit', array($c->id)) }}"><img src="{{ $c->gravatar($c->email, 60) }}?t={{time()}}" class="img-rounded"/></a></td>
                        <td>{{ $c->last_name }}</td>
                        <td>{{ $c->first_name }}</td>
                        <td>{{ $c->email }}</td>
                        <td><span class="hide">{{ strtotime($c->created_at) }}</span>{{ format_date($c->created_at) }}</td>
                        <td class="text-right">
                            {!! Form::open(['method' => 'DELETE', 'route' => ['admins.destroy', $c->id], 'id'=>'delete-'.$c->id]) !!}
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admins.edit', array($c->id)) }}" data-toggle="tooltip" title="" class="btn btn-default edit-admin" data-original-title="Details"><i class="hi hi-search"></i></a>
                                <a href="#" type="submit" class="btn btn-danger delete" data-entry="{{ $c->id }}" data-toggle="tooltip" data-original-title="Delete"><i class="gi gi-remove_2"></i></a>
                            </div>
                            {!! Form::close() !!}
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
        bootbox.confirm("Are you sure?", function(result) {
            if(result==true)
                $('#delete-'+$entry).submit();
        });
    });

    /* Initialize Datatables */
    $('#admins').dataTable({
        "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 6 ] } ],
        "iDisplayLength": 50,
        "aLengthMenu": [[50, 100, -1], [50, 100, "All"]],
        "aaSorting": [[ 0, "desc" ]]
    });
});
</script>
@endpush
