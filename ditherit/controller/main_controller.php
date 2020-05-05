<?php
/**
 *
 * Dither it!. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Alex Harris, https://alexharris.online
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace alexharris\ditherit\controller;

/**
 * Dither it! main controller.
 */
class main_controller
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config		$config		Config object
	 * @param \phpbb\controller\helper	$helper		Controller helper object
	 * @param \phpbb\template\template	$template	Template object
	 * @param \phpbb\language\language	$language	Language object
	 */
	public function __construct(\phpbb\config\config $config, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\language\language $language)
	{
		$this->config	= $config;
		$this->helper	= $helper;
		$this->template	= $template;
		$this->language	= $language;
	}

	/**
	 * Controller handler for route /demo/{name}
	 *
	 * @param string $name
	 *
	 * @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	 */
	public function handle($name)
	{
		$l_message = !$this->config['alexharris_ditherit_goodbye'] ? 'DITHERIT_HELLO' : 'DITHERIT_GOODBYE';
		$this->template->assign_var('DITHERIT_MESSAGE', $this->language->lang($l_message, $name));

		return $this->helper->render('@alexharris_ditherit/ditherit_body.html', $name);
	}
}
