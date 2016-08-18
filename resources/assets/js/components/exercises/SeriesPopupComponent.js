module.exports = {
    template: '#series-popup-template',
    data: function () {
        return {
            showPopup: false,
            selectedSeries: {}
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

            HelpersRepository.put('/api/exerciseSeries/' + this.selectedSeries.id, data, 'Series updated', function (response) {
                store.update(response.data, 'exerciseSeries');
                this.showPopup = false;
            }.bind(this));
        },

        /**
        *
        */
        deleteSeries: function () {
            HelpersRepository.delete('/api/exerciseSeries/' + this.selectedSeries.id, 'Series deleted', function (response) {
                store.delete(this.selectedSeries, 'exerciseSeries');
            }.bind(this));
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
        }
    },
    props: [

    ],
    ready: function () {
        this.listen();
    }
};