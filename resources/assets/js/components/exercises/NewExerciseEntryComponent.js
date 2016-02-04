var NewExerciseEntry = Vue.component('new-exercise-entry', {
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
            this.$http.get('/api/exerciseUnits', function (response) {
                this.units = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
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
                unit_id: this.newEntry.unit_id
            };

            this.$http.post('/api/exerciseEntries', data, function (response) {
                    //this.exerciseEntries = response;
                    $.event.trigger('provide-feedback', ['Entry created', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    this.handleResponseError(response);
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
});
