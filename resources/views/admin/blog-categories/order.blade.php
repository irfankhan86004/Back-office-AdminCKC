@extends('admin.layouts.app')

@section('title')
Catégories du blog
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-rss"></i>Catégories du blog<br>
            <small>Définir l'ordre</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('categories-blog.index') }}">Catégories du blog</a></li>
    <li>Ordre</li>
</ul>

<p>
    <a class="btn btn-default" href="{{ route('categories-blog.index') }}"><i class="fa fa-angle-left"></i> Retour au listing</a>
</p>
@if(!$categories || count($categories)===0)
<p>
    <em>Aucune catégorie.</em>
</p>
@else
<div class="row">
	<div class="col-sm-12 column sortable_categories">
		@foreach($categories as $c)
		<div class="block animation-fadeInQuick" data-position="{!!$c->position!!}" data-id="{!!$c->id!!}" style="cursor:move;">
		    <div class="block-title" style="border:none;"></div>
		    <div class="row">
		    	<div class="col-md-4 text-center"><p><i class="fa fa-arrows"></i> {{ $c->position }}</p></div>
		    	<div class="col-md-4 text-center"><p><a href="{!! route('categories-blog.edit', array($c->id)) !!}"><b>{!!$c->id!!}</b> - {!! ucfirst($c->getAttr('name')) !!}</a></p></div>
		    	<div class="col-md-4 text-center"><p><b>Articles</b> : <span class="label label-default">{{ $c->posts->count() }}</span></p></div>
		    </div>
		</div>
		@endforeach
	</div>
</div>
@endif
@stop

@push('scripts')
<script>
$('.sortable_categories').sortable({
	placeholder: 'draggable-placeholder',
	opacity: 0.4,
	start: function(event, ui){
        ui.placeholder.css('height', ui.item.outerHeight());
    },
	stop: function(event, ui) {
		var children = $('.sortable_categories').children().size();
		var i = 1;
		$('.sortable_categories > .block').each(function(){
			$.ajax({
				type: "POST",
				url: "{!! route('set_order_categories_blog') !!}",
				data: {
				    _token: "{{ csrf_token() }}",
					category_id: $(this).data('id'),
					position: i
				}
			});
			i++;
		});
	}
});
</script>
@endpush
