var NewManualTimer = Vue.component('new-manual-timer', {
    template: '#new-manual-timer-template',
    data: function () {
        return {
            newManualTimer: {
                activity: {}
            }
        };
    },
    components: {},
    methods: {
        /**
         * Instead of starting and stopping the timer,
         * enter the start and stop times manually
         */
        insertManualTimer: function () {
            $.event.trigger('show-loading');
            var data = TimersRepository.setData(this.newManualTimer, this.date.sql);
            $('#timer-clock').timer({format: '%H:%M:%S'});

            this.$http.post('/api/timers/', data, function (response) {
                    this.timers.push(response);
                    $.event.trigger('manual-timer-created');
                    $.event.trigger('provide-feedback', ['Manual entry created', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },

        /**
         *
         */
        setDefaultActivity: function () {
            this.newManualTimer.activity = this.activities[0];
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('activities-loaded', function (event) {
                setTimeout(function () {
                    that.setDefaultActivity();
                }, 500);
            });
        }

    },
    props: [
        'activities',
        'date',
        'timers'
    ],
    ready: function () {
        this.listen();
    }
});
