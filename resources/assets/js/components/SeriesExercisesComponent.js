var SeriesExercises = Vue.component('series-exercises', {
    template: '#series-exercises-template',
    data: function () {
        return {
            array: [1,2,3]
        };
    },
    components: {},
    filters: {
        filterExercises: function (exercises) {
            var that = this;

            //Sort
            exercises = _.chain(exercises).sortBy('priority').sortBy('stepNumber').value();

            //Filter
            return exercises.filter(function (exercise) {
                var filteredIn = true;

                //Priority filter
                if (that.priorityFilter && exercise.priority != that.priorityFilter) {
                    filteredIn = false;
                }

                return filteredIn;
            });
        }
    },
    methods: {
        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            this.$broadcast('response-error', response);
            this.showLoading = false;
        }
    },
    props: [
        'selectedSeries',
        'priorityFilter'
    ],
    ready: function () {

    }
});
