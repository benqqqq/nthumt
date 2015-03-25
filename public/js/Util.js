var util = {
	isContinue : true,
	
	stop : function() {
		util.isContinue = false;	
	},
	
	link : function(url) {
		if (util.isContinue) {
			window.location = url;
		} else {
			util.isContinue = true;
		}
	},

	makeImagePreview : function(input, target, callback) {
		if (input.files && input.files[0]) {
			reader = new FileReader();
			reader.onload = function (e) {
				$(target).attr('src', e.target.result);
				if (callback) {
					callback();	
				}
			}
			reader.readAsDataURL(input.files[0]);
			input.value = null;
		}
	},
		
	userProfileCropper : function() {			
		var ratio = document.querySelector('.container img').clientWidth / document.querySelector('.container img').clientHeight;
		$(".container > img").cropper({
			aspectRatio: 1,
			done: function(data) {
				var dataUrl = $('.container > img').cropper("getDataURL", {
					width: 200,
					height: 200
				}, "image/jpg");
				$('#profileDataUrl').val(dataUrl);
			},
			build: function() {
				var height = 250;
				var width = height * ratio;
				
				$(".container").css({
					height: height,
					width: width
				});		
			},
		});				
	},
	teamProfileCropper : function() {
		var ratio = document.querySelector('.container img').clientWidth / document.querySelector('.container img').clientHeight;
		$(".container > img").cropper({
			aspectRatio: 1.618,
			done: function(data) {
				var dataUrl = $('.container > img').cropper("getDataURL", {
					width: 400 * 1.618,
					height: 400
				}, "image/jpg");
				$('#profileDataUrl').val(dataUrl);				
			},
			build: function() {
				var height = 500;
				var width = height * ratio;
				
				$(".container").css({
					height: height,
					width: width
				});
				$(".pop-main").css('margin-top', 0);
			},
		});	
	},
	
	cropperDown : function() {
		var dataUrl = $('#profileDataUrl').val();		
		$('.imgFrame img').attr('src', dataUrl);
		popper.cancel = null;
		popper.hidden();
	},
	cropperCancel : function() {
		$('#profileDataUrl').val(null);
	},
	
	setScalableWidth : function(obj, existWidth) {
		$(window).resize(function() {			
			util.scalableWidth(obj, existWidth);
		});
		$(document).ready(function() {
			util.scalableWidth(obj, existWidth);
		});		
	},
	scalableWidth : function(obj, existWidth) {
		var windowWidth = $(window).width();
		var minWidth = 800;
		var maxWidth = 1080;
		if (windowWidth - existWidth > maxWidth) {
			$(obj).css('width', maxWidth);
		} else if (windowWidth > minWidth) {
			$(obj).css('width', windowWidth - existWidth);			
		} else {
			$(obj).css('width', 'auto');
		}
	},
	
	
	ajax : function(url, data, callback, method, cache) {
		data = data || {};
		method = method || 'get';
		cache = (typeof cache != 'undefined')? cache : true;
		$.ajax({
			url : url,
			dataType : 'html',
			data: data,
			async : true,
			cache : cache,
			type: method,
			success : callback,
		});			
	},
	
	ajaxPost : function(url, form, success, fail) {
		$.post(url, $(form).serialize()).done(success).fail(fail);
	},
	
	triggerBtn : function(e, target) {
		if (e.keyCode == 13) {
			$(target).click();	
		}
	},
	
	changeNavFocus : function(i) {
		$(document).ready(function() {
			$('.navBtn-focus').removeClass('navBtn-focus');
			$('.headerNav li:nth-child(' + i + ') .navBtn').addClass('navBtn-focus');			
		});
	},
	
	enableGuideMove : function() {
		var paddingTop = $('main').css('padding-top');
		$(document).scroll(function() {
			var top = $(this).scrollTop();
			if (top < 75) {
				$('.top-header').css('top', -1 * top);
				$('.guide').css('position', 'relative');
				$('main').css('padding-top', parseInt(paddingTop));
			} else if (top < 175){
				$('.top-header').css('top', -75);
				$('.guide').css({
					'position': 'fixed',
					'top': 0,
					'z-index': 2
				});
				var guideHeight = $('.guide').height();
				$('main').css('padding-top', parseInt(paddingTop) + guideHeight);
			}
		});	
	},
	
	enableTabInTextarea : function() {
		$("textarea").keydown(function(e) {
		    if(e.keyCode === 9) { // tab was pressed
		        // get caret position/selection
		        var start = this.selectionStart;
		        var end = this.selectionEnd;
		
		        var $this = $(this);
		        var value = $this.val();
		
		        // set textarea value to: text before caret + tab + text after caret
		        $this.val(value.substring(0, start)
		                    + "\t"
		                    + value.substring(end));
		
		        // put caret at right position again (add one for the tab)
		        this.selectionStart = this.selectionEnd = start + 1;
		
		        // prevent the focus lose
		        e.preventDefault();
		    }
		});
	}
}