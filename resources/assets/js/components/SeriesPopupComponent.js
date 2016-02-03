var SeriesPopup = Vue.component('series-popup', {
    template: '#series-popup-template',
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
        updateSeries: function () {
            $.event.trigger('show-loading');

            var data = {
                name: this.selectedSeries.name,
                priority: this.selectedSeries.priority,
                workout_ids: this.selectedSeries.workout_ids
            };

            this.$http.put('/api/exerciseSeries/' + this.selectedSeries.id, data, function (response) {
                    var index = _.indexOf(this.exerciseSeries, _.findWhere(this.exerciseSeries, {id: this.selectedSeries.id}));
                    this.exerciseSeries[index].name = response.data.name;
                    this.exerciseSeries[index].priority = response.data.priority;
                    this.showPopup = false;
                    $.event.trigger('provide-feedback', ['Series updated', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },

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
            $(document).on('show-series-popup', function (event, series) {
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
        'selectedSeries',
        'exerciseSeries'
    ],
    ready: function () {
        this.listen();
    }
});
