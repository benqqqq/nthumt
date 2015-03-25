var it = {
	requires : [],
	
	addRequires : function(objs) {
		for (var i in objs) {
			var obj = objs[i];
			it.requires.push(obj);
			
			$(obj).blur(function() {
				var v = $(this).val();
				var matches = v.match(/.+/);
				if (matches == null) {
					$(this).addClass('invalid');	
					$(this).after('<span> * 必要</span>');
				} else {
					$(this).removeClass('invalid');
					$(this).next('span').remove();
				}				
			});
		}
	},
	addDatepicker : function(objs, values) {
		for (var i in objs) {
			var obj = objs[i];
			var value = (typeof(values) != 'undefined')? values[i] : it.today();
			$(obj).datetimepicker({
				lang : 'ch',
				timepicker : false,
				format: 'Y/m/d',
				scrollInput: false,
				value: value,
			});
		}	
	},
	addDatetimepicker : function(objs, values) {
		for (var i in objs) {
			var obj = objs[i];
			var value = (typeof(values) != 'undefined')? values[i] : it.today() + ' 00:00';
			$(obj).datetimepicker({
				lang : 'ch',
				scrollInput: false,
				value : value,
			});
		}
	},
	today : function() {
		var today = new Date();
		return today.getFullYear() + '/' + (today.getMonth() + 1) + '/' + today.getDate()
	},
}

var inputer = it;