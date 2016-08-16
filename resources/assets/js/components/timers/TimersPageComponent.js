module.exports = {
    template: '#timers-page-template',
    data: function () {
        return {
            date: store.state.date,
            timers: [],
            activities: [],
            timersFilter: false,
            activitiesFilter: '',
            activitiesWithDurationsForTheWeek: [],
            activitiesWithDurationsForTheDay: [],
            timerInProgress: false,
            showTimerInProgress: true,
        };
    },
    filters: {
        formatDateTime: function (dateTime, format) {
            if (!format) {
                return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format('hh:mm:ssa DD/MM');
            }
            else if (format === 'seconds') {
                return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format('ss a DD/MM');
            }
            else if (format === 'hoursAndMinutes') {
                return moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format('hh:mm');
            }
            else if (format === 'object') {
                return {
                    seconds: moment(dateTime, 'YYYY-MM-DD HH:mm:ss').format('ss')
                };
            }
        },
        doubleDigits: function (number) {
            if (number < 10) {
                return '0' + number;
            }

            return number;
        },
        formatDuration: function (minutes) {
            return FiltersRepository.formatDuration(minutes);
        }
    },
    components: {},
    methods: {

        /**
         *
         * @param timer
         */
        showTimerPopup: function (timer) {
            $.event.trigger('show-timer-popup', [timer]);
        },

        /**
         *
         */
        getActivities: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/activities').then(function (response) {
                this.activities = response;
                $.event.trigger('activities-loaded');
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        getTimers: function () {
            $.event.trigger('show-loading');
            var url = TimersRepository.calculateUrl(false, this.date.sql);

            this.$http.get(url).then(function (response) {
                this.timers = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
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
        getTotalMinutesForActivitiesForTheDay: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/activities/getTotalMinutesForDay?date=' + this.date.sql).then(function (response) {
                this.activitiesWithDurationsForTheDay = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        getTotalMinutesForActivitiesForTheWeek: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/activities/getTotalMinutesForWeek?date=' + this.date.sql).then(function (response) {
                this.activitiesWithDurationsForTheWeek = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        ///**
        // *
        // */
        //showNewManualTimerPopup: function () {
        //    $.event.trigger('show-new-manual-timer-popup');
        //},

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('date-changed', function (event) {
                that.getTimers();
                that.getTotalMinutesForActivitiesForTheDay();
                that.getTotalMinutesForActivitiesForTheWeek();
            });

            $(document).on('timer-deleted', function (event, timer) {
                var index = HelpersRepository.findIndexById(that.timers, timer.id);
                that.timers = _.without(that.timers, that.timers[index]);
                that.getTotalMinutesForActivitiesForTheDay();
                that.getTotalMinutesForActivitiesForTheWeek();
            });

            $(document).on('timer-stopped', function (event) {
                that.getTotalMinutesForActivitiesForTheDay();
                that.getTotalMinutesForActivitiesForTheWeek();
            });

            $(document).on('manual-timer-created', function (event) {
                that.getTotalMinutesForActivitiesForTheDay();
                that.getTotalMinutesForActivitiesForTheWeek();
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            $.event.trigger('hide-loading');
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getActivities();
        this.getTimers();
        this.getTotalMinutesForActivitiesForTheDay();
        this.getTotalMinutesForActivitiesForTheWeek();
        this.listen();
    }
};