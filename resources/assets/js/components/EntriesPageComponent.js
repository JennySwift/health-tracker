var EntriesPage = Vue.component('entries-page', {
    template: '#entries-page-template',
    data: function () {
        return {
            weight: weight,
            calories: {
                day: caloriesForTheDay,
                AverageFor7Days: calorieAverageFor7Days,
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
            date: {}
        }
    },
    components: {},
    watch: {
        'date.typed': function (newValue, oldValue) {
            this.date.sql = Date.parse(this.date.typed).toString('yyyy-MM-dd');
            this.date.long = Date.parse(this.date.typed).toString('ddd dd MMM yyyy');
            $("#date").val(newValue);

            if (newValue === oldValue) {
                // this.pageLoad();
            }
            else {
                this.getEntries();
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
        getEntries: function () {
            $.event.trigger('get-entries');
        },

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
         * @param $number
         */
        goToDate: function ($number) {
            this.date.typed = DatesRepository.goToDate(this.date.typed, $number);
        },

        /**
         *
         */
        today: function () {
            this.date.typed = DatesRepository.today();
        },

        /**
         *
         * @param keycode
         */
        changeDate: function (keycode) {
            if (keycode === 13) {
                this.date.typed = DatesRepository.changeDate($("#date").val());
            }
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
                $.event.trigger('show-loading');
                this.getWeightForTheDay();
                this.getCalorieInfoForTheDay();
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

        if (this.date.typed === undefined) {
            this.date.typed = Date.parse('today').toString('dd/MM/yyyy');
        }
        this.date.long = Date.parse(this.date.typed).toString('dd MMM yyyy');
    }
});