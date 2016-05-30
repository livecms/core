<?php

namespace LiveCMS\Models\Contracts;

interface BaseModelInterface
{
    public function dependencies();

    public function rules();

    public function getFields();
}
