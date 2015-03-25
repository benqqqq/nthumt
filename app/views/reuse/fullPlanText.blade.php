# 隊名

{{{ $team->name }}}

# 前言

{{{ $team->foreword }}}

# 山區簡介

{{{ $team->intro }}}	

# 日期 

{{{ $team->startDate . ' ~ ' . $team->backDate }}}

# 預定行程

{{{ $team->plan }}}

	
	- 預定下山時間 : {{ $team->backDatetime }}	
	- 撤退計畫 : {{ $team->retreat }}
	- 山難期限 : {{ $team->deadline }}
	- 留守 : {{{ $team->leftPerson }}}
	- 安中 : {{{ $team->safetyPerson }}}
	- 交通方式 : 	{{{ $team->traffic }}}
	- 無線電頻道 : {{{ $team->channel }}}
	- 台號 : {{{ $team->channelName }}}
	- 開機時段 : 	{{{ $team->channelPeriod }}}
	- 衛星電話號碼 : {{{ $team->satellitePhone }}}
	
	
# 參考資料

{{ $team->reference }}
				
# 人員組成

@foreach ($team->members as $member)
{{{ $member->name }}} ({{{ $member->pivot->teamRole }}})
@endforeach
	
{{ $team->unregisteredMembers }}					

{{ $team->memberComposition }}

# 器材裝備
	
{{ $team->equipments }}

# 隊員要求

{{ $team->memberRequire }}

# 隊費

{{{ $team->fee }}}

# 重要時程

{{ $team->importantDate }}

# 想說的話

{{{ $team->greetings }}}