<div class="field field-min{{ $errors->has($name)?' has-error':'' }}">
	{!! Form::label($name, $label, ['class' => "field_label"]) !!}
	{!! Form::$type($name, $value, array_merge(['class' => "field_input"], $attributes)) !!}
	@if ($errors->has($name))
		<div class="field_error">{{ $errors->first($name) }}</div>
	@endif
</div>
