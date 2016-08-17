module.exports = {
    template: '#food-units-page-template',
    data: function () {
        return {
            units: [],
            newUnit: {}
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        getUnits: function () {
            store.showLoading();
            this.$http.get('/api/foodUnits').then(function (response) {
                this.units = response;
                store.hideLoading();
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        insertUnit: function () {
            store.showLoading();
            var data = {
                name: this.newUnit.name
            };

            this.$http.post('/api/foodUnits', data).then(function (response) {
                this.units.push(response);
                $.event.trigger('provide-feedback', ['Unit created', 'success']);
                store.hideLoading();
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        deleteUnit: function (unit) {
            if (confirm("Are you sure?")) {
                store.showLoading();
                this.$http.delete('/api/foodUnits/' + unit.id).then(function (response) {
                    this.units = _.without(this.units, unit);
                    //var index = _.indexOf(this.units, _.findWhere(this.units, {id: this.unit.id}));
                    //this.units = _.without(this.units, this.units[index]);
                    $.event.trigger('provide-feedback', ['Unit deleted', 'success']);
                    store.hideLoading();
                }, function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
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
        this.getUnits();
    }
};

