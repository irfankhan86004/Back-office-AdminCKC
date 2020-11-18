@extends('admin.layouts.app')

@section('title')
SMS
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-search"></i>SMS<br>
            <small>Listing</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li>SMS</li>
</ul>

@if(empty($sms))
<p>
    <em>Aucun sms.</em>
</p>
@else
<div class="block full">
    <div class="block-content-full">
        <div class="table-responsive">
            <table id="sms" class="table table-vcenter table-striped table-bordered table-hover" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Type</th>
                        <th>Sujet</th>
                        <th>Destinataire</th>
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
    var usersDatatable = $('#sms').DataTable({
        "processing": true,
        "serverSide": true,
        "columnDefs": [ { "targets": [5], "orderable": false } ],
        "iDisplayLength": 50,
        "order": [[0, "desc"]],
        "ajax": "{{ route('sms_ajaxlisting') }}",
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
