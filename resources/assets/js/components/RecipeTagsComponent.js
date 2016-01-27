var RecipeTags = Vue.component('recipe-tags', {
    template: '#recipe-tags-template',
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
