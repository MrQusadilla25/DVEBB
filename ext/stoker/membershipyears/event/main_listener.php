<?php
/**
 *
 * Membership Years. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2026 Stoker
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
 
declare(strict_types=1);

namespace stoker\membershipyears\event;

use phpbb\user;
use phpbb\template\template;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class main_listener implements EventSubscriberInterface
{
	/** @var int Year in seconds */
	const YEAR_IN_SECONDS = 31557600;
	
	/** @var user */
	protected $user;
	
	/** @var template */
	protected $template;

	/** @var bool */
	protected $lang_loaded = false;

	/**
	 * Constructor
	 *
	 * @param user		$user		User object
	 * @param template	$template	Template object
	 */
	public function __construct(user $user, template $template)
	{
		$this->user	= $user;
		$this->template	= $template;
	}

	/**
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return [
			'core.memberlist_view_profile' => 'add_member_years_profiles',
			'core.viewtopic_cache_user_data' => 'viewtopic_cache_user_data',
			'core.viewtopic_modify_post_row' => 'add_member_years_post',
		];
	}

	/**
	 * Add membership years to profile view
	 *
	 * @param \phpbb\event\data $event Event object
	 * @return void
	 */
	public function add_member_years_profiles($event): void
	{
		$this->load_language();

		$join_time = (int) $event['member']['user_regdate'];
		$years = ($join_time > 0) ? (int) floor(($this->user->time_now - $join_time) / self::YEAR_IN_SECONDS) : 0;
		$this->template->assign_vars(['MEMBER_YEARS' => $years]);
	}
	
	/**
	 * Add membership years to viewtopic user cache data
	 *
	 * @param \phpbb\event\data $event Event object
	 * @return void
	 */
	public function viewtopic_cache_user_data($event): void
	{
		$join_time = (int) $event['row']['user_regdate'];
		$array = $event['user_cache_data'];
		$array['member_years'] = ($join_time > 0) ? (int) floor(($this->user->time_now - $join_time) / self::YEAR_IN_SECONDS) : 0;
		$event['user_cache_data'] = $array;
	}

	/**
	 * Add membership years to post row
	 *
	 * @param \phpbb\event\data $event Event object
	 * @return void
	 */
	public function add_member_years_post($event): void
	{
		$this->load_language();

		$post_row = $event['post_row'];
		$post_row['MEMBER_YEARS'] = (int) ($event['user_poster_data']['member_years'] ?? 0);
		$event['post_row'] = $post_row;
	}

	/**
	 * Load language file once
	 *
	 * @return void
	 */
	protected function load_language(): void
	{
		if (!$this->lang_loaded)
		{
			$this->user->add_lang_ext('stoker/membershipyears', 'common');
			$this->lang_loaded = true;
		}
	}
}