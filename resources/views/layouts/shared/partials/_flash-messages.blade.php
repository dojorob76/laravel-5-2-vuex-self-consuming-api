@if(session()->has('vue_flash'))
    <!-- Display VueStrap Alert Permanent Messages -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 flash-message">
        <v-alert type="{{session('vue_flash.level')}}">
            <p class="text-center">
                <span class="bold">{{session('vue_flash.title')}}:</span> {{session('vue_flash.message')}}
            </p>
        </v-alert>
    </div>
@endif

@if(session()->has('vue_dismiss'))
    <!-- Display VueStrap Alert Dismissable Messages -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 flash-message">
        <v-alert type="{{session('vue_dismiss.level')}}" dismissable>
            <p class="text-center">
                <span class="bold">{{session('vue_dismiss.title')}}:</span> {{session('vue_dismiss.message')}}
            </p>
        </v-alert>
    </div>
@endif

@if(session()->has('vue_timed'))
    <!-- Display VueStrap Alert Timed Messages -->
    <div v-bind="showSessionAlert = true">
        <v-alert
            :show.sync="showSessionAlert"
            :duration="5000"
            type="{{session('vue_timed.level')}}"
            width="300px"
            placement="top-right"
            class="animated"
            dismissable
        >
            <p class="text-center">
                <span class="bold">{{session('vue_timed.title')}}:</span> {{session('vue_timed.message')}}
            </p>
        </v-alert>
    </div>
@endif