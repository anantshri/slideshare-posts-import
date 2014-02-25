<?php
/**
 * Discribe and initialize Slideshare cron tasks
 *
 * @package   SlideshareCron
 * @author    Spoon <spoon4@gmail.com>
 * @license   GPL-2.0+
 * @link      https://github.com/Spoon4/slideshare-posts-import
 * @copyright 2014 Spoon
 */
class SlideshareCron
{
	const EVENT_NAME = 'slideshare_posts_import_cron_task';
	
	/**
	 * Initilize the WordPress schedule.
	 *
	 * @since     1.0.0
	 */
	public function init()
	{
		add_action(SlideshareCron::EVENT_NAME, array($this, 'get_user_slideshares_task'));
	    add_filter('cron_schedules', array($this, 'add_cron_schedule'));
	}
	
	/**
	 * Task to be executed by the schedule
	 *
	 * @since     1.0.0
	 */
	public function get_user_slideshares_task()
	{
		$user = get_option('SLIDESHARE_NAME');

		if($user) {
			$result = get_user_slideshares($user, array('detailed' => 1, 'limit' => 5));

			if(!is_wp_error($result)) {
				$importer = new SlideshareImporter($result->getSlideshows());
				$importer->import();
			} else {
				error_log("cron task error: ".$result->get_error_message());
			}
		}
	}
	
	/**
	 * Callback function of the 'cron_schedules' filter.
	 *
	 * @since     1.0.0
	 *
	 * @param     array    $schedules    The WordPress schedules array parameter.
	 * @return    array    The WordPress schedules array parameter.
	 */
    public function add_cron_schedule($schedules) 
    {
		$import_interval = (int) get_option('SLIDESHARE_IMPORT_INTERVAL');
		
        $schedules[self::schedule_name()] = array(
            'interval' => $import_interval * 3600,
            'display' => sprintf(__('Each %s hours'), $import_interval),
        );
        return $schedules;
    }
	
	/**
	 * Return the name of the Slideshare import schedule
	 * from the admin Slideshare import page setting.
	 *
	 * @since     1.0.0
	 *
	 * @return    string    The name of the Slideshare import schedule.
	 */
	public static function schedule_name()
	{
		return 'each'.get_option('SLIDESHARE_IMPORT_INTERVAL').'hours'; 
	}
}