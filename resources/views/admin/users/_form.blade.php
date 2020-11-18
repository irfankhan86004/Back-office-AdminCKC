<ul class="nav nav-tabs push" data-toggle="tabs">
    <li class="active"><a href="#tabGeneral"><i class="fa fa-file-text-o"></i>&nbsp;&nbsp;Général</a></li>
</ul>

<div class="tab-content">
    @include('admin.users.partials._general')
</div>

<div class="form-group form-actions">

    <div class="col-md-9 col-md-offset-3">
        <a class="btn btn-default" href="{!! route('users.index') !!}"><i class="fa fa-angle-left"></i> Retour</a>
        <button type="submit" class="btn btn-primary">{!! $submitButtonText !!} <i class="fa fa-angle-right"></i></button>
    </div>

</div>
