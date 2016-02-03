var ExercisePopup = Vue.component('exercise-popup', {
    template: '#exercise-popup-template',
    data: function () {
        return {
            showPopup: false
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        updateExercise: function () {
            $.event.trigger('show-loading');

            var data = ExercisesRepository.setData(this.selectedExercise);

            this.$http.put('/api/exercises/' + this.selectedExercise.id, data, function (response) {
                    this.selectedExercise = response.data;
                    var index = _.indexOf(this.exercises, _.findWhere(this.exercises, {id: this.selectedExercise.id}));

                    this.exercises[index].name = response.data.name;
                    this.exercises[index].description = response.data.description;
                    this.exercises[index].step = response.data.step;
                    this.exercises[index].series = response.data.series;
                    this.exercises[index].defaultQuantity = response.data.defaultQuantity;
                    this.exercises[index].defaultUnit = response.data.defaultUnit;
                    this.exercises[index].target = response.data.target;
                    this.exercises[index].priority = response.data.priority;
                    this.exercises[index].program = response.data.program;


                    this.showPopup = false;
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
        deleteExercise: function () {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/exercises/' + this.selectedExercise.id, function (response) {
                    this.exercises = _.without(this.exercises, this.selectedExercise);
                    $.event.trigger('provide-feedback', ['Exercise deleted', 'success']);
                    this.showPopup = false;
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
        closePopup: function ($event) {
            if ($event.target.className === 'popup-outer') {
                this.showPopup = false;
            }
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-exercise-popup', function (event) {
                that.showPopup = true;
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
        'selectedExercise',
        'exercises',
        'exerciseSeries',
        'programs',
        'units'
    ],
    ready: function () {
        this.listen();
    }
});
