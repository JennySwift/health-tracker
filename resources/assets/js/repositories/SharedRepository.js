var Vue = require('vue');
var HelpersRepository = require('./HelpersRepository');
var TimersRepository = require('./TimersRepository');
require('sugar');

module.exports = {

    state: {
        exercises: [],
        exerciseSeries: [],
        exerciseEntries: [],
        date: {
            typed: Date.create('today').format('{dd}/{MM}/{yyyy}'),
            long: HelpersRepository.formatDateToLong('today'),
            sql: HelpersRepository.formatDateToSql('today')
        },
        exerciseUnits: [],
        programs: [],
        activities: [],
        timers: [],
        loading: false
    },

    /**
     *
     */
    showLoading: function () {
        this.state.loading = true;
    },

    /**
     *
     */
    hideLoading: function () {
        this.state.loading = false;
    },

    /**
     *
     * @param date
     */
    setDate: function (date) {
        this.state.date.typed = Date.create(date).format('{dd}/{MM}/{yyyy}');
        this.state.date.long = HelpersRepository.formatDateToLong(date);
        this.state.date.sql = HelpersRepository.formatDateToSql(date);
    },

    /**
     *
     */
    getExercises: function () {
        HelpersRepository.get('/api/exercises', false, 'exercises');
    },

    /**
     * Todo: all the entries I think are actually in the data (unnecessarily)
     */
    getExerciseEntriesForTheDay: function () {
        HelpersRepository.get('/api/exerciseEntries/' + this.state.date.sql, false, 'exerciseEntries');
    },

    /**
     *
     */
    getSeries: function () {
        HelpersRepository.get('/api/exerciseSeries', false, 'exerciseSeries');
    },

    /**
     *
     */
    getTimers: function () {
        var url = TimersRepository.calculateUrl(false, this.state.date.sql);
        HelpersRepository.get(url, false, 'timers');
    },

    /**
     *
     * @param data
     * @param propertyName
     */
    set: function (data, propertyName) {
        store.state[propertyName] = data;
    },

    /**
     *
     */
    getActivities: function () {
        HelpersRepository.get('/api/activities', function (response) {
            store.state.activities = response.data;
            $.event.trigger('activities-loaded');
        }.bind(this));
    },

    /**
     *
     */
    getExerciseUnits: function () {
        HelpersRepository.get('/api/exerciseUnits', false, 'exerciseUnits');
    },

    /**
     *
     */
    getExercisePrograms: function () {
        HelpersRepository.get('/api/exercisePrograms', function (response) {
            store.state.programs = response.data;
        });
    },

    /**
     * 
     * @param timer
     * @param timerIsManual
     */
    addTimer: function (timer, timerIsManual) {
        if (store.state.date.sql === HelpersRepository.formatDateToSql() || timerIsManual) {
            //Only add the timer if the date is on today or the timer is a manual entry
            store.state.timers.push(timer);
        }
    },

    /**
     *
     */
    insertExerciseSet: function (exercise) {
        var data = {
            date: this.state.date.sql,
            exercise_id: exercise.id,
            exerciseSet: true
        };

        HelpersRepository.post('/api/exerciseEntries', data, 'Set added', function (response) {
            store.state.exerciseEntries = response.data;
            exercise.lastDone = 0;
        }.bind(this));
    },

    /**
     *
     * @param item
     * @param propertyName
     */
    add: function (item, propertyName) {
        store.state[propertyName].push(item);
    },

    /**
     *
     * @param item
     * @param propertyName
     */
    update: function (item, propertyName) {
        var index = HelpersRepository.findIndexById(this.state[propertyName], item.id);
        this.state[propertyName].$set(index, item);
    },

    /**
     *
     * @param item
     * @param propertyName
     */
    delete: function (item, propertyName) {
        this.state[propertyName] = HelpersRepository.deleteById(this.state[propertyName], item.id);
    }
};