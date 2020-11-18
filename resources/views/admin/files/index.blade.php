@extends('admin.layouts.app')

@section('title')
Fichiers du site
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-files-o"></i>Fichiers du site<br>
            <small>Listing</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li>Fichiers du site</li>
</ul>

@if($files===0)
<p>
    <em>Aucun fichier.</em>
</p>
@else
<div class="block full">
    <div class="block-content-full">
        <div class="table-responsive">
            <table id="fichiers" class="table table-vcenter table-striped table-bordered table-hover" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Type</th>
                        <th>Nom</th>
                        <th>Extension</th>
                        <th>Poids</th>
                        <th>Model</th>
                        <th>Créée le</th>
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
			bootbox.confirm("Êtes-vous sur?", function(result) {
				if(result==true)
					$('#delete-'+$entry).submit();
			});
		});
	};

    /* Initialize Datatables */
    $('#fichiers').dataTable({
        "processing": true,
        "serverSide": true,
		"columnDefs": [ { "targets": [1,7], "orderable": false } ],
		"iDisplayLength": 50,
		"order": [[0, "desc"]],
		"ajax": "{{ URL::route('files_ajaxlisting') }}",
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
