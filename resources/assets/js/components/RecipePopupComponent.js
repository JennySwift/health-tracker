var RecipePopup = Vue.component('recipe-popup', {
    template: '#recipe-popup-template',
    data: function () {
        return {
            showRecipePopup: false,
            selectedRecipe: {},
            newIngredient: {
                food: {}
            },
            editingMethod: false
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-recipe-popup', function (event, recipe) {
                that.showPopup(recipe);
            });
        },

        /**
         *
         */
        showPopup: function (recipe) {
            this.selectedRecipe = recipe;
            this.showRecipePopup = true;
        },

        /**
         *
         */
        closePopup: function ($event) {
            if ($event.target.className === 'popup-outer') {
                this.showRecipePopup = false;
            }
        },
        
        /**
        *
        */
        updateRecipe: function () {
            $.event.trigger('show-loading');

            var string = $("#edit-recipe-method").html();
            var lines = RecipesRepository.formatString(string, $("#edit-recipe-method")).items;
            var steps = [];

            $(lines).each(function () {
                steps.push(this);
            });

            this.selectedRecipe.steps = steps;

            var data = {
                name: this.selectedRecipe.name,
                steps: this.selectedRecipe.steps,
                tag_ids: this.selectedRecipe.tag_ids
            };

            this.$http.put('/api/recipes/' + this.selectedRecipe.id, data, function (response) {
                var index = _.indexOf(this.recipes, _.findWhere(this.recipes, {id: this.selectedRecipe.id}));
                this.recipes[index].name = response.name;
                this.recipes[index].tags = response.tags;
                this.recipes[index].tag_ds = response.tag_ids;
                this.editingMethod = false;
                this.showRecipePopup = false;
                $.event.trigger('provide-feedback', ['Recipe updated', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         *
         */
        toggleEditMethod: function () {
            //Toggle the visibility of the wysywig
            this.editingMethod = !this.editingMethod;

            //If we are editing the recipe, prepare the html of the wysiwyg
            if (this.editingMethod) {
                var text;
                var string = "";

                //convert the array into a string so I can make the wysiwyg display the steps
                $(this.selectedRecipe.steps).each(function () {
                    text = this.text;
                    text = text + '<br>';
                    string+= text;
                });
                $("#edit-recipe-method").html(string);
            }
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
        'tags',
        'recipes'
    ],
    ready: function () {
        this.listen();
    }
});
