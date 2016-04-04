var ActivitiesPage = Vue.component('activities-page', {
    template: '#activities-page-template',
    data: function () {
        return {
            activities: [],
            newActivity: {},
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
                HelpersRepository.handleResponseError(response);
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
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param activity
         */
        showActivityPopup: function (activity) {
            $.event.trigger('show-activity-popup', [activity]);
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
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
