var SeriesPage = Vue.component('series-page', {
    template: '#series-page-template',
    data: function () {
        return {
            date: DatesRepository.setDate(this.date),
            exerciseSeries: series,
            exerciseSeriesHistory: [],
            workouts: workouts,
            priorityFilter: 1,
            showNewSeriesFields: false,
            newSeries: {},
            newExercise: {},
            selectedSeries: {
                exercises: {
                    data: []
                }
            },
            selectedExercise: {
                unit: {}
            },
            showExerciseEntryInputs: false
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
        insertSeries: function () {
            $.event.trigger('show-loading');
            var data = {
                name: this.newSeries.name
            };

            this.$http.post('/api/exerciseSeries', data, function (response) {
                this.exerciseSeries.push(response.data);
                $.event.trigger('provide-feedback', ['Series created', 'success']);
                this.showLoading = false;
                this.newSeries.name = '';
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

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
                this.showLoading = false;
                this.newSeries.name = '';
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
        *
        */
        deleteSeries: function (series) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/exerciseSeries/' + series.id, function (response) {
                    this.exerciseSeries = _.without(this.exerciseSeries, series);
                    $.event.trigger('provide-feedback', ['Series deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
            }
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
        showExercisePopup: function (exercise) {
            $.event.trigger('show-loading');
            this.$http.get('/api/exercises/' + exercise.id, function (response) {
                    this.selectedExercise = response;
                    $.event.trigger('show-exercise-popup');
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },

        /**
        *
        */
        updateExercise: function (exercise) {
            $.event.trigger('show-loading');

            var data = ExercisesRepository.setData(exercise);

            this.$http.put('/api/exercises/' + exercise.id, data, function (response) {
                this.selectedExercise = response.data;
                var index = _.indexOf(this.selectedSeries.exercises.data, _.findWhere(this.selectedSeries.exercises.data, {id: this.selectedExercise.id}));
                this.selectedSeries.exercises.data[index] = response.data;
                this.showExercisePopup = false;
                $.event.trigger('provide-feedback', ['Exercise updated', 'success']);
                $.event.trigger('hide-loading');
                $("#exercise-step-number").val("");
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
    }
});

