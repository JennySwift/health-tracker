var GraphsPage = Vue.component('graphs-page', {
    template: '#graphs-page-template',
    data: function () {
        return {
            date: DatesRepository.setDate(this.date),
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

            this.$http.get(url, function (response) {
                    this.timers = response;
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            this.$broadcast('response-error', response);
            this.showLoading = false;
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getTimers();
    }
});

