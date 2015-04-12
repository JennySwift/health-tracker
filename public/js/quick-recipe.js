app.factory('quickRecipe', function ($http) {
	return {
		formatString: function ($string, $wysiwyg) {
			var $lines = [];
			var $divs;

			if ($string.indexOf('<br>') !== -1) {
				//remove the final and pointless br tag/tags
				while ($string.substr($string.length - 4, 4) === '<br>') {
					//there is a br tag at the end. remove it.
					$string = $string.substring(0, $string.length - 4);
				}

				//change br tags to divs
				var $split = $string.split('<br>');
				var $formatted_string = "";
				for (var i = 0; i < $split.length; i++) {
					$formatted_string += '<div>' + $split[i] + '</div>';
				}
				$string = $formatted_string;
				$($wysiwyg).html($string);
			}

			//turn the string into an array of lines
			$divs = $($wysiwyg).children();
			$($divs).each(function () {
				var $line = $(this).html();
				$line = $line.trim();
				if ($line !== "") {
					$lines.push($line);
				}	
			});

			return $lines;
		},
		quantity: function ($string, $index) {
			//Find out how many digits the quantity contains.
			var $end_quantity_index = $index + 1;

			//check if the following characters are numbers, or empty strings (isNan return false for empty string), a decimal point, or a / for a fractional number
			while (!isNaN($string.substr($end_quantity_index, 1)) && $string.substr($end_quantity_index, 1) !== ' ' || $string.substr($end_quantity_index, 1) === '.' || $string.substr($end_quantity_index, 1) === '/') {
				//in other words, the next character is a number, '.' or '/'.
				$end_quantity_index++;
			}

			$quantity = $string.substring($index, $end_quantity_index);

			//check if $quantity is a fraction, and if so, convert to decimal
			if ($quantity.indexOf('/') !== -1) {
				//it is a fraction
				var $parts = $quantity.split('/');
				var $decimal = parseInt($parts[0], 10) / parseInt($parts[1], 10);
				$quantity = $decimal;
			}

			return [$quantity, $end_quantity_index];
		},
		unitName: function ($string, $end_quantity_index) {
			$start_unit_index = $end_quantity_index + 1;
			var $end_unit_index = $start_unit_index + 1;
			var $unit_name;

			while ($string.substr($end_unit_index, 1) !== ' ') {
				if ($string.substr($end_unit_index, 1) === '<' || $string.substr($end_unit_index, 1) === ',' || $string.substr($end_unit_index, 1) === '') {
					//there was an error-after the unit there is a tag, comma, or it is the end of the line.
					return {
						error: true
					};
				}
				$end_unit_index++;
			}

			$unit_name = $string.substring($start_unit_index, $end_unit_index);

			return {
				name: $unit_name,
				end_index: $end_unit_index,
				error: false
			};
		},
		foodName: function ($string, $end_unit_index) {
			var $start_food_index = $end_unit_index + 1;
			var $end_food_index = $start_food_index + 1;

			while ($string.substr($end_food_index, 1) !== ',' && $string.substr($end_food_index, 1) !== "") {
				//we haven't reached a comma or the end of a line
				$end_food_index++;
			}
			
			var $food_name = $string.substring($start_food_index, $end_food_index);

			return [$food_name, $end_food_index];
		},
		description: function ($string, $end_food_index) {
			var $description;

			//check there is a comma
			if ($string.substr($end_food_index, 1) === ',') {
				var $start_description_index = $end_food_index + 1;
				var $end_description_index = $start_description_index + 1;

				while ($string.substr($end_description_index, 1) !== "") {
					$end_description_index++;
				}
				
				$description = $string.substring($start_description_index + 1, $end_description_index);
			}

			return $description;
		}
	};
});