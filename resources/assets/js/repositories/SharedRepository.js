var store = {

    state: {
        exercises: []
    },

    /**
     *
     */
    getExercises: function (that) {
        $.event.trigger('show-loading');
        that.$http.get('/api/exercises', function (response) {
            store.state.exercises = response;
            $.event.trigger('hide-loading');
        })
        .error(function (response) {
            HelpersRepository.handleResponseError(response);
        });
    },

    /**
    *
    * @param exercise
    */
    updateExercise: function (exercise) {
        var index = HelpersRepository.findIndexById(this.state.exercises, exercise.id);
        this.state.exercises.$set(index, exercise);
    },
};