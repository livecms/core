<?php

$adminSlug = getSlug('admin');
$menus = config('livecms.menus.admin');

?>

<!-- Home -->
<li class="@if(isInCurrentRoute('admin.home'))active @endif"><a href="{{ route('admin.home') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>

{!! getMenus($adminSlug, $menus) !!}
