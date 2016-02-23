var SeriesPage = Vue.component('series-page', {
    template: '#series-page-template',
    data: function () {
        return {
            date: DatesRepository.setDate(this.date),
            exerciseSeries: [],
            exerciseSeriesHistory: [],
            priorityFilter: 1,
            showNewSeriesFields: false,
            showNewExerciseFields: false,
            selectedSeries: {
                exercises: {
                    data: []
                }
            },
            showExerciseEntryInputs: false,
            units: [],
            programs: []
        };
    },
    components: {},
    filters: {
        filterSeries: function (series) {
            var that = this;

            //Sort
            series = _.chain(series)
                .sortBy('priority')
                .sortBy('lastDone')
                .value();

            /**
             * @VP:
             * This method feels like a lot of code for just
             * a simple thing-ordering series by their lastDone value,
             * putting those with a null lastDone value on the end.
             * I tried underscore.js _.partition with _.flatten,
             * but it put 0 values on the end,
             * (I had trouble getting the predicate parameter of the _.partition method to work.)
             */
            series = this.moveLastDoneNullToEnd(series);

            //Filter
            return series.filter(function (thisSeries) {
                var filteredIn = true;

                //Priority filter
                if (that.priorityFilter && thisSeries.priority != that.priorityFilter) {
                    filteredIn = false;
                }

                return filteredIn;
            });
        }
    },
    methods: {

        /**
         * For the series filter
         * @param series
         * @returns {*}
         */
        moveLastDoneNullToEnd: function (series) {
            //Get the series that have lastDone null values
            var seriesWithNullLastDone = _.filter(series, function (oneSeries) {
                return oneSeries.lastDone == null;
            });

            //Remove the series that have lastDone null values
            for (var i = 0; i < seriesWithNullLastDone.length; i++) {
                var index = _.indexOf(series, _.findWhere(series, {id: seriesWithNullLastDone[i].id}));
                series = _.without(series, series[index]);
            }

            //Add the series that have lastDone null values back on the
            //end of the series array
            for (var i = 0; i < seriesWithNullLastDone.length; i++) {
                series.push(seriesWithNullLastDone[i]);
            }

            return series;
        },

        /**
        *
        */
        getSeries: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseSeries', function (response) {
                this.exerciseSeries = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        getExerciseSeriesHistory: function (series) {
            $.event.trigger('show-loading');

            this.$http.get('api/seriesEntries/' + series.id, function (response) {
                //For displaying the name of the series in the popup
                this.selectedSeries = series;
                this.exerciseSeriesHistory = response;
                $.event.trigger('show-series-history-popup');
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        getExercisesInSeries: function (series) {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseSeries/' + series.id, function (response) {
                this.selectedSeries = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        showExerciseSeriesPopup: function (series) {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseSeries/' + series.id, function (response) {
                this.selectedSeries = response;
                $.event.trigger('show-series-popup');
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        getPrograms: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/exercisePrograms', function (response) {
                this.programs = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        getUnits: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseUnits', function (response) {
                    this.units = response;
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getPrograms();
        this.getUnits();
        this.getSeries();
    }
});

