var DatesRepository = require('../../repositories/DatesRepository');

module.exports = {
    template: '#graphs-page-template',
    data: function () {
        return {
            date: store.state.date,
            timers: []
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        getTimers: function () {
            $.event.trigger('show-loading');
            var url = TimersRepository.calculateUrl(false, this.date.sql);

            this.$http.get(url).then(function (response) {
                this.timers = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
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
        this.getTimers();
    }
};

