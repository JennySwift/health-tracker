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
        },
        timers: function () {
            return this.shared.timers;
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
            var data = TimersRepository.setData(this.newTimer);
            //So the previous timer's time isn't displayed at the start
            this.time = 0;

            HelpersRepository.post('/api/timers', data, 'Timer started', function (response) {
                this.timerInProgress = response.data;
                this.setTimerInProgress();
            }.bind(this));
        },

        /**
        *
        */
        stopTimer: function () {
            clearInterval(this.secondsInterval);

            var data = {
                finish: TimersRepository.calculateFinishTime(this.timerInProgress)
            };

            HelpersRepository.put('/api/timers/' + this.timerInProgress.id, data, 'Timer finished', function (response) {
                this.timerInProgress = false;
                store.addTimer(response.data);
            }.bind(this));
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
            HelpersRepository.get('/api/timers/checkForTimerInProgress', function (response) {
                if (response.data.activity) {
                    this.timerInProgress = response.data;
                    this.setTimerInProgress();
                }
            }.bind(this));
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

    ],
    ready: function () {
        this.listen();
        this.checkForTimerInProgress();
    }
};
