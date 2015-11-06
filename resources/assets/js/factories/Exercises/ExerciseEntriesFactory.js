angular.module('tracker')
    .factory('ExerciseEntriesFactory', function ($http) {
        return {
            getSpecificExerciseEntries: function ($sql_date, $entry) {
                var $url = 'api/select/specificExerciseEntries';
                var $data = {
                    date: $sql_date,
                    exercise_id: $entry.exercise.id,
                    exercise_unit_id: $entry.unit.id
                };

                return $http.post($url, $data);
            },
            getEntriesForTheDay: function ($date) {
                var $url = 'api/exerciseEntries/' + $date;
                return $http.get($url);
            },
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
            insertExerciseSet: function ($sqlDate, $exercise) {
                var $url = 'api/exerciseEntries';
                var $data = {
                    date: $sqlDate,
                    exercise_id: $exercise.id,
                    exerciseSet: true
                };

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