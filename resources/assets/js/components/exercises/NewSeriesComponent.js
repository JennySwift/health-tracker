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
            var data = {
                name: this.newSeries.name
            };

            HelpersRepository.post('/api/exerciseSeries', data, 'Series created', function (response) {
                store.add(response.data.data, 'exerciseSeries');
                this.newSeries.name = '';
            }.bind(this));
        }
    },
    props: [
        'showNewSeriesFields',
    ],
    ready: function () {

    }
};