<?php

namespace Fluid;

/*
 * This is the interface that every plugin have to implements to work properly. It'a subset of the portlet's interface IPortlet
 */
interface IPlugin{

	/**
	 * Returns an array of informations
	 *
	 */
	public function info();

	/*
	*	Called when the administrator activate the plugin from the administration panel
	*/
	public function activate();

	/*
	*	Called when the administrator deactivate the plugin from the administration panel
	*/
	public function deactivate();

	/*
	*	Called when the administrator install the plugin for the first time it's a good place
	*	for all the code that prepares the environment for the plugin. For example it'a the place
	*	where you can create DB tables if your plugin need them
	*/
	public function install();

	/*
	*	Called when the plugin is removed from the system, please use this function to remove all the
	* 	tables/files/resources that you have created during the install
	*/
	public function uninstall();

	/**
	*	Called when the plugin is loaded at the beginning, this is the place for registering event's handlser
	*	using the EventsDispatcher->addListener() method or for other initialization before showing the content
	*/
	public function init();

	/**
	*	Return the html code to configure the plugin
	*	@return string the client-side code to configure this plugin
	*/
	public function configure();

}