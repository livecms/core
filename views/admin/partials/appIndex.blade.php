@extends('livecms::backend')

@section('content')
<div class="topbutton @if (!isset($withoutStickedTopButton)) run @endif ">
	<div class="row">
		<div class="col-xs-6 buttons">
		@if(isset($withoutAddButton))
		@else
			<p>
			<a href="{{ action($baseClass.'@create', request()->query()) }}" class="btn btn-danger">{{trans('livecms::livecms.add')}}</a> &nbsp;<span class="hidden-xs">{{trans('livecms::livecms.clicktoadd')}} {{ trans('livecms::'.($groupName ?: 'livecms').'.' .$base) }}.</span>
			</p>
		@endif
		</div>
		<div class="col-xs-6 row">
			<div class="col-xs-12 visible-xs visible-sm">&nbsp;</div> 
			@yield('index.submenu')
		</div>
	</div>
</div>
<h4>
</h4>
<div class="row">
    <div class="col-sm-12">
		<table class="table datatables display responsive no-wrap">
			<thead>
			@foreach(array_values($fields) as $field)
				<th @if ($field == 'id') class="desktop" @endif>{{ trans('livecms::'.($groupName ?: 'livecms').'.' .strtolower($field)) }}</th>
			@endforeach
                <th id="menu-control" class="all">Menu</th>
			</thead>
		</table>  	  	
  	</div><!-- /.box-body -->
</div><!-- /.box-->
@stop
