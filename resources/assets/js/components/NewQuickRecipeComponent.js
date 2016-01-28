var NewQuickRecipe = Vue.component('new-quick-recipe', {
    template: '#new-quick-recipe-template',
    data: function () {
        return {
            errors: {},
            showPopup: false,
            showHelp: false,
            newRecipe: {
                similarNames: []
            }
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        toggleHelp: function () {
            this.showHelp = !this.showHelp;
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
        //data to be received from parent
    ],
    ready: function () {

    }
});
