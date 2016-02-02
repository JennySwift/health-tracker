var TimersPage = Vue.component('timers-page', {
    template: '#timers-page-template',
    data: function () {
        return {
            date: DatesRepository.setDate(this.date),
            timerInProgress: false,
            newTimer: {
                activity: {}
            },
            newManualTimer: {
                activity: {}
            },
            showTimerInProgress: true,
            timers: [],
            activities: [],
            timersFilter: false,
            activitiesWithDurationsForTheWeek: []
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
                $scope.timerInProgress = response.data;
                $.event.trigger('provide-feedback', ['Timer started', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         * Instead of starting and stopping the timer,
         * enter the start and stop times manually
         */
        insertManualTimer: function () {
            $.event.trigger('show-loading');
            var data = TimersRepository.setData(this.newManualTimer, this.date.sql);
            $('#timer-clock').timer({format: '%H:%M:%S'});

            this.$http.post('/api/timers/', data, function (response) {
                this.timers.push(response.data);
                this.getTotalMinutesForActivitiesForTheDay();
                this.getTotalMinutesForActivitiesForTheWeek();
                $.event.trigger('provide-feedback', ['Manual entry created', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
        *
        */
        getActivities: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/activities', function (response) {
                this.activities = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
        *
        */
        stopTimer: function (timer) {
            $.event.trigger('show-loading');
            $('#timer-clock').timer('remove');

            var data = {
                finish: TimersRepository.calculateFinishTime(this.timerInProgress)
            };

            this.$http.put('/api/timers/' + this.timerInProgress.id, data, function (response) {
                this.timerInProgress = false;
                this.timers.push(response);
                this.getTotalMinutesForActivitiesForTheDay();
                this.getTotalMinutesForActivitiesForTheWeek();
                $.event.trigger('provide-feedback', ['Timer updated', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
        *
        */
        getTimers: function () {
            $.event.trigger('show-loading');
            var url = TimersRepository.calculateUrl(false, this.date.sql);

            this.$http.get(url, function (response) {
                this.timers = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         *
         * @param timer
         * @returns {boolean}
         */
        filterTimers: function (timer) {
            if (this.timersFilter) {
                return timer.activity.data.name.indexOf(this.timersFilter) !== -1;
            }
            return true;

        },

        /**
         *
         * @param minutes
         * @returns {number}
         */
        formatMinutes: function (minutes) {
            return minutes * 10;
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
                this.handleResponseError(response);
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
        getTotalMinutesForActivitiesForTheDay: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/activities/getTotalMinutesForDay?date=' + this.date.sql, function (response) {
                this.activitiesWithDurationsForDay = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
        *
        */
        getTotalMinutesForActivitiesForTheWeek: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/activities/getTotalMinutesForWeek?date=' + this.date.sql, function (response) {
                this.activitiesWithDurationsForWeek = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
        *
        */
        deleteTimer: function (timer) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/timers/' + timer.id, function (response) {
                    this.timers = _.without(this.timers, timer);
                    this.getTotalMinutesForActivitiesForTheDay();
                    this.getTotalMinutesForActivitiesForTheWeek();
                    $.event.trigger('provide-feedback', ['Timer deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
            }
        },

        listen: function () {
            var that = this;
            $(document).on('date-changed', function (event) {
                that.getTimers();
                that.getTotalMinutesForActivitiesForTheDay();
                that.getTotalMinutesForActivitiesForTheWeek();
            });
        },

        /**
         * 
         * @param response
         */
        handleResponseError: function (response) {
            this.$broadcast('response-error', response);
            this.showLoading = false;
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.checkForTimerInProgress();
        this.getActivities();
        this.listen();
    }
});