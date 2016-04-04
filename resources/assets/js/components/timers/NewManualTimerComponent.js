var NewManualTimer = Vue.component('new-manual-timer', {
    template: '#new-manual-timer-template',
    data: function () {
        return {
            newManualTimer: {
                activity: {}
            },
            showPopup: true
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
                console.log(router);
                    $.event.trigger('manual-timer-created');
                    $.event.trigger('provide-feedback', ['Manual entry created', 'success']);
                    $.event.trigger('hide-loading');
                    router.go('/timers');
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
        closePopup: function ($event) {
            HelpersRepository.closePopup($event, this);
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

            $(document).on('show-new-manual-timer-popup', function (event) {
                if (that.$route.path.indexOf('/timers') !== -1) {
                    //We're on the timers page so we can show the popup
                    that.showPopup = true;
                }
                else {
                    //Wait for the timers page to load before showing the popup
                    setTimeout(function () {
                        that.showPopup = true;
                    }, 5000);
                }
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
        this.setDefaultActivity();
    }
});
