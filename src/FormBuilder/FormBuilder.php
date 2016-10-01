<?php

namespace LiveCMS\FormBuilder;

use Form;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use LiveCMS\Models\Core\BaseModel;

// HTML Support Tags :
// text
// readonly
// readonly_url
// password
// email
// tel
// number
// date
// datetime
// datetime_local
// time
// url
// color
// textarea
// texteditor
// select
// tagged_multi_select
// select_range
// select_year
// select_month
// checkbox
// radio
// image
// file

class FormBuilder
{
    protected $model;
    protected $viewTemplate;
    protected $labelClass;
    protected $inputClass;
    protected $additionalFields = [];
    protected $customFields = [];

    public function __construct($model)
    {
        if (! ($model instanceof BaseModel)) {
            return;
        }
        $this->model = $model;
        $this->setTemplate(config('livecms.formfieldtemplate'));
        $this->labelClass = config('livecms.formfieldlabelclass');
        $this->inputClass = config('livecms.formfieldinputclass');

    }

    public function setTemplate($viewTemplate = null)
    {
        $this->viewTemplate = $viewTemplate;
        return $this;
    }

    protected function fieldWrapper($field, $fieldInput)
    {
        $fieldLabel = Form::label($field, trans('livecms::'.$this->groupName.'.'.$field), ['class' => $this->labelClass]);
        return str_replace(['$fieldLabel', '$fieldInput'], [$fieldLabel, $fieldInput], $this->viewTemplate);
    }

    public function textType($model, $field)
    {
        $fieldInput = Form::text($field, $model->$field, ['class' => $this->inputClass]);
        return $this->fieldWrapper($field, $fieldInput);
    }

    public function readonlyType($model, $field)
    {
        $fieldInput = '<p class="form-static">'.$model->$field.'</p>';
        return $this->fieldWrapper($field, $fieldInput);
    }

    public function readonlyUrlType($model, $field)
    {
        $fieldInput = '<p class="form-static"><a href="'.$model->$field.'" target="_blank">'.$model->$field.'</a></p>';
        return $this->fieldWrapper($field, $fieldInput);
    }

    public function selectType($model, $field)
    {
        $fieldId = $field.'_id';
        $fieldModel = $model->{$field}()->getRelated();
        $fillable = $fieldModel->getFillable();
        $fieldName = in_array($field, $fillable) ? $field : (in_array('name', $fillable) ? 'name' : (in_array('title', $fillable) ? 'title' : array_first($fillable)));
        $fieldInput = Form::select($field, [null => trans('livecms::livecms.choose')] + $fieldModel->pluck($fieldName, 'id')->toArray(), $model->{$fieldId}, ['class' => $this->inputClass]);
        return $this->fieldWrapper($field, $fieldInput);
    }

    public function taggedMultiSelectType($model, $fields)
    {
        $field = Str::singular($fields);
        $fieldInput = Form::select($fields.'[]', $model->{$fields}()->getRelated()->pluck($field, 'id')->toArray(), $model->{$fields}->pluck('id')->toArray(), ['class' => $this->inputClass, 'multiple' => true, 'data-tags' => true]);
        return $this->fieldWrapper($field, $fieldInput);
    }

    public function textareaType($model, $field)
    {
        $fieldInput = Form::textarea($field, $model->$field, ['class' => $this->inputClass]);
        return $this->fieldWrapper($field, $fieldInput);
    }

    public function imageType($model, $field)
    {
        $thumbnail = $field.'_thumbnail';
        $image = $model->$thumbnail ?: ($model->$field ?: null);
        $input = Form::file($field, null, ['class' => $this->inputClass]);

        $fieldInput = '';
        if ($image) {
            $fieldInput .= '
                <div class="row">
                    <div class="col-sm-4 col-md-3">
                        Preview :
                        <figure style="width: 100%;">
                            <img src="'.$image.'" class="img-responsive" alt="'.$image.'">
                        </figure>
                        <div class="row">&nbsp;</div>
                    </div>
                </div>
            ';
        }
        $fieldInput .= '
            <div class="row">
                <div class="col-sm-12">
                    '.($image ? '<strong>'.trans('livecms::livecms.ifwanttochangepicture').'</strong>' : '').'
                    '.$input.'
                </div>
            </div>
        ';
        return $this->fieldWrapper($field, $fieldInput);
    }

    public function setField($field, $beforeField = null)
    {
        if (!isset($this->additionalFields[$beforeField])) {
            $this->additionalFields[$beforeField] = [];
        }
        $this->additionalFields[$beforeField][] = $field;
        return $this;
    }

    public function addCustomField($field, $html)
    {
        $this->customFields[$field] = $this->fieldWrapper($field, $html);
        return $this;
    }

    protected function getFields($defFields = [])
    {
        $defFields = array_merge($defFields, $this->fields ?: array_merge($this->model->getFillable(), $this->model->getDependencies()));
        $fields = [];
        foreach ($defFields as $field) {
            if (isset($this->additionalFields[$field])) {
                foreach ($this->additionalFields[$field] as $addField) {
                    $fields[] = $addField;
                }
            }
            if (!in_array($field, $fields)) {
                $fields[] = $field;
            }
            if (isset($this->additionalFields[$field])) {
                unset($this->additionalFields[$field]);
                return $this->getFields($fields);
            }
        }
        return $fields;
    }

    public function make()
    {
        if (! ($formStructure = $this->model->getForms())) {
            return '';
        }
        $structures = [];
        array_walk($formStructure, function ($item, $key) use (&$structures) {
            foreach ($item as $i) {
                $structures[$i] = $key;
            }
        });

        $fields = $this->getFields();

        $customFields = $this->customFields;
// dd($fields, $structures['url'], $formStructure, $customFields);
        $formFields = '';
        foreach ($fields as $field) {
            if (isset($customFields[$field])) {
                $formFields .= $customFields[$field];
            }
            if (isset($structures[$field])) {
                $fieldType = Str::camel($structures[$field].'Type');
                $formFields .= method_exists($this, $fieldType) ? $this->{$fieldType}($this->model, $field) : '';
            }
        }
        return $formFields;
    }

    public static function model($model, $groupName, $fields = null, $callback = null)
    {
        $instance = new static($model);
        $instance->groupName = $groupName;
        $instance->fields = $fields;
        if (is_callable($callback)) {
            call_user_func_array($callback, [$instance]);
        }
        return $instance->make();
    }
}
