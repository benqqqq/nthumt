
app.controller('articleCtrl', function($scope) {
	checker.add('#title');
	
	$scope.submitForm = function(url) {
		var form = document.getElementById('dataForm');
		var result = checker.check();
		if (result.isPass) {
			form.action = url;
			form.submit();			
		} else {
			for (var i in result.invalids) {
				var invalid = result.invalids[i];
				$(invalid).next('.warn').show();
			}
			$('body').animate({
				scrollTop : $(result.invalids[0]).offset().top - 200
			}, 300);
		}
	};
	

});