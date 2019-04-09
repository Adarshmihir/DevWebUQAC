<?php
$ID = 'Form'.strtoupper(substr(md5(uniqid()), 2, 2)).str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
if (isset($attributes['div-padding'])) {
	$divPadding = $attributes['div-padding'];
	unset($attributes['div-padding']);
}
?>
<div class="field-checkbox{{ $errors->has($name)?' has-error':'' }}"{!! isset($divPadding) ? "style='padding-top: {$divPadding}'" : null !!}>
	{!! Form::hidden($name, '0') !!}
	{!! Form::checkbox($name, '1', $value, array_merge(['class' => "field_checkbox", 'id' => $ID], $attributes)) !!}
	{!! Form::label($ID, $label, ['class' => "field_label"]) !!}
</div>