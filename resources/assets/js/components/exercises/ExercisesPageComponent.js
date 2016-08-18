var ExercisesRepository = require('../../repositories/ExercisesRepository');

module.exports = {
    template: '#exercises-page-template',
    data: function () {
        return {
            date: store.state.date,
            exerciseSeriesHistory: [],
            showNewSeriesFields: false,
            showNewExerciseFields: false,
            selectedSeries: {
                exercises: {
                    data: []
                }
            },
            showExerciseEntryInputs: false,
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
        },
        programs: function () {
            return this.shared.programs;
        },
        exerciseSeries: function () {
            return this.shared.exerciseSeries;
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
            store.insertExerciseSet(exercise);
        },

        /**
         *
         */
        showExercisePopup: function (exercise) {
            HelpersRepository.get('/api/exercises/' + exercise.id, function (response) {
                $.event.trigger('show-exercise-popup', response.data);
            }.bind(this));
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
        getExerciseSeriesHistory: function (key) {
            //Find the series. The exercises were grouped according to series, so all we have is the series name (key).
            var series = _.find(this.exerciseSeries, function (series) {
                return series.name === key;
            });

            HelpersRepository.get('api/seriesEntries/' + series.id, function (response) {
                //For displaying the name of the series in the popup
                this.selectedSeries = series;
                this.exerciseSeriesHistory = response.data;
                $.event.trigger('show-series-history-popup');
            }.bind(this));
        },

        /**
         *
         */
        getExercisesInSeries: function (series) {
            HelpersRepository.get('/api/exerciseSeries/' + series.id, function (response) {
                this.selectedSeries = response.data;
            }.bind(this));
        },

        /**
         *
         */
        showExerciseSeriesPopup: function (key) {
            //Find the series. The exercises were grouped according to series, so all we have is the series name (key).
            var series = _.find(this.exerciseSeries, function (series) {
                return series.name === key;
            });

            HelpersRepository.get('/api/exerciseSeries/' + series.id, function (response) {
                this.selectedSeries = response.data;
                $.event.trigger('show-series-popup');
            }.bind(this));
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        
    }
};

