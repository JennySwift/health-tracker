var Vue = require('vue');

module.exports = {
    template: '#weight-template',
    data: function () {
        return {
            weight: {},
            editingWeight: false,
            addingNewWeight: false,
            newWeight: {},
            store: store.state
        };
    },
    computed: {
        date: function () {
          return this.store.date;
        }
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
            store.showLoading();
            var data = {
                date: this.date.sql,
                weight: this.newWeight.weight
            };

            this.$http.post('/api/weights', data).then(function (response) {
                this.weight = response.data;
                this.addingNewWeight = false;
                $.event.trigger('provide-feedback', ['Weight created', 'success']);
                store.hideLoading();
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        updateWeight: function () {
            store.showLoading();

            var data = {
                weight: this.weight.weight
            };

            this.$http.put('/api/weights/' + this.weight.id, data).then(function (response) {
                this.weight = response.data;
                this.editingWeight = false;
                $.event.trigger('provide-feedback', ['Weight updated', 'success']);
                store.hideLoading();
            }, function (response) {
                HelpersRepository.handleResponseError(response);
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
            store.showLoading();
            this.$http.get('api/weights/' + this.date.sql).then(function (response) {
                this.weight = response.data;
                store.hideLoading();
            }, function (response) {
                HelpersRepository.handleResponseError(response);
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
            $.event.trigger('response-error', [response]);
            this.showLoading = false;
        }
    },
    props: [

    ],
    ready: function () {
        $("#weight").val("");
        this.getWeightForTheDay();
        this.listen();
    }
};
