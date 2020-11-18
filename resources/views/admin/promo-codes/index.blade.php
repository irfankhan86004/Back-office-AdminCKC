@extends('admin.layouts.app')

@section('title')
Codes promo
@stop

@section('content')

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-euro-sign"></i>Codes promo<br>
            <small>Listing</small>
        </h1>
    </div>
</div>

<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li>Codes promo</li>
</ul>

<p>
    <a class="btn btn-primary" href="{{ route('promo-codes.create') }}"><i class="fa fa-plus"></i> Ajouter un code promo</a>
</p>

@if(empty($promoCodes))
<p>
    <em>Aucun code promo.</em>
</p>
@else

<div class="block full">
    <div class="block-content-full">
        <div class="table-responsive">
            <table id="promoCodes" class="table table-vcenter table-striped table-bordered table-hover" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Campagne</th>
                        <th>Code</th>
                        <th>Valeur</th>
                        <th>Utilisations</th>
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
                if(result)
                    $('#delete-'+$entry).submit();
            });
        });
    };

    /* Initialize Datatables */
    $('#promoCodes').dataTable({
        "processing": true,
        "serverSide": true,
        "columnDefs": [ { "targets": [6], "orderable": false } ],
        "iDisplayLength": 50,
        "order": [[0, "desc"]],
        "ajax": "{{ URL::route('promo_codes_ajaxlisting') }}",
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
