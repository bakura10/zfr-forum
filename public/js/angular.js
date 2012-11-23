angular.module('postServices', ['ngResource']).
    factory('Post', function($resource) {
        alert('ok');
        return $resource('/posts/:postId', {}, {
            query: {method: 'GET', params: {postId: 'post'}, isArray:true}
        });
    });

angular.module('post', ['postServices']).
    config(['$routeProvider', function($routeProvider) {
        $routeProvider.
            when('/posts/:postId', {templateUrl: 'partials/post-detail', controller: PostDetailController})
}]);

function PostDetailController($scope, $routeParams, Post) {
    alert('ok');
}