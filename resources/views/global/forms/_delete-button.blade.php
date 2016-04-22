{{ method_field('DELETE') }}
<button type="button"
        class="btn btn-danger delete-btn"
        data-toggle="modal"
        data-target="#deleteModal"
>
    @if(isset($delete_text))
        {{$delete_text}}
    @else
        Delete
    @endif
</button>