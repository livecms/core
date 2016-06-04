<?php

$userSlug = getSlug('userhome');
$menus = config('livecms.menus.user');

?>

{!! getMenus($userSlug, $menus) !!}

@if (auth()->user()->is_administer)
<li class="@if(isInCurrentRoute('admin.home'))active @endif"><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> <span>{{trans('livecms::livecms.dashboard')}}</span></a></li>
@endif
