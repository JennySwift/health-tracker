module.exports = {
    template: '#activity-popup-template',
    data: function () {
        return {
            showPopup: false,
            selectedActivity: {}
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        updateActivity: function () {
            $.event.trigger('show-loading');

            var data = {
                name: this.selectedActivity.name,
                color: this.selectedActivity.color
            };

            this.$http.put('/api/activities/' + this.selectedActivity.id, data).then(function (response) {
                var index = _.indexOf(this.activities, _.findWhere(this.activities, {id: this.selectedActivity.id}));
                store.updateActivity(response.data);
                this.showPopup = false;
                $.event.trigger('provide-feedback', ['Activity updated', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        deleteActivity: function () {
            if (confirm("Are you sure? The timers for the activity will be deleted, too!")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/activities/' + this.selectedActivity.id).then(function (response) {
                    store.deleteActivity(this.selectedActivity);
                    this.showPopup = false;
                    $.event.trigger('provide-feedback', ['Activity deleted', 'success']);
                    $.event.trigger('hide-loading');
                }, function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },

        /**
         *
         */
        closePopup: function ($event) {
            HelpersRepository.closePopup($event, this);
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-activity-popup', function (event, activity) {
                that.selectedActivity = activity;
                that.showPopup = true;
            });
        }
    },
    props: [
        'activities'
    ],
    ready: function () {
        this.listen();
    }
};