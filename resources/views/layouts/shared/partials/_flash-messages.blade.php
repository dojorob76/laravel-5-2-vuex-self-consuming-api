@if(session()->has('bs_flash'))
    <div class="alert alert-{{session('bs_flash.level')}} text-center col-sm-10 col-sm-offset-1"
         role="alert"
    >
        <strong>{{session('bs_flash.title')}}:</strong> {{session('bs_flash.message')}}
    </div>
@endif

@if(session()->has('bs_dismiss'))
    <div class="alert alert-{{session('bs_dismiss.level')}} alert-dismissible text-center col-sm-10 col-sm-offset-1"
         role="alert"
    >
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>{{session('bs_dismiss.title')}}:</strong> {{session('bs_dismiss.message')}}
    </div>
@endif