<ul class="nav nav-tabs push" data-toggle="tabs">
	<li class="active"><a href="#tab-specs">Caractéristiques</a></li>
	@foreach($languages as $language)
	   <li><a href="#tab-{{ $language->short }}"><i class="fa fa-font"></i>&nbsp;&nbsp;{{ $language->name }}</a></li>
	@endforeach

	@if(isset($page) && $page->history->count())
	   <li><a href="#tab-history"><i class="fa fa-history"></i>&nbsp;&nbsp;Historique des changements</a></li>
	@endif
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="tab-specs">
	    @if(isset($page) && $page->admin)
	     <div class="form-group">
		    {!! Form::label('admin', 'Auteur', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-3">
			    {!! Form::text('admin', $page->admin->displayName(), ['class'=>'form-control', 'disabled' => 'disabled']) !!}
		    </div>
		</div>
	    @endif
		<div class="form-group{{ $errors->has('published') ? ' has-error' : '' }}">
		    {!! Form::label('published', 'Publiée', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-3">
			    <label class="switch switch-primary">
			    	{!! Form::checkbox('published', 1, isset($page_arr) && $page_arr['published']==1) !!}
			    	<span></span>
				</label>
		    </div>
		</div>
		<div class="form-group{{ $errors->has('footer') ? ' has-error' : '' }}">
		    {!! Form::label('footer', 'Affichée sur le footer', ['class'=>'col-md-3 control-label']) !!}
		    <div class="col-md-3">
			    <label class="switch switch-primary">
			    	{!! Form::checkbox('footer', 1, isset($page_arr) && $page_arr['footer']==1) !!}
			    	<span></span>
				</label>
		    </div>
		</div>
    </div>
    @foreach($languages as $language)
    	@include('admin.pages._lang_form')
    @endforeach
    @if(isset($page) && $page->history->count())
    <div class="tab-pane" id="tab-history" style="padding: 0 15px">
		<div class="table-responsive">
            <table id="history" class="table table-vcenter table-striped table-bordered table-hover" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">Avant</th>
                        <th class="text-center">Après</th>
                        <th>Date</th>
                        <th>Admin</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@if(!isset($page) && (Auth::guard('admin')->user()->hasPermission(6)))
<div class="form-group">
    <div class="col-md-9 col-md-offset-3">
	    <a href="#" class="btn btn-info btn-files">Enregistrer les informations saisies et y associer des médias</a>
		{{ Form::hidden('upload_files',0) }}
    </div>
</div>
@endif
<div class="form-group form-actions">
    <div class="col-md-9 col-md-offset-3">
        <a class="btn btn-default" href="{!! route('pages.index') !!}"><i class="fa fa-angle-left"></i> Retour</a>
        <button type="submit" class="btn btn-primary">{!! $submitButtonText !!} <i class="fa fa-angle-right"></i></button>
    </div>
</div>

@push('scripts')
<script src="{{ asset('admin1/js/helpers/ckeditor/ckeditor.js') }}"></script>
<script>
	$(document).ready(function(){
		$('.btn-files').click(function(e){
			e.preventDefault();
			$('input[name="upload_files"]').val(1);
			$('form.create-page-form').submit();
		});
	});
	@if(isset($page) && $page->history->count())

	$('#history').DataTable({
        "processing": true,
        "serverSide": true,
		"columnDefs": [ { "targets": [0,1], "orderable": false } ],
		"iDisplayLength": 10,
		"order": [[2, "desc"]],
		"ajax": "{{ URL::route('page_history_ajaxlisting') }}?page_id={{ $page->id }}",
		"drawCallback": function(settings){
			var api = this.api();
            $('[data-toggle="tooltip"]', api.table().container()).each(function () {
               $(this).attr('title', $(this).data('original-title'));
            });
            $('[data-toggle="tooltip"]', api.table().container()).tooltip({
               container: 'body'
            });
		}
    });
    $(document).on('click','.restore-history',function(e){
	    e.preventDefault();
	    var history_id = $(this).attr('data-history-id');
	    var type = $(this).attr('data-type');
	    bootbox.confirm('Êtes-vous sûr de vouloir restaurer cette version ?', function(result) {
		    if (result) {
		        $.post({
			       url: "{{ route('page_restore_history') }}",
			       data:{
				       _token: "{{ csrf_token() }}",
				       history_id: history_id,
				       type: type,
			       }
		        }).done(function(result){
			    	var datas = $.parseJSON(result);
			    	if(datas.status == true){
				    	noty({
							layout: "topRight",
							theme: "metroui",
							type: "success",
							text: "La version à été restaurée avec succès",
							timeout: 4000,
						});
			    	}
		        });
		    }
		});
    })
	@endif
</script>
@endpush
