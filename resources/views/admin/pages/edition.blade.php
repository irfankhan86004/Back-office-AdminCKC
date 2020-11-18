<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Edition de la page "{{ $page->getAttr('name') }}"</title>
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin1/js/keditor/examples/plugins/bootstrap-3.3.6/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('admin1/js/keditor/examples/plugins/font-awesome-4.5.0/css/font-awesome.min.css') }}" />
        <link rel="stylesheet" data-type="keditor-style" type="text/css" href="{{ asset('admin1/js/keditor/dist/css/keditor.min.css') }}" />
        <link rel="stylesheet" data-type="keditor-style" type="text/css" href="{{ asset('admin1/js/keditor/dist/css/keditor-components.min.css') }}" />

        <script data-type="keditor-style" src="{{ asset('admin1/js/keditor/examples/plugins/jquery-1.11.3/jquery-1.11.3.min.js') }}"></script>
        <script data-type="keditor-style" src="{{ asset('admin1/js/keditor/examples/plugins/bootstrap-3.3.6/js/bootstrap.min.js') }}"></script>

        <script data-type="keditor-style" type="text/javascript">
            var bsTooltip = $.fn.tooltip;
            var bsButton = $.fn.button;
        </script>
        <script data-type="keditor-style" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        {{--<script data-type="keditor-style" src="/admin/js/keditor/examples/plugins/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>--}}
        <script data-type="keditor-style" type="text/javascript">
            $.widget.bridge('uibutton', $.ui.button);
            $.widget.bridge('uitooltip', $.ui.tooltip);
            $.fn.tooltip = bsTooltip;
            $.fn.button = bsButton;
        </script>
        <script data-type="keditor-style" src="{{ asset('admin1/js/keditor/examples/plugins/jquery.nicescroll-3.6.6/jquery.nicescroll.min.js') }}"></script>
        <script data-type="keditor-style" src="{{ asset('admin1/js/keditor/examples/plugins/ckeditor-4.5.6/ckeditor.js') }}"></script>
        <script data-type="keditor-style" src="{{ asset('admin1/js/keditor/examples/plugins/ckeditor-4.5.6/adapters/jquery.js') }}"></script>
        <script data-type="keditor-style" src="{{ asset('admin1/js/keditor/dist/js/keditor.js') }}"></script>
        <script data-type="keditor-style" src="{{ asset('admin1/js/keditor/dist/js/keditor-components.js') }}"></script>
		<script data-type="keditor-style" src="{{ asset('admin1/js/vendor/jquery.noty.packaged.min.js') }}"></script>
        <link rel="stylesheet" type="text/css" href="{{ asset('admin1/js/keditor/src/css/custom.css') }}" />
    </head>
    <body class="editor-page">
	    <div class="keditor-header">
			{{-- Back to previous page --}}
		    <a href="{{ route('pages.edit', $page->id) }}" class="btn btn-default"><i class="fa fa-angle-left"></i>&nbsp;&nbsp;Retour</a>
			
			{{-- Save the content --}}
		    <a href="#" class="btn btn-success save"><i class="fa fa-check"></i>&nbsp;&nbsp;Enregistrer la page</a>
		
			{{-- Add medias button --}}
		    @if(auth()->guard('admin')->user()->hasPermission(6))
		    	<a href="#" class="btn btn-primary medias-btn"><i class="fa fa-folder-open-o"></i>&nbsp;&nbsp;Médias</a>
		    @endif
		
			{{-- Preview button --}}
			<form action="{{ route('page_preview', $page->id) }}" method="POST" id="formPreviewPage" target="_blank" style="display: inline-block">
				{{ csrf_field() }}
				{{ Form::hidden('html', null) }}
				<button type="submit" class="btn btn-primary" id="btnSubmitPreviewForm"><i class="fa fa-eye"></i>&nbsp;&nbsp;Prévisualiser la page</button>
			</form>
			
			{{-- Import content from another lang --}}
			@if(!empty($otherLang))
				<select class="form-control" name="pages">
					<option data-url="0">Importer depuis une autre langue</option>
					@foreach($otherLang as $lang)
						<option data-url="{{ route('page_replace', [$page, $language, $lang->language]) }}">{{ $lang->name.' ('.strtoupper($lang->language->short).')' }}</option>
					@endforeach
				</select>
			@endif
			
			{{-- Info label --}}
			<div class="alert alert-info">Page en cours d'édition : <strong>{{ $page->getAttr('name', $language->id).' ('.strtoupper($language->short).')' }}</strong></div>
	    </div>
        <div id="content-area">{!! $content !!}</div>
        @if(auth()->guard('admin')->user()->hasPermission(6))
		<div class="modal fade" tabindex="-1" role="dialog" id="medias-modal">
			<div class="modal-dialog modal-lg" role="document">
		    	<div class="modal-content">
					<div class="modal-body">
						<iframe src="{{ route('pages.edit', [$page->id, 'only_medias_block' => 1]) }}" id="medias-iframe" allowtransparency="true"></iframe>
		    		</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
		    		</div>
		    	</div>
			</div>
		</div>
		@endif
        <script type="text/javascript">
            $(function() {
	            function initializeKEditor(){
			        $('#content-area').keditor({
		                snippetsUrl: '/admin/js/keditor/src/snippets/snippets.html'
	                });
	            }
	            initializeKEditor();
                $('.save').click(function(e){
	                e.preventDefault();
	                var text = $('#content-area').keditor('getContent');
	                $.ajax({
		                method: "POST",
		                url: "{{ route('save_page_edition') }}",
		                data:{
			            	text: text,
			            	page_id: {{ $page->id }},
			            	language_id: {{ $language->id }},
			            	_token: "{{ csrf_token() }}"
		                }
	                }).done(function(result){
		                var message;
		                var type;
		                if(result == 'ok'){
			            	message = 'La page a été enregistrée avec succès';
			            	type = 'success';
		                } else {
			            	message = 'Un problème s\'est produit lors de l\'enregistrement de la page';
			            	type = 'warning';
		                }
			            noty({
							layout: 'topCenter',
							theme: 'metroui',
							type: type,
							text: message,
							timeout: 4000
						});
	                });
                });
	
                // Import content
				$('select[name="pages"]').on('change', function(){
					if(confirm('Le contenu de cette page sera remplacé. Voulez-vous poursuivre le transfert ?')){
						window.location.href = $(this).find('option:selected').attr('data-url');
					} else {
						$('select[name="pages"] option[data-url="0"]').attr('selected','selected');
					}
				});
                
				// Preview
				$('#btnSubmitPreviewForm').on('click', function(e){
					e.preventDefault();
					var textHTML = $('#content-area').keditor('getContent');
					$('#formPreviewPage input[name="html"]').val(textHTML);
					$('#formPreviewPage').trigger('submit');
				});
				
				// Medias
                $('.medias-btn').click(function(e){
	                e.preventDefault();
	                $('#medias-modal').modal('show');
                });
            });
			$(window).resize(function () {
				$('#medias-iframe').height($(document).height() - 165);
				$('.keditor-frame').height($(document).height() - 75);
			}).trigger('resize');
			setTimeout(function(){
				$(window).trigger('resize');
			}, 200);
        </script>
    </body>
</html>
