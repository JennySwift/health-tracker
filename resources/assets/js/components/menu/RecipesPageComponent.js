module.exports = {
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
            this.$http.get('/api/recipeTags').then(function (response) {
                this.tags = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        getRecipes: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/recipes').then(function (response) {
                this.recipes = response;
                $.event.trigger('hide-loading');
            }, function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param response
         */
        handleResponseError: function (response) {
            $.event.trigger('response-error', [response]);
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
};
