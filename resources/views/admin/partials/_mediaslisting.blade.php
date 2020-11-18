@php $medias_count = \App\Models\Media::count() @endphp
<p class="no-medias{{ $medias_count ? ' hide' : '' }}">
    <em>Pas encore de médias.</em>
</p>
<div class="block full medias-container{{ !$medias_count ? ' hide' : '' }}">
    <div class="block-content-full">
        <div class="table-responsive">
            <table id="medias" class="table table-vcenter table-striped table-bordered table-hover" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        @if(isset($show_checkboxes))
                        <th class="text-center">
                            <input type="checkbox" name="check_all_medias" value="1"/>
                        </th>
                        @endif
                        <th></th>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Langue</th>
                        <th>Catégories</th>
                        <th class="text-center">Dates</th>
                        <th>Admin</th>
                        <th>Status</th>
                        <th>Public</th>
                        <th class="text-center">{{ isset($show_actions) ? 'Actions' : 'Sélection' }}</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td data-search="0"></td>
                        @if(isset($show_checkboxes))
                        <td data-search="0"></td>
                        @endif
                        <td data-search="0"></td>
                        <td data-search="1" data-column="media_name" data-type="text" data-placeholder="Rechercher par nom de média"></td>
                        <td data-search="1" data-column="type" data-type="select" data-options="{{ json_encode([''=>'Tous les types','file'=>'Fichiers','picture'=>'Images']) }}"></td>
                        <td data-search="1" data-column="language" data-type="select" data-options="{{ json_encode(\App\Models\Media::languages('-')) }}"></td>
                        <td data-search="1" data-column="media_category" data-type="text" data-placeholder="Rechercher par catégorie"></td>
                        <td data-search="1" data-column="dates" data-type="text" data-placeholder="Entrez une date" data-datepicker="1"></td>
                        <td data-search="1" data-column="admin" data-type="text" data-placeholder="Rechercher par admin"></td>
                        <td data-search="1" data-column="status" data-type="select" data-options="{{ statusString() }}"></td>
                        <td data-search="1" data-column="public" data-type="select" data-options="{{ json_encode([''=>'Tout','public'=>'Public','private'=>'Privé']) }}"></td>
                        <td data-search="0"></td>
                    </tr>
                </tfoot>
                <tbody></tbody>
            </table>
            <div class="input-group input-daterange" data-date-format="dd/mm/yyyy">
                <input type="text" class="form-control text-center" name="min_created_at" placeholder="Du">
                <span class="input-group-addon">
                    <i class="fa fa-angle-right"></i>
                </span>
                <input type="text" class="form-control text-center" name="max_created_at" placeholder="Au">
            </div>
            @if(isset($show_btn_upload_scroll))
            <a href="#" class="btn btn-info btn-scroll-upload-media pull-right"><i class="fa fa-cloud-upload"></i>&nbsp;&nbsp;Charger un média</a>
            @endif
            @if(auth()->guard('admin')->user()->medias_status != 'subsidiary_contributor')
            <select class="form-control" name="validation">
                <option value="0">Tout</option>
                <option value="1">À valider</option>
            </select>
            @endif
        </div>
    </div>
</div>
@if(isset($show_checkboxes))
<a href="#" class="btn btn-warning btn-action-medias">Actions pour les médias sélectionnés</a>
<div class="modal fade" tabindex="-1" role="dialog" id="media-actions-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center">Actions</h4>
            </div>
            <div class="modal-body">
                <p class="selected-medias">Médias sélectionnés : <span></span></p>
                <div class="row">
                    <div class="col-md-4">
                        <label>Changer le status</label>
                        {{ Form::select('status', status(true), null, ['class' => 'form-control select-chosen']) }}
                    </div>
                    <div class="col-md-4">
                        <label>Public ?</label>
                        {{ Form::select('public', ['' => '-', '1' => 'Oui', '0' => 'Non'], null, ['class' => 'form-control select-chosen']) }}
                    </div>
                    <div class="col-md-4">
                        <label>Supprimer</label>
                        <a href="#" class="btn btn-warning delete-multiple-media">Supprimer les médias sélectionnés</a>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-left">
                <button type="button" class="btn btn-primary pull-left multiple-action save-multiple-status hide" data-action="status">Sauvegarder le status</button>
                <button type="button" class="btn btn-danger pull-right multiple-action confirm-delete-multiple-media hide" data-action="delete"><i class="fa fa-warning"></i>&nbsp;&nbsp;Confirmer la suppression</button>
            </div>
        </div>
    </div>
