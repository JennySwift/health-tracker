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

        removePayer: function ($payer_id) {
            var $url = 'delete/payer';
            var $data = {
                payer_id: $payer_id
            };

            return $http.post($url, $data);
        },
        deleteProject: function ($project) {
            var $url = $project.path;

            return $http.delete($url);
        },
        deleteTimer: function ($timer) {
            var $url = $timer.path;

            return $http.delete($url);
        }
    };
});
