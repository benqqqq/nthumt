<p>
	{{ Form::label('membersInput', '新增(更動) 隊員') }}
	{{ Form::text('membersInput', null, ['onkeyup' => 'userAdder.searchUsers(event, this.value)', 'autocomplete' => 'off']) }}	
	{{ Form::text('teamRole', null, 
		['placeholder' => '實領', 'id' => 'teamRole', 'onkeydown' => "util.triggerBtn(event, '#addUserBtn')",
		 'autocomplete' => 'off' ]) }}
	<span id='addUserBtn' class='btn btn-green' onclick="userAdder.addFocusUser(); userAdder.refreshMemberList(); userAdder.clearMemberInput(); ">確定</span>
	<p class='desc'>輸入隊員帳號 (email) 或暱稱、姓名關鍵字，或請他至網頁報名</p>
	
	<ul id='memberDetails'></ul>
	<ul id='memberList'></ul>
	<span class='red warn' hidden="true">至少要有一位領隊哦 ! (職稱包含"領隊")</span>
	
	{{ Form::hidden('members', null, ['id' => 'members']) }}
</p>
