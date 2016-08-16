module.exports = {
    template: '#new-exercise-entry-template',
    data: function () {
        return {
            newEntry: {},
            units: []
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        getUnits: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/exerciseUnits').then(function (response) {
                this.units = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        insertEntry: function () {
            $.event.trigger('show-loading');

            //this.newEntry.exercise.unit_id = $("#exercise-unit").val();
            //$("#exercise").val("").focus();

            var data = {
                date: this.date.sql,
                exercise_id: this.newEntry.id,
                quantity: this.newEntry.quantity,
                unit_id: this.newEntry.unit.id
            };

            this.$http.post('/api/exerciseEntries', data).then(function (response) {
                //this.exerciseEntries = response;
                $.event.trigger('exercise-entry-added', [response]);
                $.event.trigger('provide-feedback', ['Entry created', 'success']);
                $.event.trigger('hide-loading');
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
        this.getUnits();
    }
};