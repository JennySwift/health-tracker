module.exports = {
    template: '#timer-popup-template',
    data: function () {
        return {
            showPopup: false,
            selectedTimer: {
                id: '',
                start: '',
                finish: '',
                activity: {
                    data: {}
                }
            }
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        updateTimer: function () {
            store.showLoading();

            var data = {
                start: this.selectedTimer.start,
                finish: this.selectedTimer.finish,
                activity_id: this.selectedTimer.activity.data.id
            };

            this.$http.put('/api/timers/' + this.selectedTimer.id, data).then(function (response) {
                var index = _.indexOf(this.timers, _.findWhere(this.timers, {id: this.selectedTimer.id}));
                store.updateTimer(response.data);
                $.event.trigger('provide-feedback', ['Timer updated', 'success']);
                this.showPopup = false;
                store.hideLoading();
            }, function (data, status, response) {
                HelpersRepository.handleResponseError(data, status, response);
            });
        },

        /**
         *
         */
        deleteTimer: function () {
            if (confirm("Are you sure?")) {
                store.showLoading();
                this.$http.delete('/api/timers/' + this.selectedTimer.id).then(function (response) {
                    store.deleteTimer(this.selectedTimer);
                    $.event.trigger('timer-deleted', [this.selectedTimer]);
                    this.showPopup = false;
                    $.event.trigger('provide-feedback', ['Timer deleted', 'success']);
                    store.hideLoading();
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
            $(document).on('show-timer-popup', function (event, timer) {
                //So that the timer doesn't appear updated if the user closes the popup without saving
                that.selectedTimer.id = timer.id;
                that.selectedTimer.start = timer.start;
                that.selectedTimer.finish = timer.finish;
                that.selectedTimer.activity = timer.activity;
                that.showPopup = true;
            });
        }
    },
    props: [
        'activities',
        'timers'
    ],
    ready: function () {
        this.listen();
    }
};