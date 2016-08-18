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
     */
    getExercises: function () {
        store.showLoading();
        Vue.http.get('/api/exercises').then(function (response) {
            store.state.exercises = response.data;
            store.hideLoading();
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
     * Todo: all the entries I think are actually in the data (unnecessarily)
     */
    getExerciseEntriesForTheDay: function () {
        HelpersRepository.get('/api/exerciseEntries/' + this.state.date.sql, function (response) {
            store.state.exerciseEntries = response.data;
        }.bind(this));
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
     */
    getSeries: function () {
        HelpersRepository.get('/api/exerciseSeries', function (response) {
            store.state.exerciseSeries = response.data;
        }.bind(this));
    },

    /**
     *
     */
    getTimers: function () {
        store.showLoading();
        var url = TimersRepository.calculateUrl(false, this.state.date.sql);

        Vue.http.get(url).then(function (response) {
            store.state.timers = response.data;
            store.hideLoading();
        }, function (response) {
            HelpersRepository.handleResponseError(response);
        });
    },
    
    /**
     * 
     * @param timer
     */
    addTimer: function (timer, timerIsManual) {
        if (store.state.date.sql === HelpersRepository.formatDateToSql() || timerIsManual) {
            //Only add the timer if the date is on today or the timer is a manual entry
            store.state.timers.push(timer);
        }
    },
    
    /**
    *
    * @param timer
    */
    deleteTimer: function (timer) {
        this.state.timers = HelpersRepository.deleteById(this.state.timers, timer.id);
    },

    /**
    *
    * @param timer
    */
    updateTimer: function (timer) {
        var index = HelpersRepository.findIndexById(this.state.timers, timer.id);
        this.state.timers.$set(index, timer);
    },

    /**
     *
     */
    getActivities: function () {
        store.showLoading();
        Vue.http.get('/api/activities').then(function (response) {
            store.state.activities = response.data;
            $.event.trigger('activities-loaded');
            store.hideLoading();
        }, function (response) {
            HelpersRepository.handleResponseError(response);
        });
    },

    /**
     * 
     * @param activity
     */
    addActivity: function (activity) {
        store.state.activities.push(activity);
    },
    
    /**
    *
    * @param activity
    */
    updateActivity: function (activity) {
        var index = HelpersRepository.findIndexById(this.state.activities, activity.id);
        this.state.activities.$set(index, activity);
    },
    
    /**
    *
    * @param activity
    */
    deleteActivity: function (activity) {
        this.state.activities = HelpersRepository.deleteById(this.state.activities, activity.id);
    },

    /**
    *
    * @param series
    */
    deleteExerciseSeries: function (series) {
        this.state.exerciseSeries = HelpersRepository.deleteById(this.state.exerciseSeries, series.id);
    },

    /**
     *
     */
    getExerciseUnits: function () {
        store.showLoading();
        Vue.http.get('/api/exerciseUnits').then(function (response) {
            store.state.exerciseUnits = response.data;
            store.hideLoading();
        }, function (response) {
            HelpersRepository.handleResponseError(response);
        });
    },

    /**
     *
     * @param exerciseUnit
     */
    addExerciseUnit: function (exerciseUnit) {
        store.state.exerciseUnits.push(exerciseUnit);
    },

    /**
    *
    * @param exerciseUnit
    */
    updateExerciseUnit: function (exerciseUnit) {
        var index = HelpersRepository.findIndexById(this.state.exerciseUnits, exerciseUnit.id);
        this.state.exerciseUnits.$set(index, exerciseUnit);
    },

    /**
    *
    * @param exercise
    */
    deleteExercise: function (exercise) {
        this.state.exercises = HelpersRepository.deleteById(this.state.exercises, exercise.id);
    },

    /**
    *
    * @param exerciseUnit
    */
    deleteExerciseUnit: function (exerciseUnit) {
        this.state.exerciseUnits = HelpersRepository.deleteById(this.state.exerciseUnits, exerciseUnit.id);
    },

    /**
     *
     * @param exercise
     */
    addExercise: function (exercise) {
        store.state.exercises.push(exercise);
    },

    /**
     *
     * @param series
     */
    addSeries: function (series) {
        store.state.exerciseSeries.push(series);
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
    * @param exercise
    */
    updateExercise: function (exercise) {
        var index = HelpersRepository.findIndexById(this.state.exercises, exercise.id);
        this.state.exercises.$set(index, exercise);
    },

    /**
    *
    * @param series
    */
    updateExerciseSeries: function (series) {
        var index = HelpersRepository.findIndexById(this.state.exerciseSeries, series.id);
        this.state.exerciseSeries.$set(index, series);
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