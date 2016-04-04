var App = Vue.component('app', {

});

var router = new VueRouter({
    hashbang: false
});

router.map({
    '/': {
        component: EntriesPage,
        //subRoutes: {
        //    //default for if no id is specified
        //    '/': {
        //        component: Item
        //    },
        //    '/:id': {
        //        component: Item
        //    }
        //}
    },
    '/entries': {
        component: EntriesPage,
        //subRoutes: {
        //    //default for if no id is specified
        //    '/': {
        //        component: Item
        //    },
        //    '/:id': {
        //        component: Item
        //    }
        //}
    },
    '/exercises': {
        component: ExercisesPage
    },
    '/series': {
        component: SeriesPage
    },
    '/exercise-units': {
        component: ExerciseUnitsPage
    },
    '/foods': {
        component: FoodsPage
    },
    '/recipes': {
        component: RecipesPage
    },
    '/journal': {
        component: JournalPage
    },
    '/food-units': {
        component: FoodUnitsPage
    },
    '/timers': {
        component: TimersPage,
        subRoutes: {
            //'/': {
            //    component: TimersPage
            //},
            '/new-manual': {
                component: NewManualTimer
            }
        }
    },
    '/activities': {
        component: ActivitiesPage
    },
    '/graphs': {
        component: GraphsPage
    },
});

router.start(App, 'body');

//new Vue({
//    el: 'body',
//    events: {
//
//    }
//});


