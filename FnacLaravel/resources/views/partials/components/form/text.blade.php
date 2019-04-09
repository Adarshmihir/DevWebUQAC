<div class="field{{ $errors->has($name)?' has-error':'' }}">
	{!! Form::label(isset($attributes['id']) ? $attributes['id'] : $name, $label, ['class' => "field_label"]) !!}
	{!! Form::$type($name, $value, array_merge(['class' => "field_input"], $attributes)) !!}
	@if ($errors->has($name))
		<div class="field_error">{{ $errors->first($name) }}</div>
	@endif
</div>