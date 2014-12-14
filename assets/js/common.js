(function() {
  var app, appControllers, appServices;

  app = angular.module('ngApp', ['appControllers', 'appServices', 'ngRoute', 'ngAnimate']);

  appServices = angular.module('appServices', []);

  appControllers = angular.module('appControllers', []);

  app.config([
    '$routeProvider', '$locationProvider', '$httpProvider', function($routeProvider, $locationProvider, $httpProvider) {
      $routeProvider.when('/', {
        controller: 'MakasetainerController',
        templateUrl: '/app/views/top.html'
      }).otherwise({
        redirectTo: "/"
      });
      return $httpProvider.defaults.transformRequest = function(data) {
        if (data === void 0) {
          return data;
        }
        return $.param(data);
      };
    }
  ]);

  appControllers.controller('MakasetainerController', [
    '$scope', '$http', '$timeout', '$interval', '$route', '$sce', function($scope, $http, $timeout, $interval, $route, $sce) {
      var val;
      $scope.windowLoad = true;
      $timeout((function() {
        return $scope.windowLoad = false;
      }), 1000);
      $timeout((function() {
        return $scope.top = true;
      }), 1300);
      $scope.init = '選択してください';
      $scope.categories = ['グルメ', 'トラベル'];
      $scope.stateTitle = '投稿画面';
      val = '';
      navigator.geolocation.getCurrentPosition((function(_this) {
        return function(pos) {
          return val = "" + pos.coords.latitude + "," + pos.coords.longitude;
        };
      })(this));
      $scope.answeres = [];
      $scope.reload = function() {
        return $route.reload();
      };
      return $scope.submit = function() {
        var postData;
        $scope.loading = true;
        postData = {
          type: 'post',
          mid: $scope.uniqueID,
          category: $scope.category,
          content: $scope.content,
          gps: val
        };
        return $http({
          method: 'POST',
          url: '/posts.php',
          data: postData,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          }
        }).success(function(data) {
          $scope.stateTitle = '回答画面';
          return $interval(function() {
            return $http({
              method: 'POST',
              url: '/posts.php',
              data: {
                type: 'get',
                mid: $scope.uniqueID
              },
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              }
            }).success(function(pollingData) {
              $scope.top = false;
              $scope.loading = false;
              if (pollingData[0].answer　) {
                $scope.answeres.push(pollingData[0].answer);
              } else if (pollingData[0].errTxt) {
                $scope.answeres.push(pollingData[0].errTxt);
              }
              return $scope.answeres = $scope.answeres.filter(function(v, i, ary) {
                return ary.indexOf(v) === i;
              });
            });
          }, 5000);
        });
      };
    }
  ]);

}).call(this);
