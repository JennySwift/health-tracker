var SeriesPage = Vue.component('series-page', {
    template: '#series-page-template',
    data: function () {
        return {
            date: DatesRepository.setDate(this.date),
            exerciseSeries: [],
            exerciseSeriesHistory: [],
            priorityFilter: '',
            showNewSeriesFields: false,
            showNewExerciseFields: false,
            selectedSeries: {
                exercises: {
                    data: []
                }
            },
            showExerciseEntryInputs: false,
            units: [],
            programs: [],
            shared: store.state,
            selectedExercise: ExercisesRepository.selectedExercise,
            showStretches: false
        };
    },
    components: {},
    computed: {
        // exercisesBySeries: function () {
        //     var that = this;
        //     var groupedExercises = this.filterExercises(this.shared.exercises);
        //
        //     if (!this.showStretches) {
        //         groupedExercises = _.filter(groupedExercises, function (exercise) {
        //             return !exercise.stretch;
        //         });
        //     }
        //
        //     groupedExercises = _.groupBy(groupedExercises, function (exercise) {
        //         return exercise.series.name;
        //     });
        //
        //     return groupedExercises;
        // },
    },
    filters: {
        filterExercises: function (exercises) {
            var that = this;

            // exercises = exercises.sort(
            //     firstBy(function (exercise) {
            //         return exercise.stepNumber;
            //     })
            //         // .thenBy("population")
            //         // .thenBy("id")
            // );

            // exercises = exercises.sort(function (exercise) {
            //     return exercise.lastDone;
            // });
            //
            //Sort
            exercises = _.chain(exercises)
                .sortBy(function (exercise) {return exercise.stepNumber})
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
                if (that.priorityFilter && exercise.priority != that.priorityFilter) {
                    filteredIn = false;
                }

                if (!that.showStretches && exercise.stretch) {
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
                date: moment().format('YYYY-MM-DD'),
                exercise_id: exercise.id,
                exerciseSet: true
            };

            this.$http.post('/api/exerciseEntries', data, function (response) {
                $.event.trigger('provide-feedback', ['Set added', 'success']);
                $.event.trigger('get-exercise-entries-for-the-day');
                $.event.trigger('hide-loading');
            })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },

        /**
         *
         */
        showExercisePopup: function (exercise) {
            $.event.trigger('show-loading');
            this.$http.get('/api/exercises/' + exercise.id, function (response) {
                this.selectedExercise = response;
                $.event.trigger('show-exercise-popup');
                $.event.trigger('hide-loading');
            })
                .error(function (response) {
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
        getExerciseSeriesHistory: function (key) {
            $.event.trigger('show-loading');

            //Find the series. The exercises were grouped according to series, so all we have is the series name (key).
            var series = _.find(this.exerciseSeries, function (series) {
                return series.name === key;
            });

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
        showExerciseSeriesPopup: function (key) {
            //Find the series. The exercises were grouped according to series, so all we have is the series name (key).
            var series = _.find(this.exerciseSeries, function (series) {
                return series.name === key;
            });

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

