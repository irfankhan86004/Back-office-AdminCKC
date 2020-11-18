@extends('admin.layouts.app')

@section('title')
Ajouter un article
@stop

@section('content')
<!-- Blank Header -->
<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="fa fa-rss"></i>Articles du blog<br>
            <small>Ajouter un article</small>
        </h1>
    </div>
</div>
<!-- END Blank Header -->
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin_dashboard') }}">Admin</a></li>
    <li><a href="{{ route('articles-blog.index') }}">Articles du blog</a></li>
    <li>Ajouter</li>
</ul>

@include('errors.list')

<!-- Example Block -->
<div class="block">
    <!-- Example Title -->
    <div class="block-title">
        <h2>Ajouter un article</h2>
    </div>
    <!-- END Example Title -->

    {!! Form::open(['route' => ['articles-blog.store'], 'method' => 'POST', 'enctype'=>'multipart/form-data', 'class' => 'form-horizontal form-bordered create-blog-post-form']) !!}
    
    	@include('admin.blog-articles._form', ['submitButtonText' => 'Ajouter'])
    
    {!! Form::close() !!}
</div>
<!-- END Example Block -->
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
	
  });
</script>
@endpush