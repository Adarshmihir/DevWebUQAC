<?php
namespace App\Providers;

use Collective\Html\FormFacade as Form;
use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 * @return void
	 */
	public function boot() {
		Form::component('fieldText', 'partials.components.form.text', ['name', 'label', 'value' => null, 'attributes' => [], 'type' => 'text']);
		Form::component('fieldDate', 'partials.components.form.text', ['name', 'label', 'value' => null, 'attributes' => [], 'type' => 'date']);
		//Form::component('fieldPassword', 'components.form.password', ['name', 'label', 'attributes' => []]);
		Form::component('fieldTextMin', 'partials.components.form.text-min', ['name', 'label', 'value' => null, 'attributes' => [], 'type' => 'text']);
		Form::component('fieldTitle', 'partials.components.form.text-title', ['name', 'label', 'value' => null, 'attributes' => [], 'min' => true, 'type' => 'text']);
		Form::component('fieldImage', 'partials.components.form.image', ['name', 'label', 'attributes' => []]);
		Form::component('fieldSelect', 'partials.components.form.select', ['name', 'label', 'options', 'value' => null, 'attributes' => []]);
		Form::component('fieldSelectMin', 'partials.components.form.select-min', ['name', 'label', 'options', 'value' => null, 'attributes' => []]);
		Form::component('fieldCheckbox', 'partials.components.form.checkbox', ['name', 'label', 'value' => false, 'attributes' => []]);
		Form::component('fieldRadio', 'partials.components.form.radio', ['name', 'label', 'value', 'default' => false, 'attributes' => []]);
	}

	/**
	 * Register any application services.
	 * @return void
	 */
	public function register() {
	}
}
