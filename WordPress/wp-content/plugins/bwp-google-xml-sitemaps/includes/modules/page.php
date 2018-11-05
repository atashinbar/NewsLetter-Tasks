<?php
/**
 * Copyright (c) 2014 Khang Minh <betterwp.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU GENERAL PUBLIC LICENSE
 * @package BWP Google XML Sitemaps
 */

class BWP_GXS_MODULE_PAGE extends BWP_GXS_MODULE
{
	public function __construct()
	{
		// @since 1.3.0 this is left blank
	}

	protected function generate_data()
	{
		global $wpdb, $post;

		// @since 1.3.1 use a different filter hook that expects an array instead
		$excluded_posts   = apply_filters('bwp_gxs_excluded_posts', array(14284,7819,7902,15016,13449,14604,8249,8267,12237,2879,9752,3254,12081,2152,2139,2144,14769,8370,5548,13913,14622,5467,8256,5556,5541,12520,5539,2133,9452,15150,14748,14489,15079,14955,14942,10993,7597,14829,8158,7850,7845,7857,8383,11088,11059,11054,7703,9697,9684,1727,3318,11516,7144,5048,5044,5036,4873,3227,2859,2850,2840,2835,2830,2821,3331,1747,4267,2822,1724,2815,1960,1974,1993,1985,1965,1968,3546,3534,1989,1991,2006,1975,2004,594,14,13,45,12,21,22,23,33,15,31,10798,11794,15183,14803,14801,14799,14937,12553,12581,12577,12720,12334,4320), 'page');
		$exclude_post_sql = sizeof($excluded_posts) > 0
			? ' AND p.ID NOT IN (' . implode(',', $excluded_posts) . ') '
			: '';

		// @since 1.3.1 this should be used to add other things to the SQL
		// instead of excluding posts
		$sql_where = apply_filters('bwp_gxs_post_where', '', 'page');
		$sql_where = str_replace('wposts', 'p', $sql_where);

		$latest_post_query = '
			SELECT *
			FROM ' . $wpdb->posts . " p
			WHERE p.post_status = 'publish'
				AND p.post_password = ''
				AND p.post_type = 'page'"
				. $exclude_post_sql
				. $sql_where . '
			ORDER BY p.post_modified DESC';

		$latest_posts = $this->get_results($latest_post_query);

		if (!isset($latest_posts) || 0 == sizeof($latest_posts))
			return false;

		$using_permalinks = $this->using_permalinks();

		$data = array();

		for ($i = 0; $i < sizeof($latest_posts); $i++)
		{
			$post = $latest_posts[$i];
			$data = $this->init_data($data);

			if ($using_permalinks && empty($post->post_name))
			{
				$data['location'] = '';
			}
			else
			{
				wp_cache_add($post->ID, $post, 'posts');
				$data['location'] = get_permalink();
			}

			$data['lastmod']  = $this->get_lastmod($post);
			$data['freq']     = $this->cal_frequency($post);
			$data['priority'] = $this->cal_priority($post, $data['freq']);

			$this->data[] = $data;
		}

		unset($latest_posts);

		// always return true if we can get here,
		// otherwise we're stuck in a SQL cycling loop
		return true;
	}
}
