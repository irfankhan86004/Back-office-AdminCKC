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
            <small>Définir l'ordre</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('menu.index') }}">Menu du site</a></li>
    <li>Ordre</li>
</ul>

<p>
    <a class="btn btn-default" href="{{ route('menu.index') }}"><i class="fa fa-angle-left"></i> Retour au listing</a>
</p>
@if(!$menus || count($menus)===0)
<p>
    <em>Aucun menu.</em>
</p>
@else
<div class="row">
	<div class="col-sm-12 column sortable_menus">
		@foreach($menus as $m)
		<div class="block animation-fadeInQuick" data-position="{!!$m->position!!}" data-id="{!!$m->id!!}" style="cursor:move;">
		    <div class="block-title" style="border:none;"></div>
		    <div class="row">
		    	<div class="col-md-4 text-center"><p><i class="fa fa-arrows"></i> {!!$m->position!!}</h2></p></div>
		    	<div class="col-md-4 text-center"><p><a href="{!! route('menu.edit', array($m->id)) !!}"><b>{!!$m->id!!}</b> - {!! ucfirst($m->getAttr('name')) !!}</a></p></div>
		    	<div class="col-md-4 text-center"><p><b>Url</b> : {!!(($m->lien_url()) ? $m->lien_url() : '-')!!}</p></div>
		    </div>
		</div>
		@endforeach
	</div>
</div>
@endif
@stop

@push('scripts')
<script>
$('.sortable_menus').sortable({
	placeholder: 'draggable-placeholder',
	opacity: 0.4,
	start: function(event, ui){
        ui.placeholder.css('height', ui.item.outerHeight());
    },
	stop: function(event, ui) {
		var children = $('.sortable_menus').children().size();
		var i = 1;
		$('.sortable_menus > .block').each(function(){
			$.ajax({
				type: "POST",
				url: "{!! route('setordermenus') !!}",
				data: {
					menu_id: $(this).data('id'),
					position: i,
					_token: "{{ csrf_token() }}",
				}
			});
			i++;
		});
	}
});
</script>
@endpush