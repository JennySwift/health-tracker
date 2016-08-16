var TimerPopup = Vue.component('timer-popup', {
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
            $.event.trigger('show-loading');

            var data = {
                start: this.selectedTimer.start,
                finish: this.selectedTimer.finish,
                activity_id: this.selectedTimer.activity.data.id
            };

            this.$http.put('/api/timers/' + this.selectedTimer.id, data).then(function (response) {
                var index = _.indexOf(this.timers, _.findWhere(this.timers, {id: this.selectedTimer.id}));
                this.timers[index].start = response.start;
                this.timers[index].finish = response.finish;
                this.timers[index].activity = response.activity;
                $.event.trigger('provide-feedback', ['Timer updated', 'success']);
                this.showPopup = false;
                $.event.trigger('hide-loading');
            }, function (data, status, response) {
                HelpersRepository.handleResponseError(data, status, response);
            });
        },

        /**
         *
         */
        deleteTimer: function () {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/timers/' + this.selectedTimer.id).then(function (response) {
                    $.event.trigger('timer-deleted', [this.selectedTimer]);
                    this.showPopup = false;
                    $.event.trigger('provide-feedback', ['Timer deleted', 'success']);
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
});
