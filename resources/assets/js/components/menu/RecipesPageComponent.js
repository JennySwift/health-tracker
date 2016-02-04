var RecipesPage = Vue.component('recipes-page', {
    template: '#recipes-page-template',
    data: function () {
        return {
            tags: [],
            recipes: [],
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
        */
        getTags: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/recipeTags', function (response) {
                this.tags = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
        *
        */
        getRecipes: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/recipes', function (response) {
                this.recipes = response;
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
        //data to be received from parent
    ],
    ready: function () {
        this.getRecipes();
        this.getTags();
    }
});
