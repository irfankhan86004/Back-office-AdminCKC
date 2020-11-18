<div class="block" id="medias-block">
    <!-- Example Title -->
    <div class="block-title">
        <h2>Upload de médias</h2>
    </div>
    <div class="block-section">
        <!-- Dropzone.js, You can check out https://github.com/enyo/dropzone/wiki for usage examples -->
        <div id="upload_files" class="dropzone"></div>
        @if(!isset($hide_medias_listing))
        <div class="text-center" style="margin-top:15px">
            <a href="#" class="btn btn-info show-global-medias-listing">Ou choisir un média existant</a>
        </div>
        <div class="global-medias-listing" style="display:none;padding-top:15px">
            @include('admin.partials._mediaslisting')
        </div>
        @endif
    </div>
    @if(!isset($hide_medias_listing))
    <div id="listing_medias"></div>
    @endif
</div>

@push('scripts')
<script>
var mediasDatatable = null;
@if(!isset($hide_medias_listing))
var listing_files = function(){
    $.get({
        url: "{!! route('medias_ajaxlisting_partial') !!}",
        data: {
            model: "{!! $model !!}",
            model_id: "{!! $model_id !!}",
        }
    }).done(function(data){
        var obj = jQuery.parseJSON(data);
        if( ! obj.result){
            bootbox.alert(obj.msg);
        }
        else{
            $('#listing_medias').empty().html(obj.content);
            $('[data-toggle="tooltip"]').tooltip();
        }
        // Supprime une dépendance de média
        $('.remove_media').on('click', function(e){
            e.preventDefault();
            var media_type_id = $(this).data('id');
            bootbox.confirm("Vous êtes sur le point de supprimer la dépendance avec ce média, êtes-vous sûr de vouloir continuer? Vous pouvez définitivement supprimer un média dans la section medias", function(result) {
                if(result==true){
                    $.ajax({
                        type: "POST",
                        url: "{!! route('remove_media') !!}",
                        data: {
                            media_type_id: media_type_id,
                            _token: "{{ csrf_token() }}",
                        }
                    })
                    .done(function(data){
                        var obj = jQuery.parseJSON(data);
                        if(obj.result){
                            listing_files();
                        }
                    });
                }
            });
        });
        // Sortable
        $('.sortable_files').sortable({
            placeholder: 'draggable-placeholder',
            opacity: 0.4,
            start: function(event, ui){
                ui.item.css('display', 'table');
                ui.placeholder.css('height', ui.item.outerHeight());
            },
            stop: function(event, ui) {
                ui.item.css('display', 'table-row');
                var children = $('.sortable_files').children().size();
                var media_types = new Array;
                $('.sortable_files > tr').each(function(){
                    media_types.push($(this).data('id'));
                });
                $.post({
                    url: "{!! route('medias_set_order') !!}",
                    data: {
                        media_types: media_types,
                        _token: "{{ csrf_token() }}",
                    }
                });
            }
        });
    });
};
@endif
// Upload
Dropzone.autoDiscover = false;
$("#upload_files").dropzone({
    url:                "{!! route('upload_media') !!}",
    paramName:          "file",
    maxFilesize:        "{!! preg_replace('/[^0-9,.]/', '', ini_get('upload_max_filesize')) !!}",
    parallelUploads:    4,
    dictDefaultMessage: "<i class=\"fa fa-cloud-upload\"></i> Cliquez ou glissez vos fichiers ici pour les envoyer",
    dictFileTooBig:     "Votre fichier est supérieur à {!! preg_replace('/[^0-9,.]/', '', ini_get('upload_max_filesize')) !!}Mb",
    error: function(file, response)
    {
        if($.type(response) === "string")
            var message = response;
        else
            var message = response.file;
        file.previewElement.classList.add("dz-error");
        _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i];
            _results.push(node.textContent = message);
        }
        return _results;
    },
    success: function(file, done)
    {
        if(mediasDatatable !== null){
            if($('.medias-container').length){
                $('.medias-container').removeClass('hide');
            }
            if($('.no-medias').length){
                $('.no-medias').addClass('hide');
            }
            if($('#medias').length){
                $('#medias').css('width', '100%');
            }
            mediasDatatable.ajax.reload();
        }
    },
    @if(!isset($hide_medias_listing))
    queuecomplete: function(file){
        listing_files();
    },
    @endif
    init: function () {
        var _this = this;
        this.on("sending", function(file, xhr, data) {
            data.append("_token", "{{ csrf_token() }}");
            @if(isset($model))
            data.append("model", "{{ $model }}");
            @endif
            @if(isset($model_id))
            data.append("model_id", {{ $model_id }});
            @endif
        });
    },
});
$(document).ready(function(){
    @if(!isset($hide_medias_listing))
    listing_files();
    @endif
    $('.show-global-medias-listing').click(function(e){
        e.preventDefault();
        $('.global-medias-listing').slideToggle('fast');
        $('#medias').css('width', '100%');
    });
    $(document).on('click','.clipboard-media-url',function(e){
        e.preventDefault();
        var toCopy  = document.getElementById($(this).attr('data-id'));
        $('.tooltip').tooltip('hide');
        toCopy.select();
        document.execCommand('copy');
        return false;
    });
});
</script>
@endpush
