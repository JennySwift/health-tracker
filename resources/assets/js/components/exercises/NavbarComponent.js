require('bootstrap');

module.exports = {
    template: '#navbar-template',
    data: function () {
        return {

        };
    },
    components: {},
    methods: {
        /**
         *
         */
        showNewManualTimerPopup: function () {
            $.event.trigger('show-new-manual-timer-popup');
        },
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {

    }
};
