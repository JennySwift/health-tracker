var RecipesRepository = {

    /**
     *
     * @param $string
     * @param $wysiwyg
     * @returns {*}
     */
    formatString: function ($string, $wysiwyg) {
        //Format for any browser (hopefully)
        var $string_and_array = this.formatForAnyBrowser($string, $wysiwyg);

        //trim the items in the array
        $($string_and_array.array).each(function () {
            this.trim();
        });

        //separate the method from the recipe
        return this.separateMethod($string_and_array.array);
    },

    /**
     * $lines is an array of all the lines in the wysywig.
     * We want to return an object containing the item lines,
     * and the method lines, separate from each other.
     * @param $lines
     * @returns {*}
     */
    separateMethod: function ($lines) {
        var $items;
        var $method;
        var $recipe;
        var $method_index;

        /**
         * @VP:
         * Surely there's a way to do these checks with less code?
         */

        //Check for the method trigger possibilities
        //First, the lowercase possibilities
        if ($lines.indexOf('method') !== -1) {
            $method_index = $lines.indexOf('method');
        }
        else if ($lines.indexOf('preparation') !== -1) {
            $method_index = $lines.indexOf('preparation');
        }
        else if ($lines.indexOf('directions') !== -1) {
            $method_index = $lines.indexOf('directions');
        }

        //Then, the uppercase possibilities
        if ($lines.indexOf('Method') !== -1) {
            $method_index = $lines.indexOf('Method');
        }
        else if ($lines.indexOf('Preparation') !== -1) {
            $method_index = $lines.indexOf('Preparation');
        }
        else if ($lines.indexOf('Directions') !== -1) {
            $method_index = $lines.indexOf('Directions');
        }

        //Then, the lowercase colon possibilities
        //Todo: 'Steps' should also be acceptable
        if ($lines.indexOf('method:') !== -1) {
            $method_index = $lines.indexOf('method:');
        }
        else if ($lines.indexOf('preparation') !== -1) {
            $method_index = $lines.indexOf('preparation:');
        }
        else if ($lines.indexOf('directions:') !== -1) {
            $method_index = $lines.indexOf('directions:');
        }

        //Then, the uppercase colon possibilities
        if ($lines.indexOf('Method:') !== -1) {
            $method_index = $lines.indexOf('Method:');
        }
        else if ($lines.indexOf('Preparation:') !== -1) {
            $method_index = $lines.indexOf('Preparation:');
        }
        else if ($lines.indexOf('Directions:') !== -1) {
            $method_index = $lines.indexOf('Directions:');
        }

        //If $method_index, there is a method.
        //If not, there is no method.
        //Populate the object to return.
        if ($method_index) {
            $items = $lines.slice(0, $method_index);
            $method = $lines.slice($method_index+1);

            $recipe = {
                ingredients: $items,
                steps: $method
            };
        }
        else {
            //There is no method
            $recipe = {
                ingredients: $lines
            };
        }

        return $recipe;
    },

    /**
     * The $string may contain unwanted br tags and
     * both opening and closing div tags.
     * Format the string so into a string of div tags to
     * populate the html of the wysiwyg.
     * And create an array from the $string.
     * Return both the formatted string and the array.
     * @param $string
     * @param $wysiwyg
     * @returns {{string: string, array: *}}
     */
    formatForAnyBrowser: function ($string, $wysiwyg) {
        //Remove any closing div tags and replace any opening div tags with a br tag.
        while ($string.indexOf('<div>') !== -1 || $string.indexOf('</div>') !== -1) {
            $string = $string.replace('<div>', '<br>').replace('</div>', '');
        }

        //turn the string into an array of divs by first splitting at the br tags
        var $array = $string.split('<br>');

        //remove any empty elements from the array
        $array = _.without($array, '');

        var $formatted_string = "";

        //make $formatted_string a string with div tags
        for (var j = 0; j < $array.length; j++) {
            $formatted_string += '<div>' + $array[j] + '</div>';
        }

        $string = $formatted_string;
        $($wysiwyg).html($string);

        return {
            string: $string,
            array: $array
        };
    },

    /**
     * $items is an array of strings.
     * The string should include the quantity, unit, food, and description,
     * providing the user has entered them.
     * We want to take each string and turn it into an object with
     * food, unit, quantity and description properties.
     * Then return the new array of objects.
     * @param $items
     * @returns {Array}
     */
    populateItemsArray: function ($items) {
        var $formatted_items = [];
        $($items).each(function () {
            $line = this;
            var $item = {};

            //if there is a description, separate the description from the quantity, unit and food
            if ($line.indexOf(',') !== -1) {
                $line = $line.split(',');
                //grab the description, add it to the item so I can remove it from the line
                //so it doesn't get in the way
                $item.description = $line[1].trim();
                $line = $line[0];
            }

            //$line is now just the quantity, unit and food, without the description
            //split $line into an array with quantity, unit and food
            $line = $line.split(' ');
            //Add the quantity, unit and food to the $item
            $item.quantity = $line[0];
            $item.unit = $line[1];
            $item.food = $line[2];

            //Add the item object to the items array
            $formatted_items.push($item);
        });

        return $formatted_items;
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