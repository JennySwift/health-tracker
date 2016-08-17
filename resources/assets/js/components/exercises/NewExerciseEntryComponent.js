module.exports = {
    template: '#new-exercise-entry-template',
    data: function () {
        return {
            newEntry: {},
            shared: store.state
        };
    },
    components: {},
    computed: {
        units: function () {
          return this.shared.exerciseUnits;
        }
    },
    methods: {

        /**
         *
         */
        insertEntry: function () {
            store.showLoading();

            var data = {
                date: this.date.sql,
                exercise_id: this.newEntry.id,
                quantity: this.newEntry.quantity,
                unit_id: this.newEntry.unit.id
            };

            this.$http.post('/api/exerciseEntries', data).then(function (response) {
                $.event.trigger('exercise-entry-added', [response]);
                $.event.trigger('provide-feedback', ['Entry created', 'success']);
                store.hideLoading();
            }, function (response) {
                HelpersRepository.handleResponseError(response);
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
        'date'
    ],
    events: {
        'option-chosen': function (option) {
            this.newEntry = option;
            this.newEntry.unit = option.defaultUnit.data;
            this.newEntry.quantity = option.defaultQuantity;
        }
    },
    ready: function () {

    }
};