var NewSleepEntry = Vue.component('new-sleep-entry', {
    template: '#new-sleep-entry-template',
    data: function () {
        return {
            date: DatesRepository.setDate(this.date),
            newSleepEntry: {
                startedYesterday: true
            },
            showPopup: false
        };
    },
    components: {},
    methods: {
        /**
         *
         */
        insertSleepEntry: function () {
            $.event.trigger('show-loading');
            var data = TimersRepository.setData(this.newSleepEntry, this.date.sql);

            this.$http.post('/api/timers', data, function (response) {
                    this.showPopup = false;
                    $.event.trigger('provide-feedback', ['Sleep entry created', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
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
            $(document).on('show-new-sleep-entry-popup', function (event) {
                that.showPopup = true;
            });
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.listen();
    }
});
