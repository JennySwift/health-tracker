var EntriesPage = Vue.component('entries-page', {
    template: '#entries-page-template',
    data: function () {
        return {
            date: DatesRepository.setDate(this.date),
            calories: {
                day: caloriesForTheDay,
                averageFor7Days: calorieAverageFor7Days,
            }
        }
    },
    components: {},
    filters: {
        roundNumber: function (number, howManyDecimals) {
            return FiltersRepository.roundNumber(number, howManyDecimals);
        }
    },
    methods: {
        /**
         *
         */
        mediaQueries: function () {
            enquire.register("screen and (max-width: 890px", {
                match: function () {
                    $("#avg-calories-for-the-week-text").text('Avg: ');
                },
                unmatch: function () {
                    $("#avg-calories-for-the-week-text").text('Avg calories (last 7 days): ');
                }
            });
        },

        /**
         * Get all the user's entries for the current date:
         * exercise entries
         * menu entries
         * weight
         * calories for the day
         * calorie average for the week
         */
        //getEntries: function () {
        //    $.event.trigger('get-entries');
        //},

        /**
         * Get calories for the day and average calories for 7 days
         */
        getCalorieInfoForTheDay: function () {
            $.event.trigger('show-loading');
            this.$http.get('api/calories/' + this.date.sql, function (response) {
                    this.calories.day = response.forTheDay;
                    this.calories.averageFor7Days = response.averageFor7Days;
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('get-entries', function (event) {
                that.getCalorieInfoForTheDay();
            });
            $(document).on('date-changed', function (event) {
                that.getCalorieInfoForTheDay();
            });
            $(document).on('menu-entry-added, menu-entry-deleted', function (event) {
                that.getCalorieInfoForTheDay();
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
        $("#food").val("");
        this.mediaQueries();
        this.listen();
    }
});