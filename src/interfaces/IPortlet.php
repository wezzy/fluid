<?php 

namespace Fluid;

interface IPortlet{
	
	/**
	*	Return a PortletInfo instance used by the system to manage the portlet
	*	@return PortletInfo with al the information requeste by the system
	*/
	public function info();
	
	/*
	*	Called when the administrator activate the portlet from the administration panel
	*/
	public function activate();
	
	/*
	*	Called when the administrator deactivate the portlet from the administration panel
	*/
	public function deactivate();
	
	/*
	*	Called when the administrator install the portlet for the first time it's a good place
	*	for all the code that prepares the environment for the portlet. For example it'a the place
	*	where you can create DB tables if your portlet need them
	*/
	public function install();
	
	/*
	*	Called when the portlet is removed from the system, please use this function to remove all the 
	* 	tables/files/resources that you have created during the install
	*/
	public function uninstall();
	
	/**
	*	Called when the portlet is loaded for the first time, this is the place for registering event's handlser
	*	using the EventsDispatcher->addListener() method or for other initialization before showing the content
	*	@param string $path the portlet's path, you may want to store it inside the instance so you can use to load you own resources
	*	@param string $identifier the portlet identifier used in the portlets_intances table and as a CSS id
	*/
	public function init($path, $identifier);
	
	/**
	*	It's the main method for the portlet, it shows the content of the portlet
	*	@return string an HTML string with the portlet content
	*/
	public function show();
	
	/**
	*	Return the html code to configure the portlet
	*	@return string the client-side code to configure this portlet
	*/
	public function configure();
        
	
}