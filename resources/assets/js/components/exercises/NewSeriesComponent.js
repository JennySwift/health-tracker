module.exports = {
    template: '#new-series-template',
    data: function () {
        return {
            newSeries: {}
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        insertSeries: function () {
            $.event.trigger('show-loading');
            var data = {
                name: this.newSeries.name
            };

            this.$http.post('/api/exerciseSeries', data).then(function (response) {
                this.exerciseSeries.push(response.data);
                $.event.trigger('provide-feedback', ['Series created', 'success']);
                this.showLoading = false;
                this.newSeries.name = '';
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
        'showNewSeriesFields',
        'exerciseSeries'
    ],
    ready: function () {

    }
};