app.factory('ProjectsFactory', function ($http) {
    return {

        /**
         * select
         */

        /**
         * insert
         */

        insertProject: function ($payer_email, $description, $rate) {
            var $url = '/projects';
            var $data = {
                payer_email: $payer_email,
                description: $description,
                rate: $rate
            };

            return $http.post($url, $data);
        },
        startProjectTimer: function ($project_id) {
            var $url = 'insert/startProjectTimer';
            var $data = {
                project_id: $project_id
            };

            return $http.post($url, $data);
        },
        stopProjectTimer: function ($project_id) {
            var $url = 'update/stopProjectTimer';
            var $data = {
                project_id: $project_id
            };

            return $http.post($url, $data);
        },
        addPayer: function () {
            var $url = 'insert/payer';
            var $payer_email = $("#new-payer-email").val();
            var $data = {
                payer_email: $payer_email
            };

            return $http.post($url, $data);
        },

        /**
         * update
         */

        /**
         * delete
         */
        deleteProject: function ($scope, $project, $context) {
            var $url = $project.path;

            // Update the scope with the collection without the project we are deleting

            $scope.projects[$context] = _.without($scope.projects[$context], $project);

            // Take the context and delete the project from the javascript version of the collection
            // You can access the id of the object with $project.id
            //$scope.projects = the array without the project we deleted;

            return $http.delete($url); // Return same thing here
        },
        deleteTimer: function ($timer) {
            var $url = $timer.path;

            return $http.delete($url);
        }
    };
});
