var pager = {
	
	itemNum : 0,
	url : '',
	resultObj : null,
	pageObj : null,
	range : 0,
	page : 0,
	itemHeight : 0,
	paddingTop : 0,
	renewCallback : null,
	isShowed : false,
	para : '',
	

	init : function(itemNum, url, resultObj, pageObj, itemHeight, paddingTop, para) {
		pager.itemNum = itemNum;
		pager.url = url;
		pager.resultObj = resultObj;
		pager.pageObj = pageObj;
		pager.itemHeight = itemHeight;
		pager.paddingTop = paddingTop;
		pager.para = para;
		
		
		pager.changeRange();
		pager.setWindowResizeEvent();
		pager.setPopEvent();
		pager.firstToPage();		
	},
	
	pushState : function(state) {			
		if (typeof(history.pushState) != "undefined"){		    	
			history.pushState(state, document.title, state.href);
		}
	},
	replaceState : function(state) {			
		if (typeof(history.replaceState) != "undefined"){		    	
			history.replaceState(state, document.title, state.href);
		}
	},
	
	pushPageState : function() {
		pager.pushState({ 
			'href': pager.makeHref('page', pager.page), 
			'page': pager.page,
		});
		// let pop state can execute "toPage"
		pager.isShowed = false;		
	},
			
	makeHref : function (para, value, href) {
		var oldHref = href || $(location).attr('href');
		var paramStr = para + "=" + value;		
		return $.param.querystring(oldHref, paramStr);
	},
	
	changeRange : function() {
		var newRange = Math.floor(($(window).height() - pager.paddingTop) / pager.itemHeight);
		if (newRange != pager.range) {
			pager.range = newRange;
			return true;
		} else {
			return false;
		}
	},
	
	setWindowResizeEvent : function() {
		$(window).resize(function() {
			if (pager.changeRange()) {
				pager.showCurrent();
			}
		});
	},
			
	showCurrent : function() {
		var end = pager.itemNum - pager.range * (pager.page - 1);
		var start = end - pager.range + 1;
		pager.show(start,end);
	},
	
	show : function(start, end) {
		url = pager.url + "/" + start + "/" + end + pager.para;
		$(pager.resultObj).empty();
		util.ajax(url , null, function(res) {			
			$(pager.resultObj).html(res);
			if (pager.renewCallback) {
				pager.renewCallback();
			}
			$(pager.resultObj).toggle().fadeIn(200);
			
		}, 'get' ,false);
	},
	
	setPopEvent : function() {
		$(window).bind("popstate", function (event){
			var state = event.originalEvent.state;
			if (state != null && !pager.isShowed) {
				pager.toPage(state.page);
			}
	   	});		
	},
	
	firstToPage : function() {
		pager.replaceState({
			'href': pager.makeHref('page', pager.page), 
			'page': pager.page,
		});
		pager.toPage(pager.page);
		pager.isShowed = true;
	},
	
	toPage : function(page) {
		pager.page = page;
		pager.showCurrent();
		pager.changePageObj();
	},
	
	next : function() {
		var maxPage = Math.ceil(pager.itemNum / pager.range);
		if (pager.page < maxPage) {
			pager.toPage(pager.page + 1);		
		}
		pager.pushPageState();
	},
	
	prev : function() {
		if (pager.page > 1) {
			pager.toPage(pager.page - 1);		
		}
		pager.pushPageState();
	},
	first : function() {
		pager.toPage(1);
		pager.pushPageState();
	},
	last : function() {
		pager.toPage(Math.ceil(pager.itemNum / pager.range));	
		pager.pushPageState();
	},
	
	changePageObj : function() {
		$(pager.pageObj).html(pager.page);	
	},
	

}