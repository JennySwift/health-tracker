var FoodPopup = Vue.component('food-popup', {
    template: '#food-popup-template',
    data: function () {
        return {
            selectedFood: {
                food: {}
            }
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
