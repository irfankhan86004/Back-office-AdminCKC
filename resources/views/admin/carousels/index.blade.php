@extends('admin.layouts.app')

@section('title')
Carousel du site
@stop

@section('content')

<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-picture"></i>Carousel du site<br>
            <small>Listing</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li>Carousel du site</li>
</ul>

<p>
    <a class="btn btn-primary" href="{{ route('carousel.create') }}"><i class="fa fa-plus"></i> Ajouter un slide</a>
    @if($carousels && count($carousels)>=1 && isset($_GET['language_id']))
        <a class="btn btn-default" href="{{ route('admin.carousel.order', ['language_id' => $_GET['language_id']]) }}">Définir l'ordre <i class="fa fa-sort"></i></a>
    @elseif($carousels && count($carousels)>=1 && count($languages) == 1)
        <a class="btn btn-default" href="{{ route('admin.carousel.order', ['language_id' => $languages[0]['id']]) }}">Définir l'ordre <i class="fa fa-sort"></i></a>
    @endif

</p>
@if($carousels->isEmpty())
<p>
    <em>Aucun slide ajouté.</em>
</p>
@else
<div class="block full">
    <div class="block-content-full">
        <div class="table-responsive">
            <table id="carousels" class="table table-vcenter table-striped table-bordered table-hover" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center"><i class="gi gi-picture"></i></th>
                        <th>Position</th>
                        <th>Publié</th>
                        <th>Créé le</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($carousels as $c)
                    <tr>
                        <td class="text-center">{{ $c->id }}</td>
                        <td class="text-center">
	                        @if($c->getPicture())
	                        <a href="{{ route('carousel.edit', array($c->id)) }}"><img src="{{ $c->getPicture(80,80) }}" class="img-rounded"/></a>
	                        @else
	                        <i class="fa fa-ban fa-2x text-danger"></i>
	                        @endif
	                    </td>
                        <td><span class="label label-primary">{{ $c->position }}</span></td>
                        <td><span class="label label-{!!($c->published==1 ? 'success' : 'danger')!!}">{!!($c->published==1 ? 'Activée' : 'Désactivée')!!}</span></td>
                        <td>{{ format_date($c->created_at) }}</td>
                        <td class="text-right">
	                        {!! Form::open(['method' => 'DELETE', 'route' => ['carousel.destroy', $c->id], 'id'=>'delete-'.$c->id]) !!}
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('carousel.edit', array($c->id)) }}" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Details"><i class="hi hi-search"></i></a>
								<a href="#" type="submit" class="btn btn-danger delete" data-entry="{{ $c->id }}" data-toggle="tooltip" data-original-title="Supprimer"><i class="gi gi-remove_2"></i></a>
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
		bootbox.confirm("Êtes-vous sur?", function(result) {
			if(result==true)
				$('#delete-'+$entry).submit();
		});
	});

    /* Initialize Datatables */
    $('#carousels').dataTable({
        "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 5 ] } ],
        "iDisplayLength": 50,
        "aLengthMenu": [[50, 100, -1], [50, 100, "All"]],
        "aaSorting": [[ 2, "asc" ]]
    });

    $('#sortSubsidiariesInput').change(function(){
        var language_id = $(this).val();
        var url_parameter = '';
        if(language_id != 0){
            url_parameter = '?language_id='+language_id;
        }
        window.location.href = "{{ route('carousel.index') }}"+url_parameter;
    });
});
</script>
@endpush
