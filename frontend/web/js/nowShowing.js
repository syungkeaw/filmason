angular.module('nowShowing', [])

.factory('$qStr', function() {
    return function(name){
    	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	        results = regex.exec(location.search);
	    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));   
	}
})

.controller('nowShowingCtrl', function ($scope, $http, $qStr, $timeout) { //&language=th
	var loadPage = 1, totalPage = 0, loadButton;
	$scope.toggle = false;
	$scope.movies = [];

	$scope.call = function(){
		$http.get('http://api.themoviedb.org/3/movie/now_playing?api_key=3b03c053f34ff11cfdc0d26b06ac95d1&language=th&page=' + loadPage)
		.success(function(data) {

			$scope.movies = reTitleUrl($scope.movies.concat(data.results));
			$scope.currentResult = $scope.movies.length;
			$scope.totalResult = data.total_results;

			totalPage = data.total_pages;
			loadPage++;

			$(loadButton).button('reset');
			if(loadPage > totalPage) 
				$scope.toggle = true;
		});
	}

	$scope.call(loadPage);

	$scope.loadMore = function($event){
		loadButton = $event.target;
		$(loadButton).button('loading');
		$scope.call(loadPage);
	}

	$timeout(function () {
        $scope.$root = {
            initializing: {
                status: 'Complete!'
            }
        }
    }, 2000);
});
