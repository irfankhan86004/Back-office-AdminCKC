@extends('admin.layouts.app')

@section('title')
Modifier un article du blog
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-rss"></i>{{ $post->getAttr('name') }} <small>#{{ $post->id }}</small><br>
            <small>Modifier un article du blog</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('articles-blog.index') }}">Articles du blog</a></li>
    <li>{{ $post->getAttr('name') }}</li>
    <li>Modifier un article</li>
</ul>

@include('errors.list')

<!-- Example Block -->
<div class="block">
    <!-- Example Title -->
    <div class="block-title">
        <h2>{{ $post->getAttr('name') }}</h2>
    </div>
    <!-- END Example Title -->

    {!! Form::model($post_arr, ['route' => ['articles-blog.update', $post->id], 'method' => 'PATCH', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered']) !!}

    	@include('admin.blog-articles._form', ['submitButtonText' => 'Mettre à jour'])

    {!! Form::close() !!}
</div>
<!-- END Example Block -->

@if(Auth::guard('admin')->user()->hasPermission(6))
@include('admin.partials._uploadmedias')
@endif

@stop

@push('scripts')
<link rel="stylesheet" type="text/css" href="{{asset('admin1/css/tagmanager.min.css')}}">
<script type="text/javascript" src="{{asset('admin1/js/tagmanager.min.js')}}"></script>
<script src="{{asset('admin1/js/bootstrap3-typeahead.min.js')}}"></script>  
<script type="text/javascript">
  $(document).ready(function() {
    var tagApi = $(".tm-input").tagsManager();


    jQuery(".typeahead").typeahead({
      name: 'tags',
      displayKey: 'name',
      source: function (query, process) {
        return $.get('{{ route("admin-tag-fetch")}}', { query: query }, function (data) {
          data = $.parseJSON(data);
          return process(data);
        });
      },
      afterSelect :function (item){
        tagApi.tagsManager("pushTag", item);
      }
    });
	@foreach($blogTag as $tag)
		tagApi.tagsManager("pushTag", '{{\App\Models\Tag::find($tag->id_tag)->title}}');
	@endforeach
  });
	
</script>
@endpush
