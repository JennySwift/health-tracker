module.exports = {
    template: '#exercise-entries-template',
    data: function () {
        return {
            showExerciseEntryInputs: false,
            selectedExercise: {
                unit: {}
            },
            shared: store.state
        };
    },
    computed: {
        date: function () {
          return this.shared.date;
        },
        exerciseEntries: function () {
            return this.shared.exerciseEntries;
        }
    },
    components: {},
    methods: {

        /**
         *
         * @param entry
         */
        showEntriesForSpecificExerciseAndDateAndUnitPopup: function (entry) {
            $.event.trigger('show-entries-for-specific-exercise-and-date-and-unit-popup', [entry]);
        },

        /**
         * 
         * @param exercise
         */
        insertExerciseSet: function (exercise) {
            store.insertExerciseSet(exercise.data);
        },

        /**
         *
         */
        listen: function () {
            $(document).on('date-changed', function (event) {
                store.getExerciseEntriesForTheDay();
            });
        }
    },
    props: [
        
    ],
    ready: function () {
        this.listen();
        store.getExerciseEntriesForTheDay();
    }
};