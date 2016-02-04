var ActivitiesPage = Vue.component('activities-page', {
    template: '#activities-page-template',
    data: function () {
        return {
            activities: [],
            newActivity: {},
            editingActivity: false,
            selectedActivity: {}
        };
    },
    components: {},
    filters: {
        formatDuration: function (minutes) {
            return FiltersRepository.formatDuration(minutes);
        }
    },
    methods: {

        /**
        *
        */
        getActivities: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/activities', function (response) {
                this.activities = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
        *
        */
        insertActivity: function () {
            $.event.trigger('show-loading');
            var data = {
                name: this.newActivity.name,
                color: this.newActivity.color
            };

            this.$http.post('/api/activities', data, function (response) {
                this.activities.push(response);
                $.event.trigger('provide-feedback', ['Activity created', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
        *
        */
        updateActivity: function (activity) {
            $.event.trigger('show-loading');

            var data = {
                name: activity.name,
                color: activity.color
            };

            this.$http.put('/api/activities/' + activity.id, data, function (response) {
                var index = _.indexOf(this.activities, _.findWhere(this.activities, {id: activity.id}));
                this.activities[index] = response;
                this.editingActivity = false;
                $.event.trigger('provide-feedback', ['Activity updated', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         *
         * @param activity
         */
        showEditActivity: function (activity) {
            this.editingActivity = true;
            this.selectedActivity = activity;
        },

        /**
        *
        */
        deleteActivity: function (activity) {
            if (confirm("Are you sure? The timers for the activity will be deleted, too!")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/activities/' + activity.id, function (response) {
                    this.activities = _.without(this.activities, activity);
                    $.event.trigger('provide-feedback', ['Activity deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
            }
        },

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
        //data to be received from parent
    ],
    ready: function () {
        this.getActivities();
    }
});
