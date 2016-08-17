module.exports = {
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

            this.$http.put('/api/exerciseSeries/' + this.selectedSeries.id, data).then(function (response) {
                var index = _.indexOf(this.exerciseSeries, _.findWhere(this.exerciseSeries, {id: this.selectedSeries.id}));
                this.exerciseSeries[index].name = response.data.name;
                this.exerciseSeries[index].priority = response.data.priority;
                this.showPopup = false;
                $.event.trigger('provide-feedback', ['Series updated', 'success']);
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        deleteSeries: function () {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/exerciseSeries/' + this.selectedSeries.id).then(function (response) {
                    //this.exerciseSeries = _.without(this.exerciseSeries, this.selectedSeries);
                    var index = _.indexOf(this.exerciseSeries, _.findWhere(this.exerciseSeries, {id: this.selectedSeries.id}));
                    this.exerciseSeries = _.without(this.exerciseSeries, this.exerciseSeries[index]);
                    this.showPopup = false;
                    $.event.trigger('provide-feedback', ['Series deleted', 'success']);
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
            $.event.trigger('response-error', [response]);
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
};