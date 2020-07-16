<?php

namespace Crossbit\LaravelNova\Fields\DynamicSelect\Http\Controllers;

use Crossbit\LaravelNova\Fields\DynamicSelect\DynamicSelect;
use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class OptionsController extends Controller
{
    public function index(NovaRequest $request)
    {
        $attribute = $request->input('attribute');
        $dependValues = $request->input('depends');

        $resource = $request->newResource();

        // Nova tabs compatibility:
        // https://github.com/eminiarts/nova-tabs
        $fields = method_exists($resource, 'parentUpdateFields')
            ? $resource->parentUpdateFields($request)
            : $resource->updateFields($request);

        $field = $fields->findFieldByAttribute($attribute);

        // Flexible content compatibility:
        // https://github.com/whitecube/nova-flexible-content
        if (!$field) {
            foreach ($fields as $updateField) {
                if ($updateField->component == 'nova-flexible-content') {
                    foreach ($updateField->meta['layouts'] as $layout) {
                        foreach ($layout->fields() as $layoutField) {
                            if ($layoutField->attribute == $attribute) {
                                $field = $layoutField;
                            }
                        }
                    }
                }
            }
        }

        /** @var DynamicSelect $field */
        $options = $field->getOptions($dependValues);

        return [
            'options' => $options,
        ];
    }
}
