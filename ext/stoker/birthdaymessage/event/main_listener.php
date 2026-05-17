<?php
/**
 *
 * Birthday Message. Display a message to users who has birtday today. Made with love for phpBB.
 *
 * @copyright (c) 2025 Stoker
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

declare(strict_types=1);

namespace stoker\birthdaymessage\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use phpbb\language\language;
use phpbb\template\template;
use phpbb\user;

/**
 * Event listener
 */
class main_listener implements EventSubscriberInterface
{
	/** @var language */
	protected $language;
	
	/** @var template */
	protected $template;

	/** @var user */
	protected $user;

	/**
	 * Constructor
	 *
	 * @param language	$language	Language object
	 * @param template	$template
	 * @param user		$user
	 */
	public function __construct(language $language, template $template, user $user)
	{
		$this->language = $language;
		$this->template = $template;
		$this->user = $user;
	}

	/**
	 * Assign functions defined in this class to event listeners in the core
	 *
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return [
			'core.index_modify_page_title' => 'check_birthday',
			'stoker.portal.main_controller_render_template_before' => 'check_birthday',
		];
	}

	/**
	 * Check if current user has birthday today
	 *
	 * @param \phpbb\event\data $event
	 */
	public function check_birthday($event)
	{
		$user_birthday = $this->user->data['user_birthday'];

		if (empty($user_birthday))
		{
			return;
		}

		$birthday_parts = explode('-', $user_birthday);

		if (count($birthday_parts) < 2)
		{
			return;
		}

		// Get current date in user's timezone
		$now = $this->user->create_datetime();
		$today_day = (int) $now->format('j');
		$today_month = (int) $now->format('n');

		$birth_day = (int) $birthday_parts[0];
		$birth_month = (int) $birthday_parts[1];

		if ($birth_day === $today_day && $birth_month === $today_month)
		{
			$this->language->add_lang('common', 'stoker/birthdaymessage');

			$this->template->assign_vars([
				'S_USER_BIRTHDAY'		=> true,
				'USER_BIRTHDAY_FULL'	=> get_username_string('full', $this->user->data['user_id'], $this->user->data['username'], $this->user->data['user_colour']),
			]);
		}
	}
}