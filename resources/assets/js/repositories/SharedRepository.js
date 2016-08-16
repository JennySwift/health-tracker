var HelpersRepository = require('./HelpersRepository');
require('sugar');

module.exports = {

    state: {
        exercises: [],
        date: {
            typed: Date.create('today').format('{dd}/{MM}/{yyyy}'),
            long: HelpersRepository.formatDateToLong('today'),
            sql: HelpersRepository.formatDateToSql('today')
        }
    },

    /**
     *
     */
    getExercises: function (that) {
        $.event.trigger('show-loading');
        that.$http.get('/api/exercises').then(function (response) {
            store.state.exercises = response;
            $.event.trigger('hide-loading');
        }, function (response) {
            HelpersRepository.handleResponseError(response);
        });
        // that.$http.get('/api/exercises', function (response) {
        //
        // })
        // .error(function (response) {
        //
        // });
    },

    /**
    *
    * @param exercise
    */
    updateExercise: function (exercise) {
        var index = HelpersRepository.findIndexById(this.state.exercises, exercise.id);
        this.state.exercises.$set(index, exercise);
    },

    /**
     * 
     * @param date
     */
    setDate: function (date) {
        this.state.date.typed = Date.create(date).format('{dd}/{MM}/{yyyy}');
        this.state.date.long = HelpersRepository.formatDateToLong(date);
        this.state.date.sql = HelpersRepository.formatDateToSql(date);
    }
};