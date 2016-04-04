var ActivityPopup = Vue.component('activity-popup', {
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

            this.$http.put('/api/activities/' + this.selectedActivity.id, data, function (response) {
                var index = _.indexOf(this.activities, _.findWhere(this.activities, {id: this.selectedActivity.id}));
                this.activities[index] = response;
                this.showPopup = false;
                $.event.trigger('provide-feedback', ['Activity updated', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        deleteActivity: function () {
            if (confirm("Are you sure? The timers for the activity will be deleted, too!")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/activities/' + this.selectedActivity.id, function (response) {
                    this.activities = _.without(this.activities, this.selectedActivity);
                    this.showPopup = false;
                    $.event.trigger('provide-feedback', ['Activity deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
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
});
