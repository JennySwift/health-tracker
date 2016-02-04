var EntriesPage = Vue.component('entries-page', {
    template: '#entries-page-template',
    data: function () {
        return {
            date: DatesRepository.setDate(this.date),
            weight: weight,
            calories: {
                day: caloriesForTheDay,
                averageFor7Days: calorieAverageFor7Days,
            },
            showAutocompleteOptions: {},
            newEntry: {
                exercise: {
                    unit: {}
                },
                exerciseUnit: {},
                menu: {},
                food: {},
            },
            autocompleteOptions: {
                exercises: {},
                menuItems: {},
                foods: {},
                temporaryRecipeFoods: {}
            },
            editWeight: false,
        }
    },
    components: {},
    filters: {
        roundNumber: function (number, howManyDecimals) {
            if (!howManyDecimals) {
                return Math.round(number);
            }
            else if (howManyDecimals === 'one') {
                var multiplyAndDivideBy = 10;
                return Math.round(number * multiplyAndDivideBy) / multiplyAndDivideBy;
            }
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
         *
         * @param keycode
         */
        insertOrUpdateWeight: function (keycode) {
            if (keycode === 13) {
                this.insertWeight();
            }
        },

        /**
         *
         */
        insertWeight: function () {
            $.event.trigger('show-loading');

            var data = {
                date: this.date.sql,
                weight: $("#weight").val()
            };

            this.$http.post('insert/weight', data, function (response) {
                    this.weight = response;
                    this.editWeight = false;
                    $("#weight").val("");
                    $.event.trigger('provide-feedback', ['Weight created', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },

        /**
         *
         */
        showNewWeightFields: function () {
            this.addingNewWeight = true;
            this.editingWeight = false;
        },

        /**
         *
         */
        editWeight: function () {
            this.editWeight = true;
            setTimeout(function () {
                $("#weight").focus();
            }, 500);
        },

        /**
         *
         */
        getWeightForTheDay: function () {
            $.event.trigger('show-loading');
            this.$http.get('api/weights/' + this.date.sql, function (response) {
                    this.weight = response;
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },

        /**
         * Get calories for the day and average calories for 7 days
         */
        getCalorieInfoForTheDay: function () {
            $.event.trigger('show-loading');
            this.$http.get('api/calories/' + this.date.sql, function (response) {
                    this.calories.day = response.forTheDay;
                    this.calories.week_avg = response.averageFor7Days;
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
                that.getWeightForTheDay();
                that.getCalorieInfoForTheDay();
            });
            $(document).on('date-changed', function (event) {
                that.getWeightForTheDay();
                that.getCalorieInfoForTheDay();
            });
            $(document).on('menu-entry-added', function (event) {
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
        $("#weight").val("");
        this.mediaQueries();
        this.listen();
    }
});