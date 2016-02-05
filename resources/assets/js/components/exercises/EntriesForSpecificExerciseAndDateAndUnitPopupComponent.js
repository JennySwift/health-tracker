var EntriesForSpecificExerciseAndDateAndUnitPopup = Vue.component('entries-for-specific-exercise-and-date-and-unit-popup', {
    template: '#entries-for-specific-exercise-and-date-and-unit-popup-template',
    data: function () {
        return {
            showPopup: false,
            selectedExercise: {}
        };
    },
    components: {},
    methods: {

        /**
         * Get all the the user's entries for a particular exercise
         * with a particular unit on a particular date.
         * @param entry
         */
        getEntriesForSpecificExerciseAndDateAndUnit: function (entry) {
            $.event.trigger('show-loading');

            var data = {
                date: this.date.sql,
                exercise_id: entry.exercise.data.id,
                exercise_unit_id: entry.unit.id
            };

            this.$http.get('api/exerciseEntries/specificExerciseAndDateAndUnit', data, function (response) {
                    this.selectedExercise = response;
                    this.showPopup = true;
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    this.handleResponseError(response);
                });
        },

        /**
         *
         */
        deleteExerciseEntry: function (entry) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/exerciseEntries/' + entry.id, function (response) {
                        this.selectedExercise.entries = _.without(this.selectedExercise.entries, entry);
                        $.event.trigger('provide-feedback', ['Entry deleted', 'success']);
                        $.event.trigger('hide-loading');
                    })
                    .error(function (response) {
                        this.handleResponseError(response);
                    });
            }
        },

        /**
         *
         */
        closePopup: function ($event) {
            if ($event.target.className === 'popup-outer') {
                this.showPopup = false;
            }
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-entries-for-specific-exercise-and-date-and-unit-popup', function (event, entry) {
                that.getEntriesForSpecificExerciseAndDateAndUnit(entry);
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
        this.listen();
    }
});
