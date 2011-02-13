<?php

namespace Fluid;

interface IComponent{

	/*
	 * Returns an associative array with two keys 'js' and 'css' with all the data required for that component
	 */
	public function load();
	
	/*
	 * Returns the markup for the button in the components list, it's better to call the GenericComponent->makeButton() method
	 * to get an uniform design
	 */
	public function displayButton();

}