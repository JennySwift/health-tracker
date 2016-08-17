module.exports = {
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
            store.showLoading();

            var data = ExercisesRepository.setData(this.selectedExercise);

            this.$http.put('/api/exercises/' + this.selectedExercise.id, data).then(function (response) {
                this.selectedExercise = response.data;
                store.updateExercise(response.data);


                this.showPopup = false;
                $.event.trigger('provide-feedback', ['Exercise updated', 'success']);
                store.hideLoading();
                $("#exercise-step-number").val("");
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        deleteExercise: function () {
            if (confirm("Are you sure?")) {
                store.showLoading();
                this.$http.delete('/api/exercises/' + this.selectedExercise.id).then(function (response) {
                    var index = _.indexOf(this.exercises, _.findWhere(this.exercises, {id: this.selectedExercise.id}));
                    this.exercises = _.without(this.exercises, this.exercises[index]);
                    $.event.trigger('provide-feedback', ['Exercise deleted', 'success']);
                    this.showPopup = false;
                    store.hideLoading();
                }, function (response) {
                    HelpersRepository.handleResponseError(response);
                });
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
};
