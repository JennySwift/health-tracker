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
            var data = {
                date: this.date.sql,
                exercise_id: this.newEntry.id,
                quantity: this.newEntry.quantity,
                unit_id: this.newEntry.unit.id
            };
            
            HelpersRepository.post('/api/exerciseEntries', data, 'Entry created', function (response) {
                store.getExerciseEntriesForTheDay();
            }.bind(this));
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