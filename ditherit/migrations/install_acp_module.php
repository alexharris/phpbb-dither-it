<?php
/**
 *
 * Dither it!. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Alex Harris, https://alexharris.online
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace alexharris\ditherit\migrations;

class install_acp_module extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['alexharris_ditherit_goodbye']);
	}

	public static function depends_on()
	{
		return array('\phpbb\db\migration\data\v320\v320');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('alexharris_ditherit_goodbye', 0)),

			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_DITHERIT_TITLE'
			)),
			array('module.add', array(
				'acp',
				'ACP_DITHERIT_TITLE',
				array(
					'module_basename'	=> '\alexharris\ditherit\acp\main_module',
					'modes'				=> array('settings'),
				),
			)),
		);
	}
}
