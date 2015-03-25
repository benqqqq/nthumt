

app.controller('teamFileCtrl', function($scope) {
	$scope.showUpload = false;
	$scope.trClass = {};
	$scope.select = function(id) {
		$scope.trClass[$scope.selectedId] = '';	
		$scope.selectedId = id;
		$scope.trClass[id] = 'selected';
	}
	$scope.deleteFile = function(url) {		
		if ($scope.selectedId) {
			popper.warn(url + "/" + $scope.selectedId, '確定刪除 ? 此動作無法復原');
		}
	}
});