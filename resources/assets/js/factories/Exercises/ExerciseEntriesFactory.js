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
            insertExerciseSet: function ($sql_date, $exercise_id) {
                var $url = 'insert/exerciseSet';
                var $data = {
                    date: $sql_date,
                    exercise_id: $exercise_id
                };

                return $http.post($url, $data);
            },
            /**
             * Sending $exercise_id and $exercise_unit_id so that in the response I can return the info to update the popup.
             * @param  {[type]} $id       [description]
             * @param  {[type]} $sql_date [description]
             * @return {[type]}           [description]
             */
            deleteExerciseEntry: function ($id, $sql_date, $exercise_id, $exercise_unit_id) {
                if (confirm("Are you sure you want to delete this entry?")) {
                    var $url = 'delete/exerciseEntry';
                    var $data = {
                        id: $id,
                        date: $sql_date,
                        exercise_id: $exercise_id,
                        exercise_unit_id: $exercise_unit_id
                    };

                    return $http.post($url, $data);
                }
            },
        }
    });