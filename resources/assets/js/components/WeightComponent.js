var Weight = Vue.component('weight', {
    template: '#weight-template',
    data: function () {
        return {
            weight: weight,
            editingWeight: false,
            addingNewWeight: false,
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
         * @param keycode
         */
        insertOrUpdateWeight: function (keycode) {
            if (keycode === 13) {
                this.insertWeight();
            }
        },

        /**
         *
         */
        insertWeight: function () {
            $.event.trigger('show-loading');

            var data = {
                date: this.date.sql,
                weight: $("#weight").val()
            };

            this.$http.post('insert/weight', data, function (response) {
                    this.weight = response;
                    this.editWeight = false;
                    $("#weight").val("");
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
        showNewWeightFields: function () {
            this.addingNewWeight = true;
            this.editingWeight = false;
        },

        /**
         *
         */
        editWeight: function () {
            this.editWeight = true;
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
        this.listen();
    }
});
