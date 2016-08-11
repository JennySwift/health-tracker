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
};