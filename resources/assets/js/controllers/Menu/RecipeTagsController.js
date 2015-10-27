angular.module('tracker')
    .controller('RecipeTagsController', function ($rootScope, $scope, RecipeTagsFactory) {

        $scope.insertRecipeTag = function ($keycode) {
            if ($keycode !== 13) {
                return;
            }
            $rootScope.showLoading();
            RecipeTagsFactory.insert()
                .then(function (response) {
                    $scope.tags.push(response.data.data);
                    $rootScope.$broadcast('provideFeedback', 'Tag created');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

        $scope.deleteRecipeTag = function ($tag) {
            $rootScope.showLoading();
            RecipeTagsFactory.destroy($tag)
                .then(function (response) {
                    $scope.tags = _.without($scope.tags, $tag);
                    $rootScope.$broadcast('provideFeedback', 'Tag deleted');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        };

    });