</div>
@endif
@push('styles')
<link rel="stylesheet" href="{{ asset('admin1/css/flag-icon-css/css/flag-icon.min.css') }}">
<style>
    #medias tfoot{display:table-header-group;}
    #medias tfoot input[type="text"], #medias tfoot select{width:100%;}
    #medias_filter .input-daterange{position:relative;top:-1px;margin-left:15px;}
    #medias_filter .input-daterange input{width:150px !important;}
    .btn-action-medias{position: fixed;left:15px;bottom:15px;z-index:1;display:none}
    #media-actions-modal .modal-body > .row{display:flex;align-items:center;}
    #media-actions-modal .modal-body .selected-medias{margin-bottom:10px;text-align:center;}
    #media-actions-modal .modal-body .selected-medias span{font-weight:bold;font-size:14px;}
    #media-actions-modal .modal-body .delete-multiple-media{display:block;}
    #media-actions-modal .modal-footer{min-height:64px;}
</style>
@endpush
@push('scripts')
<script>
$(function(){
    var cookie_filters_name = 'filter_values_medias';
    var delete_entry = function(){
        $('.delete').on('click', function(e){
            e.preventDefault();
            var $entry = $(this).data('entry');
            var name = $(this).data('name');
            bootbox.confirm("Êtes-vous sûr de vouloir supprimer "+ name +" ?", function(result) {
                if(result==true)
                    $('#delete-'+$entry).submit();
            });
        });
    };

    /* Initialize Datatables */
    $('#medias tfoot td').each( function () {
        if($(this).attr('data-search') == 1){
            var default_value = null;
            if(Cookies.get(cookie_filters_name) != undefined){
                var filter = $.parseJSON(Cookies.get(cookie_filters_name));
                var filter_value = filter[$(this).attr('data-column')];
                if(filter_value != undefined && filter_value != ''){
                    default_value = filter_value;
                }
            }
            if($(this).attr('data-type') == 'text'){
                $(this).html('<input type="text" class="form-control search-column" placeholder="'+$(this).attr('data-placeholder')+'"'+(default_value ? ' value="'+default_value+'"' : '')+'/>');
                if($(this).attr('data-datepicker')){
                    $(this).find('input').datepicker({
                        format: 'dd/mm/yyyy',
                        weekStart: 1,
                        todayHighlight: true
                    });
                }
            } else if($(this).attr('data-type') == 'select') {
                var options = $.parseJSON($(this).attr('data-options'));
                var select = '<select class="form-control search-column">';
                for(var key in options) {
                    select += '<option value="'+key+'"'+(default_value && default_value == key ? ' selected="selected"' : '')+'>'+options[key]+'</option>';
                }
                select += '</select>';
                $(this).html(select);
            }
        }
    });
    mediasDatatable = $('#medias').DataTable({
        "processing": true,
        "serverSide": true,
        "columnDefs": [ { "targets": [1,{{ isset($show_checkboxes) ? '2,6,7,11' : '5,6,10' }}], "orderable": false } ],
        "iDisplayLength": 10,
        "order": [[0, "desc"]],
        "stateSave": true,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tout"]],
        "ajax": {
            url: "{{ route('medias_ajaxlisting') }}",
            data: function (d) {
                d.showActions = {{ isset($show_actions) ? 1 : 0 }};
                d.showCheckboxes = {{ isset($show_checkboxes) ? 1 : 0 }};
                @if(isset($model))
                d.model = "{{ $model }}";
                @endif
                @if(isset($model_id))
                d.model_id = {{ $model_id }};
                @endif
                var search_columns = new Object;
                $('.search-column').each(function(){
                    search_columns[$(this).parent().attr('data-column')] = $(this).val();
                });
                d.search_columns = search_columns;
                d.min_created_at = $('input[name="min_created_at"]').val();
                d.max_created_at = $('input[name="max_created_at"]').val();
                d.validation = $('select[name="validation"]').length ? $('select[name="validation"]').val() : null;
            }
        },
        "drawCallback": function(settings){
            var api = this.api();
            $('[data-toggle="tooltip"]', api.table().container()).each(function () {
               $(this).attr('title', $(this).data('original-title'));
            });
            $('[data-toggle="tooltip"]', api.table().container()).tooltip({
               container: 'body'
            });
            delete_entry();
            $('.btn-action-medias').fadeOut();
        }
    });
    $('.medias-container .dataTables_filter input').addClass('form-control').attr('placeholder', 'Recherche globale');

    mediasDatatable.columns().every( function () {
        var that = this;
        $('.search-column', this.footer()).on('keyup change', function(){
            if (that.search() !== this.value){
                that.search(this.value).draw();
                var object_values = new Object;
                $('#medias tfoot td').each(function(){
                    if($(this).attr('data-search') == '1'){
                        var value;
                        if($(this).attr('data-type') == 'text'){
                            value = $(this).find('input[type="text"]').val();
                        } else if($(this).attr('data-type') == 'select') {
                            value = $(this).find('select').val();
                        }
                        object_values[$(this).attr('data-column')] = value;
                    }
                });
                Cookies.set(cookie_filters_name, JSON.stringify(object_values));
            }
        });
    });
    $('.medias-container .input-daterange').prependTo('#medias_filter').change(function () {
        mediasDatatable.ajax.reload();
    });
    if($('.btn-scroll-upload-media').length){
        $('.btn-scroll-upload-media').prependTo('#medias_length').click(function(e){
            e.preventDefault();
            $('html, body').animate({ scrollTop: $(document).height() }, 500);
            setTimeout(function(){
                $('#upload_files').trigger('click');
            }, 750);
        });
    }
    if($('select[name="validation"]').length){
        $('select[name="validation"]').prependTo('#medias_filter').change(function () {
            mediasDatatable.ajax.reload();
        });
    }
    $(document).on('change','input[type="checkbox"][name="media_type"]',function(){
        var media_id = $(this).attr('data-id');
        var type_id = $(this).attr('data-type-id');
        var type = $(this).attr('data-type');
        var checked = $(this).is(':checked') ? 1 : 0;
        $.post({
            url: "{{ route('media_affectation') }}",
            data:{
                _token: "{{ csrf_token() }}",
                media_id: media_id,
                type_id: type_id,
                type: type,
                checked: checked,
            }
        }).done(function(){
            if(typeof listing_files !== undefined){
                listing_files();
            }
        })
    });
    $('input[name="check_all_medias"]').change(function(){
        $('input[name="media_id"]').prop('checked', $(this).is(':checked')).trigger('change');
    });
    $(document).on('change', 'input[name="media_id"]', function(){
        if($('input[name="media_id"]:checked').length){
            $('.btn-action-medias').fadeIn();
        } else {
            $('.btn-action-medias').fadeOut();
        }
    });
    var selected_medias;
    $('.btn-action-medias').click(function(e){
        e.preventDefault();
        selected_medias = new Array;
        $('input[name="media_id"]:checked').each(function(){
            selected_medias.push($(this).val());
        });
        $('#media-actions-modal .selected-medias span').html(selected_medias.length);
        $('#media-actions-modal select').val('').trigger("chosen:updated");
        $('#media-actions-modal .modal-footer > button').addClass('hide');
        $('#media-actions-modal').modal('show');
    });
    $('#media-actions-modal select').change(function(){
        var show_button = false;
        $('#media-actions-modal select').each(function(){
            if($(this).val() != ''){
                show_button = true;
            }
        });
        if(show_button){
            $('#media-actions-modal .save-multiple-status').removeClass('hide');
        } else {
            $('#media-actions-modal .save-multiple-status').addClass('hide');
        }
    });
    $('#media-actions-modal .delete-multiple-media').click(function(e){
        e.preventDefault();
        $('#media-actions-modal .confirm-delete-multiple-media').removeClass('hide');
    });
    $('#media-actions-modal .multiple-action').click(function(e){
        var action = $(this).attr('data-action');
        $.post({
            url: "{{ route('medias_multiple_actions') }}",
            data:{
                _token: "{{ csrf_token() }}",
                action: action,
                medias: selected_medias,
                status: $('#media-actions-modal select[name="status"]').val(),
                public: $('#media-actions-modal select[name="public"]').val(),
            }
        }).done(function(result){
            $('#media-actions-modal').modal('hide');
            mediasDatatable.ajax.reload();
            if(result == 0){
                $('.medias-container').addClass('hide');
                $('.no-medias').removeClass('hide');
            }
        })
    })
});
</script>
@endpush
