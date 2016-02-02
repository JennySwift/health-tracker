angular.module('tracker')
    .factory('ExerciseEntriesFactory', function ($http) {
        return {
            insert: function ($sqlDate, $newEntry) {
                var $url = 'api/exerciseEntries';

                var $data = {
                    date: $sqlDate,
                    exercise_id: $newEntry.id,
                    quantity: $newEntry.quantity,
                    unit_id: $newEntry.unit_id
                };

                $("#exercise").val("").focus();

                return $http.post($url, $data);
            },

            deleteExerciseEntry: function ($entry) {
                if (confirm("Are you sure you want to delete this entry?")) {
                    var $url = 'api/exerciseEntries/' + $entry.id;

                    return $http.delete($url);
                }
            }
        }
    });