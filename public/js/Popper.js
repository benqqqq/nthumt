var popper = {
	html : "<div class='pop vertical-middle'> \
				<div class='pop-background' onclick='popper.hidden()'></div> \
				<div class='pop-main'> \
					<h1 class='pop-title green'></h1> \
					<span class='pop-message gray'></span> \
					<div class='pop-additional'> \
						<button class='btn btn-green' onclick='popper.hidden()'>確定</button> \
					</div> \
				</div> \
			</div>",
	obj : null,
	
	set : function(attr, val) {
		popper.html = $(popper.html).find(attr).html(val).parents('.pop');
	},
	
	show : function() {
		popper.obj = $(popper.html).appendTo('body').animate({'opacity' : 1}, 300);
		$(document).keyup(function(e) {
			if (e.keyCode == 27) {	// esc
				popper.hidden();
			}
		});
	},
	
	hidden : function() {
		$(popper.obj).animate({'opacity' : 0}, 300).remove();
		if (popper.cancel) {
			popper.cancel();
		}
	},
	
	setTitleMessage : function(title, message) {
		popper.set('.pop-title', title);
		popper.set('.pop-message', message);		
	},
	
	showWithTitleMessage : function(title, message) {
		popper.setTitleMessage(title, message);
		popper.show();
		popper.focusOn('.pop-additional .btn');
	},
	
	focusOn : function(obj) {
		$(obj).focus();	
	},
	
	signUp : function(teamId, teamName, teamMemberRequire) {
		var form = "<form method='POST' action='/team/" + teamId + "/signUp' accept-charset='UTF-8' enctype='multipart/form-data'> \
						<p> \
						<textarea name='message' id='message' placeholder='給領隊的話'></textarea> \
						<p> \
						<input class='btn btn-green' type='submit' value='送出'> \
					</form>";
	
		popper.setTitleMessage('報名 : ' + teamName, '隊員要求 : ' + teamMemberRequire);
		popper.set('.pop-additional', form);
		popper.show();
	},
	
	categoryWarn : function(i) {
		var choices = "<button class='btn btn-green' onclick='popper.hidden(); changeCategoryName(" + i + ")'>僅套用分類</button> \
			<button class='btn btn-green' onclick='popper.hidden(); \
				changeCategoryName(" + i + "); changeCategoryContent(" + i + ")'>套用預設格式(將會覆蓋原文內容)</button>";
		popper.setTitleMessage('警告', '[' + categories[i].name + '] 含有預設格式');
		popper.set('.pop-additional', choices);
		popper.show();		
	},
	
	uploadImage : function(input, callback) {
		var imgFrame = "<div class='container'> \
							<img> \
						</div>\
						<button class='btn btn-green' onclick='util.cropperDown();'>確定</button>\
						<button class='btn btn-green' onclick='popper.hidden();'>取消</button>";
		popper.cancel = util.cropperCancel;
		popper.setTitleMessage('上傳圖片', '對您的照片拖曳、放大、縮小');
		popper.set('.pop-additional', imgFrame);
		popper.show();
		util.makeImagePreview(input, '.container img', callback);
	},
	
	warn : function(link, message) {
		var btn = "<a class='btn btn-green' href='" + link + "'>確定</a>\
				<button class='btn btn-green' onclick='popper.hidden();'>取消</button>";
		popper.setTitleMessage('警告', message);
		popper.set('.pop-additional', btn);
		popper.show();
	},
	
	importTeam : function() {
		var form = "<textarea class='pop-importArea'></textarea></br>";
		var btn = "<button class='btn btn-green' onclick='transfer(); popper.hidden();'>確定</button>\
				<button class='btn btn-green' onclick='popper.hidden();'>取消</button>";
				
		popper.setTitleMessage('匯入社版計劃書', '將計劃書全文貼入以下文字框(需符合社版格式)');
		popper.set('.pop-additional', form + btn);
		popper.show();
	}
}