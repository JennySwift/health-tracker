var DatesRepository = require('../repositories/DatesRepository');

module.exports = {
    template: '#entries-page-template',
    data: function () {
        return {
            date: store.state.date,
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
            // enquire.register("screen and (max-width: 890px", {
            //     match: function () {
            //         $("#avg-calories-for-the-week-text").text('Avg: ');
            //     },
            //     unmatch: function () {
            //         $("#avg-calories-for-the-week-text").text('Avg calories (last 7 days): ');
            //     }
            // });
        },

        /**
         * Get calories for the day and average calories for 7 days
         */
        getCalorieInfoForTheDay: function () {
            HelpersRepository.get('api/calories/' + this.date.sql, function (response) {
                this.calories.day = response.data.forTheDay;
                this.calories.averageFor7Days = response.data.averageFor7Days;
            }.bind(this));
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
};