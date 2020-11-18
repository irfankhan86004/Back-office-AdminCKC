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
            <small>Définir l'ordre</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('carousel.index') }}">Carousel du site</a></li>
    <li>Ordre</li>
</ul>

<p>
    <a class="btn btn-default" href="{{ route('carousel.index') }}"><i class="fa fa-angle-left"></i> Retour au listing</a>
</p>
@if(!$carousels || count($carousels)===0)
<p>
    <em>Aucun slide.</em>
</p>
@else
<div class="row">
	<div class="col-sm-12 column sortable_carousels">
		@foreach($carousels as $c)
		<div class="block animation-fadeInQuick" data-position="{!!$c->position!!}" data-id="{!!$c->id!!}" style="cursor:move;">
		    <div class="block-title" style="border:none;"></div>
		    <div class="row">
		    	<div class="col-md-3 text-center"><p style="padding-top:20px;"><i class="fa fa-arrows"></i> {!!$c->position!!}</h2></p></div>
		    	<div class="col-md-6 text-center">
			    	@if($c->getPicture())
                    <p><a href="{{ $c->getPicture() . '?t='.time() }}" data-toggle="lightbox-image"><img src="{{ $c->getPicture(120, 60) . '?t='.time() }}" class="img-rounded"/></a></p>
                    @else
                    <p style="padding:16px 0 10px;"><i class="fa fa-ban fa-2x text-danger"></i></p>
                    @endif
				</div>
		    	<div class="col-md-3 text-center"><p style="padding-top:20px;"><span class="label label-{!!($c->published==1 ? 'success' : 'danger')!!}">{!!($c->published==1 ? 'Activée' : 'Désactivée')!!}</span></p></div>
		    </div>
		</div>
		@endforeach
	</div>
</div>
@endif
@stop

@push('scripts')
<script>
$('.sortable_carousels').sortable({
	placeholder: 'draggable-placeholder',
	opacity: 0.4,
	start: function(event, ui){
        ui.placeholder.css('height', ui.item.outerHeight());
    },
	stop: function(event, ui) {
		var children = $('.sortable_carousels').children().size();
		var i = 1;
		$('.sortable_carousels > .block').each(function(){
			$.ajax({
				type: "POST",
				url: "{!! route('setordercarousel') !!}",
				data: {
					carousel_id: $(this).data('id'),
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
