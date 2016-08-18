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

            HelpersRepository.put('/api/activities/' + this.selectedActivity.id, data, 'Activity updated', function (response) {
                store.update(response.data, 'activities');
                this.showPopup = false;
            }.bind(this));
        },

        /**
        * Todo: the timers will be deleted, too.
        */
        deleteActivity: function () {
            if (confirm("Really? The timers for the activity will be deleted, too.")) {
                HelpersRepository.delete('/api/activities/' + this.selectedActivity.id, 'Activity deleted', function (response) {
                    store.delete(this.selectedActivity, 'activities');
                    this.showPopup = false;
                }.bind(this));
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
                that.selectedActivity = HelpersRepository.clone(activity);
                that.showPopup = true;
            });
        }
    },
    props: [

    ],
    ready: function () {
        this.listen();
    }
};