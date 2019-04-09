<div class="field field-file{{ $errors->has($name)?' has-error':'' }}">
	{!! Form::label($name, $label, ['class' => "field_label"]) !!}
	{!! Form::file($name, array_merge(['class' => "field_input field_file"], $attributes)) !!}
	@if ($errors->has($name))
		<div class="field_error">{{ $errors->first($name) }}</div>
	@endif
</div>