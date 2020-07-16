<?php

namespace Crossbit\LaravelNova\Fields\DynamicSelect;

use Crossbit\LaravelNova\Fields\DynamicSelect\Traits\DependsOnAnotherField;
use Crossbit\LaravelNova\Fields\DynamicSelect\Traits\HasDynamicOptions;
use Laravel\Nova\Fields\Field;

class DynamicSelect extends Field
{
    use HasDynamicOptions;
    use DependsOnAnotherField;

    public $component = 'dynamic-select';

    public function resolve($resource, $attribute = null)
    {
        $this->extractDependentValues($resource);

        return parent::resolve($resource, $attribute);
    }

    public function meta()
    {
        $this->meta = parent::meta();
        return array_merge([
            'options' => $this->getOptions($this->dependentValues),
            'dependsOn' => $this->getDependsOn(),
            'dependValues' => $this->dependentValues,
        ], $this->meta);
    }
}
