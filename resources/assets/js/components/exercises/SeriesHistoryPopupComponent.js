var SeriesHistoryPopup = Vue.component('series-history-popup', {
    template: '#series-history-popup-template',
    data: function () {
        return {
            showPopup: false
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        closePopup: function ($event) {
            if ($event.target.className === 'popup-outer') {
                this.showPopup = false;
            }
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-series-history-popup', function (event, series) {
                that.selectedSeries = series;
                that.showPopup = true;
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
        'exerciseSeriesHistory',
        'selectedSeries'
    ],
    ready: function () {
        this.listen();
    }
});
