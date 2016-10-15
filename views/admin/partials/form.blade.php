<div class="row">
	<div class="col-md-{{ $width or '7' }}">
		<div class="box">
		  	<div class="box-body">
				{!! Form::model($model, ['method' => !isset($params['id']) ? 'post' : 'put', 'url' => action($baseClass.'@'.$action, !isset($params) ? [] : $params), 'files' => isset($files) ?: false, 'id' => $base.'form']) !!}
				
				@yield('form')

				@if (!isset($withoutFormButtons))
				<hr>
				
				<div class="row form-group">
					<div class="col-md-{{ $formLeftWidth or '2' }}">&nbsp;</div>
					<div class="col-md-9">
					@if (isset($withPreview) && $withPreview)
                        {!! Form::button('<i class="fa fa-search"></i> '.trans('livecms::livecms.saveandpreview'), ['class' => 'btn bg-navy', 'name' => 'save_and_preview', 'value' => 'true', 'type' => 'submit']) !!}
					@endif
						{!! Form::submit(trans('livecms::livecms.save'), ['class' => 'btn btn-success']) !!}
						<a href="{{ action($baseClass.'@index') }}" class="btn btn-default">{{trans('livecms::livecms.cancel')}}</a>
					</div>
				</div>
				@endif

				{!! Form::close() !!}
		  	</div><!-- /.box-body -->
		</div><!-- /.box-->
	</div>
</div>