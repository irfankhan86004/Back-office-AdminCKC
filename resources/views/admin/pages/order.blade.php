@extends('admin.layouts.app')

@section('title')
Pages du site
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-font"></i>Pages du site<br>
            <small>Définir l'ordre</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('pages.index') }}">Pages du site</a></li>
    <li>Ordre</li>
</ul>

<p>
    <a class="btn btn-default" href="{{ route('pages.index') }}"><i class="fa fa-angle-left"></i> Retour au listing</a>
</p>
@if(!$pages || count($pages)===0)
<p>
    <em>Aucune page.</em>
</p>
@else
<div class="row">
	<div class="col-sm-12 column sortable_pages"></div>
</div>
@endif
@stop

@push('scripts')
<script>
var sortable_pages = function (){
	$('.sortable_pages').sortable({
		placeholder: 'draggable-placeholder',
		opacity: 0.4,
		start: function(event, ui){
	        ui.placeholder.css('height', ui.item.outerHeight());
	    },
		stop: function(event, ui) {
			var children = $('.sortable_pages').children().size();
			var i = 1;
			$('.sortable_pages > .block').each(function(){
				$.ajax({
					type: "POST",
					url: "{!! route('setorderpages') !!}",
					data: {
						page_id: $(this).data('id'),
						position: i,
						_token: "{{ csrf_token() }}",
					}
				});
				i++;
			});
		}
	});
};
$(document).ready(function(){
	$.ajax({
		type: "POST",
		url: "{!! route('pages_order_ajaxlisting') !!}",
		data: {
			_token: "{{ csrf_token() }}",
		},
		success : function(response){
			var obj = jQuery.parseJSON(response);
			$('.sortable_pages').empty().html(obj.results);
			sortable_pages();
		}
	});
});
</script>
@endpush