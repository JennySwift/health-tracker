var SeriesPage = Vue.component('series-page', {
    template: '#series-page-template',
    data: function () {
        return {
            date: DatesRepository.setDate(this.date),
            exerciseSeries: [],
            exerciseSeriesHistory: [],
            priorityFilter: 1,
            showNewSeriesFields: false,
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
            series = _.chain(series).sortBy('priority').sortBy('lastDone').partition('lastDone').flatten().value();

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
        *
        */
        getSeries: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseSeries', function (response) {
                this.exerciseSeries = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
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
                this.handleResponseError(response);
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
                this.handleResponseError(response);
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
                this.handleResponseError(response);
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
                this.handleResponseError(response);
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
                    this.handleResponseError(response);
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
        this.getPrograms();
        this.getUnits();
        this.getSeries();
    }
});

