var RecipesRepository = {
    
    getIngredients: function () {
        var string = $("#quick-recipe").html();
        return lines.slice(0, stepsIndex);
    },

    getIngredientArray: function () {
        //turn the string into an array of divs by first splitting at the br tags
        var array = string.split('<br>');

        //remove any empty elements from the array
        array = _.without(array, '');

        //Chrome was putting this line at the top. Remove that:
        //<!--?xml version="1.0" encoding="UTF-8" standalone="no"?-->
        if (array[0].indexOf('<!--') !== -1 && array[0].indexOf('-->') !== -1) {
            array.shift();
        }
    },
    
    getSteps: function () {
        return lines.slice(stepsIndex+1);
    },

    /**
     *
     * @param string
     * @param wysiwyg
     * @returns {*}
     */
    formatString: function (string, wysiwyg) {
        //Format for any browser (hopefully)
        var stringAndArray = this.formatForAnyBrowser(string, wysiwyg);

        //trim the items in the array
        $(stringAndArray.array).each(function () {
            this.trim();
        });

        //separate the steps from the recipe
        return this.separateStepsFromIngredients(stringAndArray.array);
    },

    /**
     * Check for the possibilities of words that indicate
     * which line the steps starts on
     *
     * @param lines
     * @returns {*|number|Number}
     */
    findStepsIndex: function (lines) {
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
            if (linesLower.indexOf(possibilities[i]) !== -1 || linesLower.indexOf(possibilities[i] + ':') !== -1) {
                return linesLower.indexOf(possibilities[i]);
            }
        }
    },

    /**
     * The string may contain unwanted br tags and
     * both opening and closing div tags.
     * Format the string so into a string of div tags to
     * populate the html of the wysiwyg.
     * And create an array from the string.
     * Return both the formatted string and the array.
     * @param string
     * @param wysiwyg
     * @returns {{string: string, array: *}}
     */
    formatString: function (string, wysiwyg) {
        //Remove any closing div tags and replace any opening div tags with a br tag.
        while (string.indexOf('<div>') !== -1 || string.indexOf('</div>') !== -1) {
            string = string.replace('<div>', '<br>').replace('</div>', '');
        }

        var formattedString = "";

        //make formattedString a string with div tags
        for (var j = 0; j < array.length; j++) {
            formattedString += '<div>' + array[j] + '</div>';
        }

        string = formattedString;
        $(wysiwyg).html(string);

        return string;
    },

    /**
     * ingredients is an array of strings.
     * The string should include the quantity, unit, food, and description,
     * providing the user has entered them.
     * We want to take each string and turn it into an object with
     * food, unit, quantity and description properties.
     * Then return the new array of objects.
     * @param items
     * @returns {Array}
     */
    convertIngredientStringsToObjects: function (ingredients) {
        var ingredientsAsObjects = [];

        $(ingredients).each(function () {
            var ingredientAsString = this;
            var ingredientAsObject = {};

            ingredientAsObject.description = this.getIngredientDescription();

            var quantityUnitAndFood = this.getIngredientQuantityUnitAndFood();

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
     */
    getIngredientDescription: function (ingredientAsString) {
        var split = this.splitDescriptionFromQuantityUnitAndFood();
        return split[1].trim();
    },

    /**
     *
     * @returns {Array|*}
     */
    splitDescriptionFromQuantityUnitAndFood: function () {
        if (ingredientAsString.indexOf(',') !== -1) {
            return split = this.split(',');
        }
    },

    /**
     *
     * @returns {*}
     */
    getIngredientQuantityUnitAndFood: function () {
        var split = this.splitDescriptionFromQuantityUnitAndFood();

        return split[0];
    },

    /**
     * Return an array of errors for each line that does not
     * have a quantity, unit and food
     * @param ingredients
     * @returns {{items: *, errors: Array}}
     */
    errorCheck: function (ingredients) {
        var lineNumber = 0;
        var errors = [];
        var checkedQuantity;
        var that = this;

        $(ingredients).each(function () {
            var ingredient = this;
            lineNumber++;

            if (!ingredient.quantity || !ingredient.unit || !ingredient.food) {
                errors.push('Quantity, unit, and food have not all been included on line ' + lineNumber);
                $("#quick-recipe > div").eq(lineNumber-1).css('background', 'red');
            }
            //The line contains quantity, unit and food.
            //Check the quantity is valid.
            else {
                $checkedQuantity = that.validQuantityCheck(ingredient.quantity);
                if (!$checkedQuantity) {
                    //Quantity is invalid
                    errors.push('Quantity is invalid on line ' + lineNumber);
                    $("#quick-recipe > div").eq(lineNumber-1).css('background', 'red');
                }
                else {
                    // Quantity is valid and if it was a fraction, it has now been converted to a decimal.
                    ingredient.quantity = checkedQuantity;
                }
            }
        });

        return {
            ingredients: ingredients,
            errors: errors
        };
    },

    /**
     * Check the quantity for any invalid characters.
     * If the quantity is a fraction, convert it to a decimal.
     * @param quantity
     * @returns {*}
     */
    validQuantityCheck: function (quantity) {
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