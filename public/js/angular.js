angular.module('threadServices', ['ngResource']).
    factory('Thread', function($resource) {
        return $resource('/forum/threads/:id', {}, {
            query: {method: 'GET', params: {id: 'thread'}, isArray: true}
        });
    });

angular.module('postServices', ['ngResource']).
    factory('Post', function($resource) {
        return $resource('/forum/threads/:threadId/posts', {}, {
            query: {method: 'GET', isArray: true}
        });
    });

function ThreadDetailController($scope, $routeParams, Thread) {
    $scope.thread = Thread.get({id: $routeParams.id});
}

function PostsController($scope, $routeParams, Post) {
    $scope.posts = Post.get({threadId: $routeParams.id});
}

angular.module('app', ['threadServices', 'postServices']).
    config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
    $locationProvider.html5Mode(true);

    $routeProvider.
        when('/forum/threads/:id', {templateUrl: '/partials/thread-detail.html', controller: ThreadDetailController}).
        when('/forum/threads/:threadId/messages', {templateUrl: '/partials/messages.html', controller: PostsController})
}]);