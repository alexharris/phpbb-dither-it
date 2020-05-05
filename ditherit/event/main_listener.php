<?php
/**
 *
 * Dither it!. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Alex Harris, https://alexharris.online
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace alexharris\ditherit\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Dither it! Event listener.
 */
class main_listener implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return array(
			'core.user_setup'							=> 'load_language_on_setup',
			'core.page_header'							=> 'add_page_header_link',
			'core.viewonline_overwrite_location'		=> 'viewonline_page',
			'core.modify_uploaded_file' => 'dither_image'
		);
	}

	/* @var \phpbb\language\language */
	protected $language;

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	/* @var \phpbb\config\config */
	protected $config;	

	/** @var string phpEx */
	protected $php_ext;	

	/**
	 * Constructor
	 *
	 * @param \phpbb\language\language	$language	Language object
	 * @param \phpbb\controller\helper	$helper		Controller helper object
	 * @param \phpbb\template\template	$template	Template object
	 * @param \phpbb\config\config	    $config	    Config object
	 * @param string                    $php_ext    phpEx
	 */
	public function __construct(\phpbb\language\language $language, \phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\config\config $config, $php_ext)
	{
		$this->language = $language;
		$this->helper   = $helper;
		$this->template = $template;
		$this->config   = $config;		
		$this->php_ext  = $php_ext;
	}

	/**
	 * Load common language files during user setup
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'alexharris/ditherit',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	 * Add a link to the controller in the forum navbar
	 */
	public function add_page_header_link()
	{
		$this->template->assign_vars(array(
			'U_DITHERIT_PAGE'	=> $this->helper->route('alexharris_ditherit_controller', array('name' => 'world')),
		));
	}

	/**
	 * Show users viewing Dither it! page on the Who Is Online page
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function viewonline_page($event)
	{
		if ($event['on_page'][1] === 'app' && strrpos($event['row']['session_page'], 'app.' . $this->php_ext . '/demo') === 0)
		{
			$event['location'] = $this->language->lang('VIEWING_ALEXHARRIS_DITHERIT');
			$event['location_url'] = $this->helper->route('alexharris_ditherit_controller', array('name' => 'world'));
		}
	}

	/**
	 * Dither uploaded images
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function dither_image($event)
	{
		// Get the filedata from the event object 
		$filedata = $event['filedata'];
		//contains:
		//  filesize
		//  mimetype
		//  extension
		//  physical_filename
		//  real_filename
		//  filetime
		//  post_attach
		//  error
		//  thumbnail			
		error_log($this->config['upload_path'], 0);

		// Get the destination of the uploaded file
		$dest_file = getcwd() . '/' . $this->config['upload_path'] . '/' . utf8_basename($filedata['physical_filename']);

		// Get the files extension
		$ext = $filedata['extension'];

		// Run the approriate php image manipulation commands based on the file type
		switch ($ext) {
			case "jpg":
				$im = imagecreatefromjpeg($dest_file);
				imagetruecolortopalette($im, true, 4);
				imagejpeg($im, $dest_file);
				imagedestroy($im);
				break;
			case "gif":
				$im = imagecreatefromgif($dest_file);
				imagetruecolortopalette($im, true, 4);
				imagegif($im, $dest_file);
				imagedestroy($im);
				break;
			case "png":
				$im = imagecreatefrompng($dest_file);
				imagetruecolortopalette($im, true, 4);
				imagepng($im, $dest_file);
				imagedestroy($im);
				break;
			default:
				error_log('The image is not a gif, jpg, or png', 0);
		}

	}	

}
