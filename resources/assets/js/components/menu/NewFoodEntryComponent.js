var NewFoodEntry = Vue.component('new-food-entry', {
    template: '#new-food-entry-template',
    data: function () {
        return {
            newIngredient: {
                food: {
                    units: {
                        data: []
                    },
                    defaultUnit: {
                        data: {}
                    }
                },
                unit: {}
            }
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        addIngredientToRecipe: function () {
            if (this.recipeIsTemporary) {
                $.event.trigger('add-ingredient-to-temporary-recipe', [this.newIngredient]);
            }
            else {
                $.event.trigger('show-loading');

                var data = {
                    addIngredient: true,
                    food_id: this.newIngredient.food.id,
                    unit_id: this.newIngredient.unit.id,
                    quantity: this.newIngredient.quantity,
                    description: this.newIngredient.description
                };

                this.$http.put('/api/recipes/' + this.selectedRecipe.id, data, function (response) {
                        this.selectedRecipe.ingredients.data.push({
                            food: {
                                data: {
                                    name: this.newIngredient.food.name
                                }
                            },
                            unit: {
                                data: {
                                    name: this.newIngredient.unit.name
                                }
                            },
                            quantity: this.newIngredient.quantity,
                            description: this.newIngredient.description,
                        });
                        $.event.trigger('provide-feedback', ['Food added', 'success']);
                        $.event.trigger('hide-loading');
                    })
                    .error(function (response) {
                        this.handleResponseError(response);
                    });
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
        'date',
        'selectedRecipe',
        'recipeIsTemporary'
    ],
    events: {
        'option-chosen': function (option) {
            this.newIngredient.food = option;
            this.newIngredient.unit = option.defaultUnit.data;
        }
    },
    ready: function () {

    }
});