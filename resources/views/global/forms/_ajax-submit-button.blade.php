<button type="submit"
        class="btn btn-primary ajax-validate @if(isset($btn_class)){{$btn_class}}@endif"
        data-prefix="{{$data_prefix}}"
>
    @include('global.partials._button-wait')
    <span class="submit-text"
          @if(isset($data_wait))
            data-wait="{{$data_wait}}"
          @endif
    >
        @if(isset($submit_text))
            {{$submit_text}}
        @else
            Submit
        @endif
    </span>
</button>