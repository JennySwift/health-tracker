var RecipesPage = Vue.component('recipes-page', {
    template: '#recipes-page-template',
    data: function () {
        return {
            tags: recipe_tags,
            recipes: recipes,
            recipesTagFilter: []
        };
    },
    components: {},
    computed: {
        //recipesTagFilter: function () {
        //    return _.pluck(this.tags, 'id');
        //}
    },
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
