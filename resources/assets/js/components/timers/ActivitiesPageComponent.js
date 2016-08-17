module.exports = {
    template: '#activities-page-template',
    data: function () {
        return {
            shared: store.state,
            newActivity: {},
        };
    },
    computed: {
        activities: function () {
          return this.shared.activities;
        }
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
        insertActivity: function () {
            $.event.trigger('show-loading');
            var data = {
                name: this.newActivity.name,
                color: this.newActivity.color
            };

            this.$http.post('/api/activities', data).then(function (response) {
                store.addActivity(response.data);
                $.event.trigger('provide-feedback', ['Activity created', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
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
        
    }
};
