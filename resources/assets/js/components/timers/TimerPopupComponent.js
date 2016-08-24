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
                store.update(response.data, 'timers');
                this.showPopup = false;
            }.bind(this));
        },

        /**
        *
        */
        deleteTimer: function () {
            HelpersRepository.delete('/api/timers/' + this.selectedTimer.id, 'Timer deleted', function (response) {
                store.delete(this.selectedTimer, 'timers');
                $.event.trigger('timer-deleted', [this.selectedTimer]);
                this.showPopup = false;
            }.bind(this));
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-timer-popup', function (event, timer) {
                that.selectedTimer = HelpersRepository.clone(timer);
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
