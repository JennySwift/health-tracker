var RecipesRepository = {

    /**
     *
     * @returns {*}
     */
    getArrayOfIngredientsAndSteps: function () {
        var stringOfIngredientsAndSteps = this.formatString($("#quick-recipe").html());
        $("#quick-recipe").html(stringOfIngredientsAndSteps);
        return this.convertFormattedRecipeStringToArrayOfIngredientsAndSteps(stringOfIngredientsAndSteps);
    },

    /**
     *
     * @param arrayOfIngredientsAndSteps
     * @returns {*|Array.<T>|string|Blob|ArrayBuffer}
     */
    getIngredients: function (arrayOfIngredientsAndSteps) {
        var stepsIndex = this.getStepsIndex(arrayOfIngredientsAndSteps);
        return arrayOfIngredientsAndSteps.slice(0, stepsIndex);
    },

    /**
     *
     * @param arrayOfIngredientsAndSteps
     * @returns {*|Array.<T>|string|Blob|ArrayBuffer}
     */
    getSteps: function (arrayOfIngredientsAndSteps) {
        var stepsIndex = this.getStepsIndex(arrayOfIngredientsAndSteps);
        return arrayOfIngredientsAndSteps.slice(stepsIndex+1);
    },

    /**
     *
     * @param string
     * @returns {*}
     */
    convertFormattedRecipeStringToArrayOfIngredientsAndSteps: function (string) {
        //turn the string into an array of divs by first splitting at the br tags
        var array = string.split('<br>');

        //remove any empty elements from the array
        array = _.without(array, '');

        //Chrome was putting this line at the top. Remove that:
        //<!--?xml version="1.0" encoding="UTF-8" standalone="no"?-->
        if (array[0].indexOf('<!--') !== -1 && array[0].indexOf('-->') !== -1) {
            array.shift();
        }

        //Remove white space
        for (var i = 0; i < array.length; i++) {
            array[i] = array[i].trim();
        }

        return array;
    },

    /**
     * The string may contain unwanted br tags and
     * both opening and closing div tags.
     * Format the string into a string of div tags to
     * populate the html of the wysiwyg.
     * @param string
     * @returns {string|*}
     */
    formatString: function (string) {
        //Remove any closing div tags and replace any opening div tags with a br tag.
        while (string.indexOf('<div>') !== -1 || string.indexOf('</div>') !== -1) {
            string = string.replace('<div>', '<br>').replace('</div>', '');
        }

        //var formattedString = "";
        //var array = string.split('<br>');

        //make formattedString a string with div tags
        //for (var j = 0; j < array.length; j++) {
        //    formattedString += '<div>' + array[j] + '</div>';
        //}

        //string = formattedString;

        return string;
    },

    /**
     * Check for the possibilities of words that indicate
     * which line the steps starts on
     *
     * @param lines
     * @returns {*|number|Number}
     */
    getStepsIndex: function (lines) {
        var possibilities = [
            'steps',
            'preparation',
            'directions',
            'method'
        ];

        //Convert lines to lower case
        var linesLower = [];
        for (var i = 0; i < lines.length; i++) {
            linesLower.push(lines[i].toLowerCase());
        }

        //Find the index of the word that indicates the start of the steps
        for (var i = 0; i < possibilities.length; i++) {
            if (linesLower.indexOf(possibilities[i]) !== -1) {
                return linesLower.indexOf(possibilities[i]);
            }
            //Allow for colon after the word
            else if (linesLower.indexOf(possibilities[i] + ':') !== -1) {
                return linesLower.indexOf(possibilities[i] + ':');
            }
        }
    },

    /**
     * ingredients is an array of strings.
     * The string should include the quantity, unit, food, and description,
     * providing the user has entered them.
     * We want to take each string and turn it into an object with
     * food, unit, quantity and description properties.
     * Then return the new array of objects.
     * @param ingredients (array)
     * @returns {Array}
     */
    convertIngredientStringsToObjects: function (ingredients) {
        var ingredientsAsObjects = [];
        var that = this;

        $(ingredients).each(function () {
            var ingredientAsString = this;
            var ingredientAsObject = {};

            ingredientAsObject.description = that.getIngredientDescription(ingredientAsString);

            var quantityUnitAndFood = that.getIngredientQuantityUnitAndFood(ingredientAsString);

            //$line is now just the quantity, unit and food, without the description
            //split $line into an array with quantity, unit and food
            var split = quantityUnitAndFood.split(' ');

            //Add the quantity, unit and food to the ingredientAsObject
            ingredientAsObject.quantity = split[0];
            ingredientAsObject.unit = split[1];
            ingredientAsObject.food = split[2];

            //Add the item object to the items array
            ingredientsAsObjects.push(ingredientAsObject);
        });

        return ingredientsAsObjects;
    },


    /**
     * If there is a description,
     * separate the description from the quantity, unit and food
     * @param ingredientAsString
     * @returns {*}
     */
    getIngredientDescription: function (ingredientAsString) {
        var split = this.splitDescriptionFromQuantityUnitAndFood(ingredientAsString);

        if (split[1]) {
            return split[1].trim();
        }

        //There is no description
        return '';
    },

    /**
     *
     * @returns {*}
     */
    getIngredientQuantityUnitAndFood: function (ingredientAsString) {
        var split = this.splitDescriptionFromQuantityUnitAndFood(ingredientAsString);

        return split[0];
    },

    /**
     *
     * @param ingredientAsString
     * @returns {*}
     */
    splitDescriptionFromQuantityUnitAndFood: function (ingredientAsString) {
        if (ingredientAsString.indexOf(',') !== -1) {
            return ingredientAsString.split(',');
        }

        return [ingredientAsString];
    },

    /**
     *
     * @param ingredients
     * @returns {Array}
     */
    checkIngredientsForErrors: function (ingredients) {
        var lineNumber = 0;
        this.errors = [];
        var that = this;

        $(ingredients).each(function () {
            var ingredient = this;
            lineNumber++;

            that.checkIngredientContainsQuantityUnitAndFood(ingredient, lineNumber);
            ingredient = that.checkIngredientQuantityIsValid(ingredient, lineNumber);
        });

        return this.errors;
    },

    /**
     *
     * @param ingredient
     * @param lineNumber
     */
    checkIngredientContainsQuantityUnitAndFood: function (ingredient, lineNumber) {
        if (!ingredient.quantity || !ingredient.unit || !ingredient.food) {
            this.errors.push('Quantity, unit, and food have not all been included on line ' + lineNumber);
            $("#quick-recipe > div").eq(lineNumber-1).css('background', 'red');
        }
    },

    /**
     *
     * @param ingredient
     * @param lineNumber
     */
    checkIngredientQuantityIsValid: function (ingredient, lineNumber) {
        var checkedQuantity = this.checkQuantityIsValid(ingredient.quantity);
        if (!checkedQuantity) {
            //Quantity is invalid
            this.errors.push('Quantity is invalid on line ' + lineNumber);
            $("#quick-recipe > div").eq(lineNumber-1).css('background', 'red');
        }
        else {
            // Quantity is valid and if it was a fraction, it has now been converted to a decimal.
            ingredient.quantity = checkedQuantity;
        }

        return ingredient;
    },

    /**
     * Check the quantity for any invalid characters.
     * If the quantity is a fraction, convert it to a decimal.
     * @param quantity
     * @returns {*}
     */
    checkQuantityIsValid: function (quantity) {
        for (var i = 0; i < quantity.length; i++) {
            var character = quantity[i];

            if (isNaN(character) && character !== '.' && character !== '/') {
                //character is not a number, '.', or '/'. The quantity is invalid.
                quantity = false;
            }
            else {
                quantity = this.convertQuantityToDecimal(quantity);
            }
        }

        return quantity;
    },

    /**
     * Check if $quantity is a fraction, and if so, convert to decimal
     * @param quantity
     * @returns {*}
     */
    convertQuantityToDecimal: function (quantity) {
        if (quantity.indexOf('/') !== -1) {
            //it is a fraction
            var parts = quantity.split('/');
            quantity = parseInt($parts[0], 10) / parseInt(parts[1], 10);
        }

        return quantity;
    }
}