var NewIngredient = Vue.component('new-ingredient', {
    template: '#new-ingredient-template',
    data: function () {
        return {
            newIngredient: {},
            autocompleteOptions: [],
            showDropdown: false,
            currentIndex: 0
        };
    },
    components: {},
    methods: {

        /**
         *
         * @param keycode
         */
        respondToKeyup: function (keycode) {
            if (keycode !== 13 && keycode !== 38 && keycode !== 40) {
                //not enter, up arrow or down arrow
                this.populateOptions();
            }
            else if (keycode === 38) {
                //up arrow pressed
                if (this.currentIndex !== 0) {
                    this.currentIndex--;
                }
            }
            else if (keycode === 40) {
                //down arrow pressed
                if (this.autocompleteOptions.length - 1 !== this.currentIndex) {
                    this.currentIndex++;
                }
            }
        },

        /**
         *
         */
        populateOptions: function () {
            //fill the dropdown
            $.event.trigger('show-loading');
            this.$http.get('/api/foods?typing=' + this.newIngredient.foodName, function (response) {
                this.autocompleteOptions = response.data;
                this.showDropdown = true;
                this.currentIndex = 0;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                this.handleResponseError(response);
            });
        },

        /**
         *
         * @param $array
         * @param $set_focus
         */
        finishFoodAutocomplete: function ($array, $set_focus) {
            //array, input_to_focus, autocomplete_to_hide, input_to_fill, selected_property_to_define
            var $selected = _.findWhere($array, {selected: true});
            $scope.recipe_popup.food = $selected;
            $scope.selected.food = $selected;
            $scope.show.autocomplete_options.foods = false;
            $($set_focus).val("").focus();
        },

        /**
         *
         */
        insertOrAutocompleteFoodEntry: function ($keycode) {
            if ($keycode !== 13) {
                return;
            }
            //enter is pressed
            if ($scope.show.autocomplete_options.foods) {
                //enter is for the autocomplete
                $scope.finishFoodAutocomplete($scope.recipe_popup.autocomplete_options, $("#recipe-popup-food-quantity"));
            }
            else {
                // if enter is to add the entry
                $scope.insertFoodIntoRecipe();
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
        'recipeName'
    ],
    ready: function () {

    }
});



//$scope.insertFoodIntoRecipe = function () {
//    //we are adding a food to a permanent recipe
//    var $data = {
//        recipe_id: $scope.recipe_popup.recipe.id,
//        food_id: $scope.selected.food.id,
//        unit_id: $("#recipe-popup-unit").val(),
//        quantity: $scope.recipe_popup.food.quantity,
//        description: $scope.recipe_popup.food.description
//    };
//
//    RecipesFactory.insertFoodIntoRecipe($data).then(function (response) {
//        $scope.recipe_popup = response.data;
//    });
//    $("#recipe-popup-food-input").val("").focus();
//    $scope.recipe_popup.food.description = "";
//};
