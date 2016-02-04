var Weight = Vue.component('weight', {
    template: '#weight-template',
    data: function () {
        return {
            weight: {},
            editingWeight: false,
            addingNewWeight: false,
            newWeight: {}
        };
    },
    components: {},
    filters: {
        roundNumber: function (number, howManyDecimals) {
            return FiltersRepository.roundNumber(number, howManyDecimals);
        }
    },
    methods: {

        /**
         *
         */
        showNewWeightOrEditWeightFields: function () {
            if (this.weight.id) {
                this.showEditWeightFields();
            }
            else {
                this.showNewWeightFields();
            }
        },

        /**
        *
        */
        insertWeight: function () {
            $.event.trigger('show-loading');
            var data = {
                date: this.date.sql,
                weight: this.newWeight.weight
            };

            this.$http.post('/api/weights', data, function (response) {
                this.weight = response;
                this.addingNewWeight = false;
                $.event.trigger('provide-feedback', ['Weight created', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
        *
        */
        updateWeight: function () {
            $.event.trigger('show-loading');

            var data = {
                weight: this.weight.weight
            };

            this.$http.put('/api/weights/' + this.weight.id, data, function (response) {
                this.weight = response;
                this.editingWeight = false;
                $.event.trigger('provide-feedback', ['Weight updated', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         *
         */
        showNewWeightFields: function () {
            this.addingNewWeight = true;
            this.editingWeight = false;
        },

        /**
         *
         */
        showEditWeightFields: function () {
            this.editingWeight = true;
            this.addingNewWeight = false;
            setTimeout(function () {
                $("#weight").focus();
            }, 500);
        },

        /**
         *
         */
        getWeightForTheDay: function () {
            $.event.trigger('show-loading');
            this.$http.get('api/weights/' + this.date.sql, function (response) {
                    this.weight = response;
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('get-entries', function (event) {
                that.getWeightForTheDay();
            });
            $(document).on('date-changed', function (event) {
                that.getWeightForTheDay();
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
        'date'
    ],
    ready: function () {
        $("#weight").val("");
        this.getWeightForTheDay();
        this.listen();
    }
});
