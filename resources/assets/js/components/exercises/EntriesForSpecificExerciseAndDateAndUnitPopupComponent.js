module.exports = {
    template: '#entries-for-specific-exercise-and-date-and-unit-popup-template',
    data: function () {
        return {
            showPopup: false,
            entries: {}
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
            store.showLoading();

            var data = {
                date: this.date.sql,
                exercise_id: entry.exercise.data.id,
                exercise_unit_id: entry.unit.id
            };

            this.$http.get('api/exerciseEntries/specificExerciseAndDateAndUnit', data).then(function (response) {
                this.entries = response;
                this.showPopup = true;
                store.hideLoading();
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        deleteExerciseEntry: function (entry) {
            if (confirm("Are you sure?")) {
                store.showLoading();
                this.$http.delete('/api/exerciseEntries/' + entry.id).then(function (response) {
                    this.entries = _.without(this.entries, entry);
                    //This might be unnecessary to do each time, and it fetches a lot
                    //of data for just deleting one entry.
                    //Perhaps do it when the popup closes instead?
                    store.getExerciseEntriesForTheDay();
                    $.event.trigger('provide-feedback', ['Entry deleted', 'success']);
                    store.hideLoading();
                }, function (response) {
                    HelpersRepository.handleResponseError(response);
                });
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
        }
    },
    props: [
        'date'
    ],
    ready: function () {
        this.listen();
    }
};
