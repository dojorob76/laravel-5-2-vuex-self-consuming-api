@if(session()->has('vue_flash'))
    <!-- Display VueStrap Alert Permanent Messages -->
    <v-alert type="{{session('vue_flash.level')}}">
        <p class="text-center">
            <span class="bold">{{session('vue_flash.title')}}:</span> {{session('vue_flash.message')}}
        </p>
    </v-alert>
@endif

@if(session()->has('vue_dismiss'))
    <!-- Display VueStrap Alert Dismissable Messages -->
    <v-alert type="{{session('vue_dismiss.level')}}" dismissable>
        <p class="text-center">
            <span class="bold">{{session('vue_dismiss.title')}}:</span> {{session('vue_dismiss.message')}}
        </p>
    </v-alert>
@endif

@if(session()->has('vue_timed'))
    <!-- Display VueStrap Alert Timed Messages -->
    <div v-bind="showSessionAlert = true">
        <v-alert
            :show.sync="showSessionAlert"
            :duration="10000"
            type="{{session('vue_timed.level')}}"
            width="300px"
            placement="top-right"
            dismissable
        >
            <p class="text-center">
                <span class="bold">{{session('vue_timed.title')}}:</span> {{session('vue_timed.message')}}<br><br>
                <small>
                    This message will close after 10 seconds<br>
                    <a class="alert-link" href="#" v-on:click="showSessionAlert = !showSessionAlert">
                        Close Now
                    </a>
                </small>
            </p>
        </v-alert>
    </div>
@endif