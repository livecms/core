<?php
$source = LC_CurrentTheme().'.views.auth.login';
$targetView = 'livecms-templates::'.$source; ?>
@extends($targetView, ['action' => 'register'])