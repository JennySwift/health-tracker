module.exports = {
    template: '#new-exercise-template',
    data: function () {
        return {
            newExercise: {},
            shared: store.state
        };
    },
    components: {},
    computed: {
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
    methods: {

        /**
         *
         */
        insertExercise: function () {
            store.showLoading();
            var data = ExercisesRepository.setData(this.newExercise);

            this.$http.post('/api/exercises', data).then(function (response) {
                if (this.exercises) {
                    //If adding new exercise from the series page,
                    //this.exercises isn't specified
                    this.exercises.push(response);
                }

                $.event.trigger('provide-feedback', ['Exercise created', 'success']);
                store.hideLoading();
            }, function (response) {
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
        'showNewExerciseFields'
    ],
    ready: function () {

    }
};
