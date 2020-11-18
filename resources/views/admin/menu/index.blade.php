@extends('admin.layouts.app')

@section('title')
Menu du site
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-font"></i>Menu du site<br>
            <small>Listing</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li>Menu du site</li>
</ul>

<p>
    <a class="btn btn-primary" href="{{ route('menu.create') }}"><i class="fa fa-plus"></i> Ajouter un menu</a>
    @if($menu && count($menu->children)>=1)
    <a class="btn btn-default" href="{{ route('admin.menu.order') }}">Définir l'ordre <i class="fa fa-sort"></i></a>
    @endif
</p>
@if(!$menu || count($menu->children)===0)
<p>
    <em>Aucun menu.</em>
</p>
@else
<div class="block full">
    <div class="block-content-full">
        <div class="table-responsive">
            <table id="menu" class="table table-vcenter table-striped table-bordered table-hover" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Nom</th>
                        <th class="text-center"><i class="fa fa-image"></i></th>
                        <th>Position</th>
                        <th>Page / Article / Lien</th>
                        <th>Créé le</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
				{{ $menu->arbo($menu) }}
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
		bootbox.confirm("Êtes-vous sur?", function(result) {
			if(result)
				$('#delete-' + $entry).submit();
		});
	});

    /* Initialize Datatables */
    $('#menu').dataTable({
        "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 6 ] } ],
        "iDisplayLength": 50,
        "aLengthMenu": [[50, 100, -1], [50, 100, "All"]],
        "aaSorting": []
    });
});
</script>
@endpush
