<?php
/**
 *
 * Dither it!. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Alex Harris, https://alexharris.online
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace alexharris\ditherit\acp;

/**
 * Dither it! ACP module info.
 */
class main_info
{
	public function module()
	{
		return array(
			'filename'	=> '\alexharris\ditherit\acp\main_module',
			'title'		=> 'ACP_DITHERIT_TITLE',
			'modes'		=> array(
				'settings'	=> array(
					'title'	=> 'ACP_DITHERIT',
					'auth'	=> 'ext_alexharris/ditherit && acl_a_board',
					'cat'	=> array('ACP_DITHERIT_TITLE')
				),
			),
		);
	}
}
