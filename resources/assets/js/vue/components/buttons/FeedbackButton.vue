<script>
    export default{

        props: [
            {name: 'waitTxt', type: String, default: 'Waiting...'},
            {name: 'btnType', type: String, default: 'submit'},
            {name: 'btnClass', type: String, default: 'primary'},
            {name: 'extClass', type: String, default: ''}
        ],

        template: '<button type="{{btnType}}" class="btn btn-{{btnClass}}{{extClass}}">' +
        '<div class="btn-wait-content">' +
        '<span class="btn-wait-icon glyphicon glyphicon-hourglass animated rotateIn infinite"></span>&nbsp;' +
        '<span class="btn-wait-text animated flash infinite"></span>' +
        '</div><div class="orig-text" data-wait="{{waitTxt}}"><slot></slot></div></button>',

        ready: function () {
            this.showFeedback();
        },

        methods: {
            showFeedback: function () {
                var self = this;
                $('.add-feedback').on('click', function () {
                    buttonFeedback.buttonClick($(this), 'show');
                });
                self.$nextTick(function(){
                    buttonFeedback.buttonClick($(this), 'hide');
                });
            }
        }
    }
</script>