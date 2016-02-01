var NewItemWithAutocomplete = Vue.component('new-item-with-autocomplete', {
    template: '#new-item-with-autocomplete-template',
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
            },
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
            if (keycode !== 13 && keycode !== 38 && keycode !== 40 && keycode !== 39 && keycode !== 37) {
                //not enter, up, down, right or left arrows
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
            else if (keycode === 13) {
                this.respondToEnter();
            }
        },

        /**
         *
         */
        populateOptions: function () {
            //fill the dropdown
            $.event.trigger('show-loading');
            this.$http.get('/api/foods?typing=' + this.newIngredient.food.name, function (response) {
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
         */
        respondToEnter: function () {
            if (this.showDropdown) {
                //enter is for the autocomplete
                this.selectOption();
            }
            else {
                //enter is to add the entry
                $scope.addFoodToRecipe();
            }
        },

        /**
         *
         */
        selectOption: function () {
            this.newIngredient.food = this.autocompleteOptions[this.currentIndex];
            this.newIngredient.unit = this.newIngredient.food.defaultUnit.data;
            this.showDropdown = false;
        },

        /**
         *
         */
        insertItem: function () {
            this.insertItemFunction(this.newIngredient);
            this.newIngredient.description = '';
            this.newIngredient.quantity = '';

            $("#new-ingredient-food-name").focus();
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
        'selectedRecipe',
        'insertItemFunction'
    ],
    ready: function () {

    }
});