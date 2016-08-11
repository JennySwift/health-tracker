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



        // this.exercises[index].name = response.data.name;
        // this.exercises[index].description = response.data.description;
        // this.exercises[index].step = response.data.step;
        // this.exercises[index].series = response.data.series;
        // this.exercises[index].defaultQuantity = response.data.defaultQuantity;
        // this.exercises[index].defaultUnit = response.data.defaultUnit;
        // this.exercises[index].target = response.data.target;
        // this.exercises[index].priority = response.data.priority;
        // this.exercises[index].program = response.data.program;
    },
};