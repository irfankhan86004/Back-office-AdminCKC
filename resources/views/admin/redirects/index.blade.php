@extends('admin.layouts.app')

@section('title')
Redirections
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-map-signs"></i>Redirections<br>
            <small>Listing</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li>Redirections</li>
</ul>

<p>
    <a class="btn btn-primary" href="{{ route('redirects.create') }}"><i class="fa fa-plus"></i>&nbsp;&nbsp;Ajouter une redirection</a>
    @if(!empty($redirectionsCount))
		<a class="btn btn-default" href="{{ route('redirects_export') }}"><i class="fas fa-download"></i>&nbsp;Export CSV</a>
    @endif
</p>
@if(empty($redirectionsCount))
<p>
    <em>Pas de redirections.</em>
</p>
@else
<div class="block full">
    <div class="block-content-full">
        <div class="table-responsive">
            <table id="redirects" class="table table-vcenter table-striped table-bordered table-hover" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Active</th>
                        <th>Type</th>
                        <th>Origine</th>
                        <th>Destination</th>
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
			bootbox.confirm("Are you sure ?", function(result) {
				if(result){
					$('#delete-'+$entry).submit();
				}
			});
		});
	};

    /* Initialize Datatables */
    $('#redirects').dataTable({
        "processing": true,
        "serverSide": true,
		"columnDefs": [ { "targets": [4,5], "orderable": false } ],
		"iDisplayLength": 50,
		"order": [[0, "desc"]],
		"stateSave": true,
		"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
		"ajax": "{{ route('redirects_ajaxlisting') }}",
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
