var TimersRepository = require('../../repositories/TimersRepository');
var moment = require('moment');

module.exports = {
    template: '#new-timer-template',
    data: function () {
        return {
            newTimer: {
                activity: {}
            },
            showTimerInProgress: true,
            timerInProgress: false,
            shared: store.state,
            time: ''
        };
    },
    computed: {
        activities: function () {
          return this.shared.activities;
        }
    },
    components: {},
    filters: {
        formatDurationFromSeconds: function (seconds) {
            return HelpersRepository.formatDurationFromSeconds(seconds);
        }
    },
    methods: {

        /**
         *
         */
        startTimer: function () {
            $.event.trigger('show-loading');
            var data = TimersRepository.setData(this.newTimer);
            //So the previous timer's time isn't displayed at the start
            this.time = 0;

            this.$http.post('/api/timers/', data).then(function (response) {
                this.timerInProgress = response.data;
                this.setTimerInProgress();
                $.event.trigger('provide-feedback', ['Timer started', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        stopTimer: function () {
            $.event.trigger('show-loading');
            clearInterval(this.secondsInterval);

            var data = {
                finish: TimersRepository.calculateFinishTime(this.timerInProgress)
            };

            this.$http.put('/api/timers/' + this.timerInProgress.id, data).then(function (response) {
                this.timerInProgress = false;
                this.timers.push(response.data);
                $.event.trigger('timer-stopped');
                $.event.trigger('provide-feedback', ['Timer updated', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
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
            this.$http.get('/api/timers/checkForTimerInProgress').then(function (response) {
                if (response.data.activity) {
                    this.timerInProgress = response.data;
                    this.setTimerInProgress();
                }
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        setTimerInProgress: function () {
            var seconds;
            var that = this;
            
            this.secondsInterval = setInterval(function () {
                seconds = moment().diff(moment(that.timerInProgress.start, 'YYYY-MM-DD HH:mm:ss'), 'seconds');
                that.time = seconds;
            }, 1000);
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
        'timers'
    ],
    ready: function () {
        this.listen();
        this.checkForTimerInProgress();
    }
};
