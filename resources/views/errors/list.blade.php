@if($errors->any())
    <div class="alert alert-danger">
        <strong>{{ trans('site.whoops_errors') }}</strong>
        <br><br>
        <ul class="list-unstyled">
            @foreach ($errors->all() as $error)
                <li>- {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif