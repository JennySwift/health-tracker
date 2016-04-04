var TimerPopup = Vue.component('timer-popup', {
    template: '#timer-popup-template',
    data: function () {
        return {
            showPopup: false,
            selectedTimer: {}
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        deleteTimer: function () {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/timers/' + this.selectedTimer.id, function (response) {
                    $.event.trigger('timer-deleted', [this.selectedTimer]);
                    this.showPopup = false;
                    $.event.trigger('provide-feedback', ['Timer deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },

        /**
        *
        */
        closePopup: function ($event) {
            HelpersRepository.closePopup($event, this);
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-timer-popup', function (event, timer) {
                that.selectedTimer = timer;
                that.showPopup = true;
            });
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.listen();
    }
});
