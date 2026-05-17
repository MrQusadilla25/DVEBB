<?php
/**
 *
 * Birthday Message. Display a message to users who has birtday today. Made with love for phpBB.
 *
 * @copyright (c) 2025 Stoker
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [
	'BIRTHDAY_MESSAGE'	=> 'Happy Birthday, %s',
	'BIRTHDAY_WISH'		=> 'The staff and members of %s wish you a wonderful day',
]);