var NewSeries = Vue.component('new-series', {
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

            this.$http.post('/api/exerciseSeries', data, function (response) {
                    this.exerciseSeries.push(response.data);
                    $.event.trigger('provide-feedback', ['Series created', 'success']);
                    this.showLoading = false;
                    this.newSeries.name = '';
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
        'showNewSeriesFields',
        'exerciseSeries'
    ],
    ready: function () {

    }
});
