app.factory('AutocompleteFactory', function ($http) {
    var $object = {};
    $object.autocompleteUpArrow = function ($array) {
        //find the selected object in the array
        var $selected = _.findWhere($array, {selected: true});
        //get its index
        var $index = $array.indexOf($selected);
        var $prev_index = $index - 1;
        if ($array[$prev_index]) {
            //there is an item before the selected one
            var $prev = $array[$prev_index];
            $prev.selected = true;
            $selected.selected = false;
        }
    };

    /**
     * For when the dropdown options are in an array
     * @param  {[type]} $array [description]
     * @return {[type]}        [description]
     */
    $object.autocompleteDownArrow = function ($array) {
        //find the selected object in the array
        var $selected = _.findWhere($array, {selected: true});
        //get its index
        var $index = $array.indexOf($selected);
        var $next_index = $index + 1;
        if ($array[$next_index]) {
            //there is an item after the selected one
            var $next = $array[$next_index];
            $next.selected = true;
            $selected.selected = false;
        }
    };

    $object.exercise = function () {
        var $typing = $("#exercise").val();
        var $url = 'api/autocomplete/exercise';
        var $data = {
            exercise: $typing
        };

        return $http.post($url, $data);
    };

    $object.food = function ($typing) {
        var $url = 'api/autocomplete/food';
        var $data = {
            typing: $typing
        };

        return $http.post($url, $data);
    };

    $object.menu = function () {
        var $typing = $("#menu").val();
        var $url = 'api/autocomplete/menu';
        var $data = {
            typing: $typing
        };

        return $http.post($url, $data);
    };

    return $object;
});



