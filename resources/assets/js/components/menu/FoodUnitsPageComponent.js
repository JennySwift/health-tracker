var FoodUnitsPage = Vue.component('food-units-page', {
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
            $.event.trigger('show-loading');
            this.$http.get('/api/foodUnits', function (response) {
                this.units = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        insertUnit: function () {
            $.event.trigger('show-loading');
            var data = {
                name: this.newUnit.name
            };

            this.$http.post('/api/foodUnits', data, function (response) {
                this.units.push(response);
                $.event.trigger('provide-feedback', ['Unit created', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        deleteUnit: function (unit) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/foodUnits/' + unit.id, function (response) {
                    this.units = _.without(this.units, unit);
                    //var index = _.indexOf(this.units, _.findWhere(this.units, {id: this.unit.id}));
                    //this.units = _.without(this.units, this.units[index]);
                    $.event.trigger('provide-feedback', ['Unit deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
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
});

