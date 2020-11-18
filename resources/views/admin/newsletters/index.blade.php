@extends('admin.layouts.app')

@section('title')
	Newsletters
@stop

@section('content')
	<!-- Blank Header -->
	<div class="content-header">
		<div class="header-section">
			<h1>
				<i class="fa fa-newspaper"></i>Newsletters<br>
				<small>Listing</small>
			</h1>
		</div>
	</div>
	<!-- END Blank Header -->
	<ul class="breadcrumb breadcrumb-top">
		<li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
		<li>Newsletters</li>
	</ul>

	@include('errors.list')

	<p>
		<a class="btn btn-primary" id="btnAddNewsletter"><i class="fas fa-plus"></i>&nbsp;Ajouter un abonnement</a>
		<a class="btn btn-default" href="{{ route('newsletters.export') }}"><i class="fas fa-download"></i>&nbsp;Export CSV</a>
	</p>
	@if(empty($newslettersCount))
		<p>
			<em>Pas d'inscriptions à la newsletter pour l'instant.</em>
		</p>
	@else
		<div class="block full">
			<div class="block-content-full">
				<div class="table-responsive">
					<table id="newsletters" class="table table-vcenter table-striped table-bordered table-hover" width="100%">
						<thead>
						<tr>
							<th class="text-center">ID</th>
							<th>Email</th>
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
	@include('admin.newsletters.modal-add')
@stop

@push('scripts')
	<script>
		$(function(){
			var delete_entry = function(){
				$('.delete').on('click', function(e){
					e.preventDefault();
					var $entry = $(this).data('entry');
					bootbox.confirm("Êtes-vous sur ?", function(result) {
						if(result){
							$('#delete-'+$entry).submit();
						}
					});
				});
			};

			/* Initialize Datatables */
			$('#newsletters').DataTable({
				"processing": true,
				"serverSide": true,
				"columnDefs": [ { "targets": [3], "orderable": false } ],
				"iDisplayLength": 50,
				"order": [[2, "desc"]],
				"ajax": "{{ route('newsletters_ajaxlisting') }}",
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
<<<<<<< HEAD
			$('.dataTables_filter input').addClass('form-control').attr('placeholder', 'Rechercher');
			$('.dataTables_processing').css({
				position: 'absolute',
				marginLeft: '100px',
				marginTop: '9px'
			});
=======
>>>>>>> master

			$('#btnAddNewsletter').on('click', function (e) {
				e.preventDefault();
				$('#modalAddNewsletter').modal('show');
			})
		});
	</script>
@endpush
