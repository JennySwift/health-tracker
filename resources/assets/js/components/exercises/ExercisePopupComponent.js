var ExercisesRepository = require('../../repositories/ExercisesRepository');

module.exports = {
    template: '#exercise-popup-template',
    data: function () {
        return {
            showPopup: false,
            selectedExercise: {
                program: {},
                series: {},
                defaultUnit: {
                    data: {}
                }
            },
            shared: store.state
        };
    },
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
    components: {},
    methods: {

        /**
        *
        */
        updateExercise: function () {
            var data = ExercisesRepository.setData(this.selectedExercise);

            HelpersRepository.put('/api/exercises/' + this.selectedExercise.id, data, 'Exercise updated', function (response) {
                this.selectedExercise = response.data.data;
                store.update(response.data.data, 'exercises');
                this.showPopup = false;
                $("#exercise-step-number").val("");
            }.bind(this));
        },

        /**
        *
        */
        deleteExercise: function () {
            HelpersRepository.delete('/api/exercises/' + this.selectedExercise.id, 'Exercise deleted', function (response) {
                store.delete(this.selectedExercise, 'exercises');
                this.showPopup = false;
                router.go(this.redirectTo);
            }.bind(this));
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-exercise-popup', function (event, exercise) {
                that.selectedExercise = HelpersRepository.clone(exercise);
                that.showPopup = true;
            });
        }
    },
    props: [

    ],
    ready: function () {
        this.listen();
    }
};
