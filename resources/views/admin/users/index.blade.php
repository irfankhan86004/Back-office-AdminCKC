@extends('admin.layouts.app')

@section('title')
Utilisateurs
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-user"></i>Utilisateurs<br>
            <small>Listing</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li>Utilisateurs</li>
</ul>

<p>
    <a class="btn btn-primary" href="{{ route('users.create') }}"><i class="fa fa-plus"></i> Ajouter un utilisateur</a>
</p>

@if(empty($users))
<p>
    <em>Aucun utilisateur.</em>
</p>
@else

<div class="block full">
    <div class="block-content-full">
        <div class="table-responsive">
            <table id="users" class="table table-vcenter table-striped table-bordered table-hover" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Utilisateur</th>
                        <th>Date de création</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endif
@stop
@push('scripts')
<script>
$(function(){
    var delete_entry = function(){
        $('.delete').on('click', function(e){
            e.preventDefault();
            var $entry = $(this).data('entry');
            bootbox.confirm("Êtes-vous sur ?", function(result) {
                if(result==true)
                    $('#delete-'+$entry).submit();
            });
        });
    };

    /* Initialize Datatables */
    var usersDatatable = $('#users').DataTable({
        "processing": true,
        "serverSide": true,
        "columnDefs": [ { "targets": [3], "orderable": false } ],
        "iDisplayLength": 50,
        "order": [[0, "desc"]],
        "ajax": "{{ route('users_ajaxlisting') }}",
        "drawCallback": function(settings){
            var api = this.api();
            $('[data-toggle="tooltip"]', api.table().container()).each(function () {
               $(this).attr('title', $(this).data('original-title'));
            });
            $('[data-toggle="tooltip"]', api.table().container()).tooltip({
               container: 'body'
            });
            delete_entry();
        }
    });
});
</script>
@endpush
