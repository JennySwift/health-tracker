var SpecificExerciseEntriesPopup = Vue.component('specific-exercise-entries-popup', {
    template: '#specific-exercise-entries-popup-template',
    data: function () {
        return {

        };
    },
    components: {},
    methods: {
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
        //data to be received from parent
    ],
    ready: function () {

    }
});
