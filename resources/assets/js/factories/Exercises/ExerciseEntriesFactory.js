angular.module('tracker')
    .factory('ExerciseEntriesFactory', function ($http) {
        return {
            getSpecificExerciseEntries: function ($sql_date, $exercise_id, $exercise_unit_id) {
                var $url = 'select/specificExerciseEntries';
                var $data = {
                    date: $sql_date,
                    exercise_id: $exercise_id,
                    exercise_unit_id: $exercise_unit_id
                };

                return $http.post($url, $data);
            },
            insertExerciseEntry: function ($sql_date, $new_entry) {
                var $url = '/ExerciseEntries';

                var $data = {
                    date: $sql_date,
                    new_entry: $new_entry,
                };

                $("#exercise").val("").focus();

                return $http.post($url, $data);
            },
            insertExerciseSet: function ($sqlDate, $exercise_id) {
                var $url = 'api/exerciseEntries';
                var $data = {
                    date: $sqlDate,
                    exercise_id: $exercise_id,
                    exerciseSet: true
                };

                return $http.post($url, $data);
            },

            deleteExerciseEntry: function ($id) {
                if (confirm("Are you sure you want to delete this entry?")) {
                    var $url = 'exerciseEntries/' + $id;

                    return $http.delete($url);
                }
            }
        }
    });