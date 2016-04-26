import Vue from 'vue'
Vue.use(require('vue-resource'));

// Set the Global Vue Resource Headers and Options - https://github.com/vuejs/vue-resource
Vue.http.options.root = appGlobals.urlProtocol + appGlobals.rootAppPath;
Vue.http.headers.common['X-CSRF-TOKEN'] = appGlobals.csrf;

// Use Animate.css Transitions - https://daneden.github.io/animate.css/
Vue.transition('bounce-down-up', {
    enterClass: 'bounceInDown',
    leaveClass: 'bounceOutUp'
});
Vue.transition('slide-down', {
    enterClass: 'slideInDown',
    leaveClass: 'slideOutDown'
});
Vue.transition('slide-up', {
    enterClass: 'slideInUp',
    leaveClass: 'slideOutUp'
});

// Use Vue-Strap When Possible - https://yuche.github.io/vue-strap/
var VueStrap = require('vue-strap/dist/vue-strap.min.js');
// Import Properly Functioning Vue-Strap Components
var VAccordion = Vue.extend(VueStrap.accordion);
var VAffix = Vue.extend(VueStrap.affix);
var VAlert = Vue.extend(VueStrap.alert);
var VAside = Vue.extend(VueStrap.aside);
var VCarousel = Vue.extend(VueStrap.carousel);
var VCheckboxBtn = Vue.extend(VueStrap.checkboxBtn);
var VCheckboxGroup = Vue.extend(VueStrap.checkboxGroup);
var VDatepicker = Vue.extend(VueStrap.datepicker);
var VOption = Vue.extend(VueStrap.option);
var VPanel = Vue.extend(VueStrap.panel);
var VProgressbar = Vue.extend(VueStrap.progressbar);
var VRadioBtn = Vue.extend(VueStrap.radioBtn);
var VRadioGroup = Vue.extend(VueStrap.radioGroup);
var VSelect = Vue.extend(VueStrap.select);
var VSlider = Vue.extend(VueStrap.slider);
var VTab = Vue.extend(VueStrap.tab);
var VTabset = Vue.extend(VueStrap.tabset);
var VTypeahead = Vue.extend(VueStrap.typeahead);
// Add Vue-Strap Components to Root Vue Instance
Vue.component('v-accordion', VAccordion);
Vue.component('v-affix', VAffix);
Vue.component('v-alert', VAlert);
Vue.component('v-aside', VAside);
Vue.component('v-carousel', VCarousel);
Vue.component('v-checkbox-btn', VCheckboxBtn);
Vue.component('v-checkbox-group', VCheckboxGroup);
Vue.component('v-datepicker', VDatepicker);
Vue.component('v-option', VOption);
Vue.component('v-panel', VPanel);
Vue.component('v-progressbar', VProgressbar);
Vue.component('v-radio-btn', VRadioBtn);
Vue.component('v-radio-group', VRadioGroup);
Vue.component('v-select', VSelect);
Vue.component('v-slider', VSlider);
Vue.component('v-tab', VTab);
Vue.component('v-tabset', VTabset);
Vue.component('v-typeahead', VTypeahead);

// Import App Components
import Authenticate from './components/authentication/Authenticate.vue'
import FeedbackButton from './components/buttons/FeedbackButton.vue'
// Add App Components to Root Vue Instance
Vue.component('authenticate', Authenticate);
Vue.component('feedback-button', FeedbackButton);

// Set the Vuex Store on Root Vue Instance and Use the Auth Store - https://github.com/vuejs/vuex
import { getAuthorized } from './vuex/getters';
import { setAuthStatus } from './vuex/actions';
import store from './vuex/store'

new Vue({
    el: '#vueapp',

    store: store,

    vuex: {
        getters: {
            authorized: getAuthorized
        },
        actions: {
            setAuthStatus: setAuthStatus
        }
    },

    created: function () {
        this.setAuthStatus();
    }

});