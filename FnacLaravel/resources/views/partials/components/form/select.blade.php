<div class="field field-select{{ $errors->has($name)?' has-error':'' }}">
	{!! Form::label($name, $label, ['class' => "field_label"]) !!}
	{!! Form::select($name, $options, $value, array_merge(['class' => "field_select"], $attributes)) !!}
	@if ($errors->has($name))
		<div class="field_error">{{ $errors->first($name) }}</div>
	@endif
</div>