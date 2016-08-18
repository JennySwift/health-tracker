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
            var data = ExercisesRepository.setData(this.newExercise);

            HelpersRepository.post('/api/exercises', data, 'Exercise created', function (response) {
                store.addExercise(response.data);
            }.bind(this));
        },
    },
    props: [
        'showNewExerciseFields'
    ],
    ready: function () {

    }
};
