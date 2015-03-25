

app.controller('createTeamCtrl', function($scope) {
	checker.add('#name');
	checker.add('#memberList', userAdder.isLeaderExist);
	

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
	
	$scope.transfer = function(data) {
		var lineData = newline2symbol(data);
		lineData = removeTab(lineData);
		var result = cut(lineData, '前言', '山區簡介');
		document.getElementsByName('foreword')[0].value = result;
		result = cut(lineData, '山區簡介', '預定行程');
		document.getElementsByName('intro')[0].value = result;
		result = cut(lineData, '預定行程', '無線電頻道');
		document.getElementsByName('plan')[0].value = result;
		result = cutLine(lineData, '無線電頻道');
		document.getElementsByName('channel')[0].value = result;
		result = cutLine(lineData, '開機時段');
		document.getElementsByName('channelPeriod')[0].value = result;
		result = cutLine(lineData, '台號');
		document.getElementsByName('channelName')[0].value = result;
		result = cutLine(lineData, '留守');
		document.getElementsByName('leftPerson')[0].value = result;
		result = cutLine(lineData, '包車');
		document.getElementsByName('traffic')[0].value = result;
		result = cut(lineData, '撤退計畫[：:]', '參考紀錄');
		document.getElementsByName('retreat')[0].value = result;
		result = cut(lineData, '參考紀錄', '人員組成');
		document.getElementsByName('reference')[0].value = result;
		result = cut(lineData, '人員組成', '器材裝備');
		document.getElementsByName('unregisteredMembers')[0].value = result;
		result = cut(lineData, '器材裝備', '隊員要求');
		document.getElementsByName('equipments')[0].value = result;
		result = cut(lineData, '隊員要求', '隊費');
		document.getElementsByName('memberRequire')[0].value = result;
		result = cut(lineData, '隊費', '重要時程');
		document.getElementsByName('fee')[0].value = result;
		result = cut(lineData, '重要時程', '--');
		document.getElementsByName('importantDate')[0].value = result;
		result = cutCustom(lineData, '標題.*計.書] (.*?)æ');
		document.getElementsByName('name')[0].value = result;
	};
	
	function newline2symbol(str) {
		return str.replace(/(\r\n|\n|\r)/gm, "æ");
	}
	
	function symbol2newline(str) {
		return str.replace(/æ/gm, "\n");
	}
	
	function cut(str, start, end) {
		var re = new RegExp(start + ".*?æ*(.*)æ.*?" + end);
		return runCut(re, str);
	}
	function cutLine(str, name) {
		var re = new RegExp(name + "[：:](.*?)æ");
		return runCut(re, str);
	}
	function cutCustom(str, reStr) {
		var re = new RegExp(reStr);
		return runCut(re, str);
	}
	
	function runCut(re, str) {
		var result = re.exec(str);
		if (result) {
			return symbol2newline(result[1]);
		} else {
			return null;
		}
	}
	function removeTab(str) {
		return str.replace(/æ\s+/gm, "æ");
	}
});