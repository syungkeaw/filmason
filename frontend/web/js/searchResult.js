angular.module('searchResult', [])

.factory('$qStr', function() {
    return function(name){
    	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	        results = regex.exec(location.search);
	    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));   
	}
})

.controller('searchResultCtrl', function ($scope, $http, $qStr, $timeout) { //&language=th
	var loadPage = 1, totalPage = 0, loadButton;
	$scope.toggle = false;
	$scope.movies = [];

	$scope.call = function(){
		$http.get('http://api.themoviedb.org/3/search/movie?page='+loadPage+'&api_key=3d197569c7f13f60d61a7d61d5c83427&query='+$qStr('query'))
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
