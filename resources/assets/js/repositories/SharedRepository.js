var HelpersRepository = require('./HelpersRepository');
var TimersRepository = require('./TimersRepository');
require('sugar');

module.exports = {

    state: {
        exercises: [],
        date: {
            typed: Date.create('today').format('{dd}/{MM}/{yyyy}'),
            long: HelpersRepository.formatDateToLong('today'),
            sql: HelpersRepository.formatDateToSql('today')
        },
        selectedExercise: {
            program: {},
            series: {},
            defaultUnit: {
                data: {}
            }
        },
        exerciseUnits: [],
        programs: [],
        activities: [],
        timers: []
    },

    /**
     *
     */
    getExercises: function (that) {
        $.event.trigger('show-loading');
        that.$http.get('/api/exercises').then(function (response) {
            store.state.exercises = response.data;
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
     */
    getTimers: function (that) {
        $.event.trigger('show-loading');
        var url = TimersRepository.calculateUrl(false, this.state.date.sql);

        that.$http.get(url).then(function (response) {
            store.state.timers = response.data;
            $.event.trigger('hide-loading');
        }, function (response) {
            HelpersRepository.handleResponseError(response);
        });
    },
    
    /**
     * 
     * @param timer
     */
    addTimer: function (timer, timerIsManual) {
        console.log(obvious('timer here is ' + timer));
        if (store.state.date.sql === HelpersRepository.formatDateToSql() || timerIsManual) {
            console.log(obvious('did we make it?'));
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
    getActivities: function (that) {
        $.event.trigger('show-loading');
        that.$http.get('/api/activities').then(function (response) {
            store.state.activities = response.data;
            $.event.trigger('activities-loaded');
            $.event.trigger('hide-loading');
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
     */
    getExerciseUnits: function (that) {
        $.event.trigger('show-loading');
        that.$http.get('/api/exerciseUnits').then(function (response) {
            store.state.exerciseUnits = response.data;
            $.event.trigger('hide-loading');
        }, function (response) {
            HelpersRepository.handleResponseError(response);
        });
    },

    /**
     *
     */
    getExercisePrograms: function (that) {
        $.event.trigger('show-loading');
        that.$http.get('/api/exercisePrograms').then(function (response) {
            store.state.programs = response.data;
            $.event.trigger('hide-loading');
        }, function (response) {
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