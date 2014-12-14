#
# initialize modules
#
app = angular.module 'ngApp',[
	'appControllers'
	'appServices'
	'ngRoute'
	'ngAnimate'
]

appServices = angular.module 'appServices', []
appControllers = angular.module 'appControllers', []

#
# routes
#

app.config ['$routeProvider','$locationProvider','$httpProvider',($routeProvider,$locationProvider,$httpProvider) ->
	$routeProvider
	#トップページ
	.when '/',
		controller:'MakasetainerController'
		templateUrl:'/app/views/top.html'
	.otherwise redirectTo: "/"

	$httpProvider.defaults.transformRequest = (data)->
        if data == undefined
            return data;
        return $.param(data);
]

#
# controller
# makasetainerCtrl
#

appControllers.controller 'MakasetainerController', ['$scope','$http','$timeout','$interval', '$route', '$sce',
	($scope, $http, $timeout, $interval, $route, $sce) ->
		$scope.windowLoad = true
		$timeout (->
			$scope.windowLoad = false
		), 1000

		$timeout (->
			$scope.top = true
		), 1300

		$scope.init = '選択してください'
		$scope.categories = ['グルメ','トラベル']
		$scope.stateTitle = '投稿画面'

		val = ''
		navigator.geolocation.getCurrentPosition (pos) =>
			val = "#{pos.coords.latitude},#{pos.coords.longitude}"

		$scope.answeres = []
		
		$scope.reload = ->
			$route.reload();

		$scope.submit = ->
			
			$scope.loading = true
			postData =
				type:'post'
				mid:$scope.uniqueID
				category:$scope.category
				content:$scope.content
				gps:val

			$http
				method:'POST'
				url:'/posts.php'
				data:postData
				headers: {'Content-Type': 'application/x-www-form-urlencoded'}

			.success (data)->
				$scope.stateTitle = '回答画面'
				$interval ->
					$http
						method:'POST'
						url:'/posts.php'
						data:
							type:'get'
							mid:$scope.uniqueID
						headers: {'Content-Type': 'application/x-www-form-urlencoded'}
					.success (pollingData)->
						$scope.top = false
						$scope.loading = false

						if pollingData[0].answer　				
							$scope.answeres.push pollingData[0].answer

						else if pollingData[0].errTxt
							$scope.answeres.push pollingData[0].errTxt

						$scope.answeres = $scope.answeres.filter (v, i, ary)->
				            return ary.indexOf(v) == i;

				,5000

]