{!! LiveCMS\FormBuilder\FormBuilder::model(${$base}, $groupName, null, function ($builder) use ($model, $contentName) {
    $builder->setField('permalink', $contentName);
    $builder->setField('url', 'permalink');
    $builder->addCustomField('permalink', Form::text('permalink', $model->permalink ? $model->permalink->permalink : '', ['class' => 'form-control', 'placeholder' => url('path/sebagai/permalink')]));
}) !!}


