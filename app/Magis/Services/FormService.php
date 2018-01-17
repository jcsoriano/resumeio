<?php

namespace App\Magis\Services;

use Type;

class FormService
{
    public static function value(String $name, $values)
    {
        return old($name) ?? isset($values[$name]) ? (is_array($values[$name]) ? json_encode($values[$name]) : $values[$name]) : '';
    }

    public static function disabled(String $name, $disableOnEdit)
    {
        return isset($disableOnEdit) && in_array($name, $disableOnEdit) ? 'disabled' : '';
    }

    public static function field($type, $resource, $name, $errors, $options = [])
    {
        $wrapper = $options['wrapper'] ?? 'div';
        $id = $options['id'] ?? 'new';
        $values = $options['values'] ?? null;
        $disableOnEdit = $options['disableOnEdit'] ?? null;
        $fieldLabels = $options['fieldLabels'] ?? null;
        $labelClass = $options['labelClass'] ?? null;
        $inputWrapperClass = $options['inputWrapperClass'] ?? null;
        $inputClass = $options['inputClass'] ?? 'form-control';
        $aspectRatio = $options['aspectRatio'] ?? null;
        $relationAttributes = $options['relationAttributes'] ?? null;

        $typeMapped = Type::$map[$type] ?? $type;
        $relationAttribute = $relationAttributes[$name] ?? null;
        $disabled = self::disabled($name, $disableOnEdit);
        $value = e(self::value($name, $values));
        $path = $resource.'/'.$id;
        $label = $fieldLabels[$name] ?? title_case(str_replace('_', ' ', $name));
        $error = e(json_encode($errors->get($name)));

        return <<<EOT
<$wrapper 
    name="$name"
    label="$label" 
    type="$type" 
    error="$error" 
    input-wrapper-class="$inputWrapperClass" 
    label-class="$labelClass"
>
	<component 
        is="magis-$typeMapped" 
        relation-attribute="$relationAttribute"
        resource="$resource"
        type="$type"
        name="$name" 
        aspect-ratio="$aspectRatio" 
        $disabled 
        value="$value" 
        label="$label" 
        path="$path" 
        error="$error" 
        input-class="$inputClass"
    >
    </component>
</$wrapper>
EOT;
    }

    public static function string($resource, $name, $errors, $options = [], $type = 'string')
    {
        return self::field($type, $resource, $name, $errors, $options);
    }

    public static function email($resource, $errors, $options = [], $name = 'email')
    {
        return self::field('email', $resource, $name, $errors, $options);
    }

    public static function password($resource, $errors, $options = [], $name = 'password')
    {
        return self::field('password', $resource, $name, $errors, $options);
    }

    public static function text($resource, $name, $errors, $options = [], $type = 'text')
    {
        return self::field($type, $resource, $name, $errors, $options);
    }

    public static function mediumText($resource, $name, $errors, $options = [], $type = 'mediumText')
    {
        return self::field($type, $resource, $name, $errors, $options);
    }

    public static function photo($resource, $name, $errors, $options = [])
    {
        return self::field('photo', $resource, $name, $errors, $options);
    }
}
