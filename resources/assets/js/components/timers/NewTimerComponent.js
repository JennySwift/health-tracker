var NewTimer = Vue.component('new-timer', {
    template: '#new-timer-template',
    data: function () {
        return {
            newTimer: {
                activity: {}
            },
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        startTimer: function () {
            $.event.trigger('show-loading');
            var data = TimersRepository.setData(this.newTimer);
            $('#timer-clock').timer({format: '%H:%M:%S'});

            this.$http.post('/api/timers/', data, function (response) {
                    this.timerInProgress = response;
                    $.event.trigger('provide-feedback', ['Timer started', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },

        /**
         *
         */
        stopTimer: function () {
            $.event.trigger('show-loading');
            $('#timer-clock').timer('remove');

            var data = {
                finish: TimersRepository.calculateFinishTime(this.timerInProgress)
            };

            this.$http.put('/api/timers/' + this.timerInProgress.id, data, function (response) {
                    this.timerInProgress = false;
                    this.timers.push(response);
                    $.event.trigger('timer-stopped');
                    $.event.trigger('provide-feedback', ['Timer updated', 'success']);
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
            this.newTimer.activity = this.activities[0];
        },

        /**
         *
         */
        checkForTimerInProgress: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/timers/checkForTimerInProgress', function (response) {
                    if (response.activity) {
                        this.resumeTimerOnPageLoad(response);
                    }
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },

        /**
         *
         * @param timer
         */
        resumeTimerOnPageLoad: function (timer) {
            this.timerInProgress = timer;
            var seconds = moment().diff(moment(timer.start, 'YYYY-MM-DD HH:mm:ss'), 'seconds');
            $('#timer-clock').timer({
                format: '%H:%M:%S',
                //The timer has already started
                seconds: seconds
            });
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
        'timerInProgress',
        'showTimerInProgress',
        'timers'
    ],
    ready: function () {
        this.listen();
        this.checkForTimerInProgress();
    }
});
