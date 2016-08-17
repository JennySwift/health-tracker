
var Vue = require('vue');
var VueRouter = require('vue-router');
Vue.use(VueRouter);
global.$ = require('jquery');
global.jQuery = require('jquery');
global._ = require('underscore');
global.store = require('./repositories/SharedRepository');
var VueResource = require('vue-resource');
Vue.use(VueResource);
require('./config.js');
global.HelpersRepository = require('./repositories/HelpersRepository');
global.FiltersRepository = require('./repositories/FiltersRepository');
Date.setLocale('en-AU');

var App = Vue.component('app', {
    ready: function () {
        store.getExercises(this);
        store.getExerciseUnits(this);
        store.getExercisePrograms(this);
        store.getActivities(this);
    }
});

//Components
Vue.component('navbar', require('./components/exercises/NavbarComponent'));
Vue.component('feedback', require('./components/shared/FeedbackComponent'));
Vue.component('loading', require('./components/shared/LoadingComponent'));
Vue.component('date-navigation', require('./components/shared/DateNavigationComponent'));
Vue.component('autocomplete', require('./components/shared/AutocompleteComponent'));


Vue.component('weight', require('./components/WeightComponent'));
Vue.component('new-menu-entry', require('./components/menu/NewMenuEntryComponent'));
Vue.component('new-food-entry', require('./components/menu/NewFoodEntryComponent'));
Vue.component('menu-entries', require('./components/menu/MenuEntriesComponent'));
Vue.component('temporary-recipe-popup', require('./components/menu/TemporaryRecipePopupComponent'));
Vue.component('new-exercise', require('./components/exercises/NewExerciseComponent'));
Vue.component('new-exercise-entry', require('./components/exercises/NewExerciseEntryComponent'));
Vue.component('exercise-entries', require('./components/exercises/ExerciseEntriesComponent'));
Vue.component('entries-for-specific-exercise-and-date-and-unit-popup', require('./components/exercises/EntriesForSpecificExerciseAndDateAndUnitPopupComponent'));
Vue.component('series-history-popup', require('./components/exercises/SeriesHistoryPopupComponent'));
Vue.component('series-popup', require('./components/exercises/SeriesPopupComponent'));
Vue.component('new-series', require('./components/exercises/NewSeriesComponent'));
Vue.component('exercise-popup', require('./components/exercises/ExercisePopupComponent'));
Vue.component('new-sleep-entry', require('./components/NewSleepEntryComponent'));

Vue.component('timer-popup', require('./components/timers/TimerPopupComponent'));
Vue.component('new-timer', require('./components/timers/NewTimerComponent'));
Vue.component('activity-popup', require('./components/timers/ActivityPopupComponent'));

var router = new VueRouter({
    hashbang: false
});

router.map({
    '/': {
        component: require('./components/EntriesPageComponent'),
//         //subRoutes: {
//         //    //default for if no id is specified
//         //    '/': {
//         //        component: Item
//         //    },
//         //    '/:id': {
//         //        component: Item
//         //    }
//         //}
    },
    '/entries': {
        component: require('./components/EntriesPageComponent'),
    },
    '/exercises': {
        component: require('./components/exercises/ExercisesPageComponent')
    },
    '/exercise-units': {
        component: require('./components/exercises/ExerciseUnitsPageComponent')
    },
    '/foods': {
        component: require('./components/menu/FoodsPageComponent')
    },
    '/recipes': {
        component: require('./components/menu/RecipesPageComponent')
    },
    '/journal': {
        component: require('./components/JournalPageComponent')
    },
    '/food-units': {
        component: require('./components/menu/FoodUnitsPageComponent')
    },
    '/timers': {
        component: require('./components/timers/TimersPageComponent'),
        subRoutes: {
            //'/': {
            //    component: TimersPage
            //},
            '/new-manual': {
                component: require('./components/timers/NewManualTimerComponent')
            }
        }
    },
    '/activities': {
        component: require('./components/timers/ActivitiesPageComponent')
    },
    '/graphs': {
        component: require('./components/timers/GraphsPageComponent')
    },
});

router.start(App, 'body');




