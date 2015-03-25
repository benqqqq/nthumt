var ua = {
	me : null,
	users : null,
	baseUrl : null,
	
	membersInputObj : '#membersInput',
	memberDetailsObj : '#memberDetails',
	teamRoleObj : '#teamRole',
	memberListObj : '#memberList',
	
	members : {},	
	
	initMemberInputEvent : function() {
		ua.preventCursorMoving();
		ua.addEnterFocusEvent();
		ua.addLeaveFocusEvent();		
	},
	
	preventCursorMoving : function() {
		$(ua.membersInputObj).bind('keydown', function(e) {
			var code = e.keyCode || e.which;
			if (code == 38 || code == 40) {
				e.preventDefault();
			}
		});
	},	
	
	addEnterFocusEvent: function() {
		$(ua.membersInputObj).focus(function() {
			ua.lockEnterEvent($(this).parents('form'));			
			$(ua.memberDetailsObj).show();
		});
	},
	
	addLeaveFocusEvent : function() {
		$(ua.membersInputObj).blur(function() {			
			ua.releaseEnterEvent();
			ua.changeValueToFocusUserName(this);
			setTimeout(function() {					// If no timeout, user can't be selected by mouse click
				$(ua.memberDetailsObj).hide()
			}, 50);
		});
	},
	
	lockEnterEvent : function(form) {
		$(form).bind("keyup keypress", function(e) {
			var code = e.keyCode || e.which;
			if (code == 13) {
				e.preventDefault();
			}
		});
	},	
	
	releaseEnterEvent : function(form) {
		$(form).unbind("keyup keypress");
	},
	
	changeValueToFocusUserName : function(input) {
		if (typeof(ua.focusUser()) != 'undefined') {
			$(input).val(ua.focusUser().name);	
		}
	},
	
	focusUser : function() {
		return ua.users[$('.focus').val()];
	},
	
	
	searchUsers : function(e, input) {
		if (ua.isKeyingUpDownEnter(e)) {
			ua.moveFocusOrFinish(e);	 
		} else {
			var details = ua.findMatchedUsers(input);	
			$(ua.memberDetailsObj).html(details);
			ua.changeFocus(ua.firstMatchUser());			
			ua.addHoverEvent();
		}
	},
	
	isKeyingUpDownEnter : function(e) {
		return (e.keyCode == 38 || e.keyCode == 40 || e.keyCode == 13);
	},
	
	moveFocusOrFinish : function(e) {
		switch(e.keyCode) { 
			case 38:	// up
				if ($('.focus').prev().length != 0) {
					ua.changeFocus($('.focus').prev());	
				}						
				break;
			case 40:	// down
				if ($('.focus').next().length != 0) {
					ua.changeFocus($('.focus').next());	
				}						
				break;
			case 13:	// enter
				$(ua.teamRoleObj).focus();
				break;
		}				
	},
	
	changeFocus : function(target) {
		$('.focus').removeClass();
		$(target).addClass('focus');
	},
	
	findMatchedUsers : function(input) {
		var users = ua.users;
		var details = '';		
		if (input != '') {
			for (var i in users) {
				if (ua.isUserMatch(users[i], input)) {
					details += ua.generateUserDetailHtml(users[i], i);
				};
			}	
		}
		return details;
	},
	
	isUserMatch : function(user, str) {
		if (user.name.search(str) != -1 || user.email.search(str) != -1 || user.realName.search(str) != -1 ) {
			return true;			
		} else {
			return false;
		}
	},
	
	generateUserDetailHtml : function(user, index) {
		var detail = '';
		detail += "<li value=" +  index + ">";
		detail += 	"<span class='user'>";
		detail += 		"<img src=" + ua.baseUrl + "/" + user.profileSrc + ">";
		detail += 		"<span>" + user.name + "</span>";
		detail += 		"<span>(" + user.realName + " " + user.grade + " " + user.email + ")</span>";
		detail += 	"</span>";				
		detail += '</li>';
		return detail;
	},
	
	firstMatchUser : function() {
		return ua.memberDetailsObj + ' li:nth-child(1)';
	},
	
	addHoverEvent : function() {
		$(ua.memberDetailsObj + " li").hover(function() {
			ua.changeFocus(this);
		});
	},
	
	addFocusUser : function() {
		ua.addUser(ua.focusUser(), $(ua.teamRoleObj).val());
	},
	
	addLeader : function(user) {
		ua.addUser(user, '領隊');
	},
	
	addUser : function(user, teamRole) {
		ua.members[user.id] = { 'teamRole' : teamRole, 'user' : user };
		ua.renewHiddenInput();	
	},
	
	renewHiddenInput : function() {
		$('#members').val(JSON.stringify(ua.members));		
	},
	
	refreshMemberList : function() {
		var list = '';
		for (var i in ua.members) {
			var user = ua.members[i].user;
			var teamRole = ua.members[i].teamRole;
			list += ua.generateMemberHtml(user, teamRole);			
		}
		$(ua.memberListObj).html(list);
	},
	
	isLeaderExist : function() {
		for (var userId in ua.members) {
			if (ua.members[userId].teamRole.search('領隊') != -1) {
				return true;
			}
		}
		return false;
	},
	
	generateMemberHtml : function(user, teamRole) {
		var member = "";
		member += "<li class='region-member'>";
		member += 	"<button class='btn btn-delete' onclick='userAdder.deleteUser(" + user.id + "); userAdder.refreshMemberList()'>x</button>"; 
		member += 	"<a href=" + ua.baseUrl + "/user/" + user.id + " class='user'>";
		member += 		"<img src=" + ua.baseUrl + "/" + user.profileSrc + ">";
		member += 		"<span>" + user.name + "</span>";
		member += 		"<span>(" + user.realName + " " + user.grade + " " + teamRole + ")</span>";
		member += 	"</a>";		
		member += "</li>";
		return member;
	},
	
	deleteUser : function(userId) {
		delete ua.members[userId];
		ua.renewHiddenInput();
	},
	
	clearMemberInput : function() {
		$(ua.membersInputObj).val('');
		$(ua.memberDetailsObj).empty();
	},
	
	
}
var userAdder = ua;