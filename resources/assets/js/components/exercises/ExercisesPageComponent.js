var ExercisesRepository = require('../../repositories/ExercisesRepository');

module.exports = {
    template: '#exercises-page-template',
    data: function () {
        return {
            date: store.state.date,
            exerciseSeries: [],
            exerciseSeriesHistory: [],
            showNewSeriesFields: false,
            showNewExerciseFields: false,
            selectedSeries: {
                exercises: {
                    data: []
                }
            },
            showExerciseEntryInputs: false,
            programs: store.state.programs,
            shared: store.state,
            showStretches: false,
            filterByName: '',
            filterByDescription: '',
            filterByPriority: 1,
            filterBySeries: '',
            showFilters: false
        };
    },
    computed: {
        selectedExercise: function () {
          return this.shared.selectedExercise;
        },
        units: function () {
            return this.shared.exerciseUnits;
        }
    },
    components: {},
    filters: {
        filterExercises: function (exercises) {
            var that = this;

            //Sort
            exercises = _.chain(exercises)
                .sortBy(function (exercise) {return exercise.stepNumber})
                .sortBy(function (exercise) {return exercise.series.id})
                .sortBy('priority')
                .sortBy(function (exercise) {
                    return exercise.lastDone * -1
                })
                .partition(function (exercise) {
                    return exercise.lastDone === null;
                })
                .flatten()
                .value();

            //Filter
            return exercises.filter(function (exercise) {
                var filteredIn = true;

                //Priority filter
                if (that.filterByPriority && exercise.priority != that.filterByPriority) {
                    filteredIn = false;
                }

                //Name filter
                if (that.filterByName && exercise.name.indexOf(that.filterByName) === -1) {
                    filteredIn = false;
                }

                //Description filter
                if (exercise.description && exercise.description.indexOf(that.filterByDescription) === -1) {
                    filteredIn = false;
                }

                else if (!exercise.description && that.filterByDescription !== '') {
                    filteredIn = false;
                }

                //Stretches files
                if (!that.showStretches && exercise.stretch) {
                    filteredIn = false;
                }

                //Series filter
                if (that.filterBySeries && exercise.series.name != that.filterBySeries && that.filterBySeries !== 'all') {
                    filteredIn = false;
                }

                return filteredIn;
            });
        },

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
        },
    },
    methods: {

        /**
         *
         */
        insertExerciseSet: function (exercise) {
            $.event.trigger('show-loading');
            var data = {
                date: this.shared.date.sql,
                exercise_id: exercise.id,
                exerciseSet: true
            };

            this.$http.post('/api/exerciseEntries', data).then(function (response) {
                exercise.lastDone = 0;
                $.event.trigger('provide-feedback', ['Set added', 'success']);
                $.event.trigger('get-exercise-entries-for-the-day');
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        showExercisePopup: function (exercise) {
            $.event.trigger('show-loading');
            this.$http.get('/api/exercises/' + exercise.id).then(function (response) {
                this.selectedExercise = response.data;
                $.event.trigger('show-exercise-popup');
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

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
            this.$http.get('/api/exerciseSeries').then(function (response) {
                this.exerciseSeries = response.data;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        getExerciseSeriesHistory: function (key) {
            $.event.trigger('show-loading');

            //Find the series. The exercises were grouped according to series, so all we have is the series name (key).
            var series = _.find(this.exerciseSeries, function (series) {
                return series.name === key;
            });

            this.$http.get('api/seriesEntries/' + series.id).then(function (response) {
                //For displaying the name of the series in the popup
                this.selectedSeries = series;
                this.exerciseSeriesHistory = response.data;
                $.event.trigger('show-series-history-popup');
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        getExercisesInSeries: function (series) {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseSeries/' + series.id).then(function (response) {
                this.selectedSeries = response.data;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        showExerciseSeriesPopup: function (key) {
            //Find the series. The exercises were grouped according to series, so all we have is the series name (key).
            var series = _.find(this.exerciseSeries, function (series) {
                return series.name === key;
            });

            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseSeries/' + series.id).then(function (response) {
                this.selectedSeries = response.data;
                $.event.trigger('show-series-popup');
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getSeries();
    }
};

