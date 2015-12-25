angular.module('tracker')
    .factory('ProgramsFactory', function ($http) {
        return {
            index: function () {
                var url = '/api/exercisePrograms';
            
                return $http.get(url);
            }
        }
    });