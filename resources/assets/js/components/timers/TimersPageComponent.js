var TimersRepository = require('../../repositories/TimersRepository');
var moment = require('moment');

module.exports = {
    template: '#timers-page-template',
    data: function () {
        return {
            date: store.state.date,
            timersFilter: false,
            activitiesFilter: '',
            activitiesWithDurationsForTheWeek: [],
            activitiesWithDurationsForTheDay: [],
            shared: store.state
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
                this.activitiesWithDurationsForTheDay = response.data;
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
                this.activitiesWithDurationsForTheWeek = response.data;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('date-changed', function (event) {
                store.getTimers(that);
                that.getTotalMinutesForActivitiesForTheDay();
                that.getTotalMinutesForActivitiesForTheWeek();
            });

            $(document).on('timer-deleted', function (event, timer) {
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
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getTotalMinutesForActivitiesForTheDay();
        this.getTotalMinutesForActivitiesForTheWeek();
        this.listen();
    }
};