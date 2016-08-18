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
            $.event.trigger('show-loading');

            var data = {
                start: this.selectedTimer.start,
                finish: this.selectedTimer.finish,
                activity_id: this.selectedTimer.activity.data.id
            };

            HelpersRepository.put('/api/timers/' + this.selectedTimer.id, data, 'Timer updated', function (response) {
                store.updateTimer(response.data);
                this.showPopup = false;
            }.bind(this));
        },

        /**
        *
        */
        deleteTimer: function () {
            HelpersRepository.delete('/api/timers/' + this.selectedTimer.id, 'Timer deleted', function (response) {
                store.deleteTimer(this.selectedTimer);
                $.event.trigger('timer-deleted', [this.selectedTimer]);
                this.showPopup = false;
            }.bind(this));
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
                that.selectedTimer = HelpersRepository.clone();
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
