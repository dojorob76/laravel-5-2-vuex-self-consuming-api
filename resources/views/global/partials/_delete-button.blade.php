@include('global.partials._delete-method-field')
<button type="button"
        class="btn btn-danger delete-btn"
        data-toggle="modal"
        data-target="#deleteModal"
>
    @if(isset($dlt_btn_txt))
        {{$dlt_btn_txt}}
    @else
        Delete
    @endif
</button>