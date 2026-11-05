<?php


if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('InfoCards_Analytics')) {
	class InfoCards_Analytics
	{

		const OPTION_DATA = 'infocards_analytics_data';


		const OPTION_DAILY = 'infocards_daily_clicks';


		const OPTION_RETENTION = 'infocards_analytics_retention';

		const PAGE_SLUG = 'info-cards-analytics';

		private $hook_suffix;


		const HISTORY_DAYS = 90;


		const MAX_ENTRIES = 300;

		const DEDUPE_SECONDS = 60;

		const MAX_TEXT = 200;

		const PER_PAGE = 10;

		public function __construct()
		{
			add_action('admin_menu', [$this, 'register_menu'], 11);
			add_action('admin_enqueue_scripts', [$this, 'enqueue']);
			add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend']);

			add_action('wp_ajax_infocards_track_click', [$this, 'track']);
			add_action('wp_ajax_nopriv_infocards_track_click', [$this, 'track']);

			add_action('admin_post_infocards_export_analytics', [$this, 'export_csv']);
			add_action('admin_post_infocards_clear_analytics', [$this, 'clear']);
			add_action('admin_post_infocards_save_retention', [$this, 'save_retention']);

			add_action('infocards_daily_retention_cleanup', [$this, 'apply_retention']);

			if (!wp_next_scheduled('infocards_daily_retention_cleanup')) {
				wp_schedule_event(time(), 'daily', 'infocards_daily_retention_cleanup');
			}
		}

		public function register_menu()
		{
			$this->hook_suffix = add_submenu_page(
				'info-cards-dashboard',
				__('Analytics', 'info-cards'),
				__('Analytics', 'info-cards'),
				'manage_options',
				self::PAGE_SLUG,
				[$this, 'render']
			);
		}


		public function enqueue($hook)
		{
			if (empty($this->hook_suffix) || $hook !== $this->hook_suffix) {
				return;
			}

			wp_enqueue_style(
				'infocards-admin-screens',
				ICB_DIR_URL . 'assets/css/analytics.css',
				[],
				ICB_VERSION
			);
		}


		public function enqueue_frontend()
		{
			wp_enqueue_script(
				'infocards-analytics-tracker',
				ICB_DIR_URL . 'assets/js/analytics-tracker.js',
				[],
				ICB_VERSION,
				true
			);

			wp_localize_script(
				'infocards-analytics-tracker',
				'infocardsAnalytics',
				[
					'ajaxurl' => admin_url('admin-ajax.php'),
					'nonce'   => wp_create_nonce('infocards_analytics'),
					'action'  => 'infocards_track_click',
				]
			);
		}


		public function track()
		{
			$nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';

			if (!wp_verify_nonce($nonce, 'infocards_analytics')) {
				wp_send_json_error('bad nonce', 403);
			}

			$title = isset($_POST['title']) ? sanitize_text_field(wp_unslash($_POST['title'])) : '';
			$title = trim(mb_substr($title, 0, self::MAX_TEXT));

			if ('' === $title) {
				wp_send_json_error('no title', 400);
			}

			$key = md5($title);

			if ($this->is_bot()) {
				wp_send_json_success(['recorded' => false, 'reason' => 'bot']);
			}

			if ($this->seen_recently($key)) {
				wp_send_json_success(['recorded' => false, 'reason' => 'duplicate']);
			}

			$source = isset($_POST['source_url']) ? esc_url_raw(wp_unslash($_POST['source_url'])) : '';
			$source = $this->local_url($source);

			$data  = $this->read(self::OPTION_DATA);
			$today = $this->today();

			if (!isset($data[$key])) {

				if (count($data) >= self::MAX_ENTRIES) {
					wp_send_json_success(['recorded' => false]);
				}

				$data[$key] = ['title' => $title, 'count' => 0, 'last' => '', 'source' => $source, 'daily' => []];
			}

			$entry = $data[$key];

			$entry['title']  = $title;
			$entry['count']  = (int) $entry['count'] + 1;
			$entry['last']   = $today;
			$entry['source'] = $source ? $source : ($entry['source'] ?? '');
			$entry['daily'][$today] = (int) ($entry['daily'][$today] ?? 0) + 1;
			$entry['daily']  = $this->trim_days($entry['daily']);

			$data[$key] = $entry;

			$this->write(self::OPTION_DATA, $data);

			$daily         = $this->read(self::OPTION_DAILY);
			$daily[$today] = (int) ($daily[$today] ?? 0) + 1;

			$this->write(self::OPTION_DAILY, $this->trim_days($daily));

			$this->remember_visit($key);

			wp_send_json_success(['recorded' => true]);
		}


		private function is_bot()
		{
			$agent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower(sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT']))) : '';

			if ('' === $agent) {
				// No user agent at all is not a browser doing this.
				return true;
			}

			$needles = [
				'bot', 'crawl', 'spider', 'slurp', 'scrape', 'curl', 'wget',
				'python-requests', 'headlesschrome', 'phantomjs', 'lighthouse',
				'pingdom', 'gtmetrix', 'facebookexternalhit', 'preview',
			];

			foreach ($needles as $needle) {
				if (false !== strpos($agent, $needle)) {
					return true;
				}
			}


			return (bool) apply_filters('infocards_is_bot', false, $agent);
		}

		private function visitor_key()
		{
			$ip    = !empty($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '';
			$agent = !empty($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : '';

			return substr(md5($ip . '|' . $agent . '|' . wp_salt('nonce')), 0, 20);
		}

		private function seen_recently($card_key)
		{
			return (bool) get_transient('infocards_seen_' . $this->visitor_key() . '_' . substr($card_key, 0, 8));
		}


		private function remember_visit($card_key)
		{
			set_transient(
				'infocards_seen_' . $this->visitor_key() . '_' . substr($card_key, 0, 8),
				1,
				self::DEDUPE_SECONDS
			);
		}


		private function local_url($url)
		{
			if ('' === $url) {
				return '';
			}

			$home = wp_parse_url(home_url());
			$this_one = wp_parse_url($url);

			if (empty($this_one['host']) || empty($home['host'])) {
				return '';
			}

			return strtolower($this_one['host']) === strtolower($home['host']) ? $url : '';
		}



		private function read($option)
		{
			$value = get_option($option, []);

			return is_array($value) ? $value : [];
		}

		private function write($option, array $value)
		{
			update_option($option, $value, false);
		}

		private function trim_days(array $days)
		{
			$cutoff = $this->day_string($this->today_date()->modify('-' . (self::HISTORY_DAYS - 1) . ' days'));

			foreach (array_keys($days) as $day) {
				if ((string) $day < $cutoff) {
					unset($days[$day]);
				}
			}

			return $days;
		}

		private function today_date()
		{
			return new DateTimeImmutable('now', wp_timezone());
		}

		private function day_string(DateTimeImmutable $date)
		{
			return $date->format('Y-m-d');
		}

		private function today()
		{
			return $this->day_string($this->today_date());
		}

		/**
		 * The preset periods in the filter bar. Every one ends today and fits
		 * inside the kept history.
		 */
		private function periods()
		{
			return [
				'7'  => ['label' => __('7D', 'info-cards'), 'days' => 7],
				'30' => ['label' => __('30D', 'info-cards'), 'days' => 30],
				'90' => ['label' => __('3M', 'info-cards'), 'days' => 90],
			];
		}

		/**
		 * The period being viewed, read from the request. Anything that is not
		 * one of the presets falls back to the last 30 days.
		 *
		 * @param array $source $_GET for the screen, $_POST for the export.
		 * @return array{key: string, start: DateTimeImmutable, end: DateTimeImmutable, days: int}
		 */
		private function current_window(array $source)
		{
			$today   = $this->today_date()->setTime(0, 0);
			$periods = $this->periods();
			$key     = isset($source['range']) && is_string($source['range']) ? sanitize_key(wp_unslash($source['range'])) : '30';

			if (!isset($periods[$key])) {
				$key = '30';
			}

			return [
				'key'   => (string) $key,
				'start' => $today->modify('-' . ($periods[$key]['days'] - 1) . ' days'),
				'end'   => $today,
				'days'  => $periods[$key]['days'],
			];
		}


		private function day_range(DateTimeImmutable $start, $days)
		{
			$keys = [];

			for ($i = 0; $i < $days; $i++) {
				$keys[] = $this->day_string($start->modify('+' . $i . ' days'));
			}

			return $keys;
		}

		private function sum(array $daily, array $days)
		{
			$total = 0;

			foreach ($days as $day) {
				$total += (int) ($daily[$day] ?? 0);
			}

			return $total;
		}

		private function delta($now, $before)
		{
			if (0 === $before) {
				return (0 === $now) ? null : 100.0;
			}

			return (($now - $before) / $before) * 100;
		}

		/* ------------------------------------------------------------ actions */

		public function export_csv()
		{
			if (!current_user_can('manage_options') || !check_admin_referer('infocards_export_analytics')) {
				wp_die(esc_html__('You are not allowed to do that.', 'info-cards'));
			}


			$period = $this->current_window($_POST);
			$range  = $period['days'];

			$rows = $this->rows_for_range($range, 'clicks', '', $period['start']);

			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename=ai-cards-analytics-' . $range . 'd-' . $this->today() . '.csv');

			$out = fopen('php://output', 'w');

			fputcsv($out, [
				'Card',
				sprintf('Clicks (last %d days)', $range),
				sprintf('Clicks (previous %d days)', $range),
				'Change %',
				'All-time clicks',
				'Last clicked',
				'Page',
			]);

			foreach ($rows as $row) {
				$change = $this->delta($row['range'], $row['prev']);

				fputcsv($out, [
					$row['title'],
					$row['range'],
					$row['prev'],
					null === $change ? '' : round($change),
					$row['total'],
					$row['last'],
					$row['source'],
				]);
			}

			fclose($out);
			exit;
		}


		private function rows_for_range($range, $sort = 'clicks', $search = '', $start = null)
		{
			// Without a start the range ends today, as it always has.
			$start     = $start instanceof DateTimeImmutable ? $start : $this->today_date()->modify('-' . ($range - 1) . ' days');
			$days      = $this->day_range($start, $range);
			$prev_days = $this->day_range($start->modify('-' . $range . ' days'), $range);

			$rows   = [];
			$search = trim((string) $search);

			foreach ($this->read(self::OPTION_DATA) as $entry) {
				$daily    = is_array($entry['daily'] ?? null) ? $entry['daily'] : [];
				$in_range = $this->sum($daily, $days);

				if (0 === $in_range) {
					continue;
				}

				$title = (string) ($entry['title'] ?? '');

				if ('' !== $search && false === mb_stripos($title, $search)) {
					continue;
				}

				$series = [];

				foreach ($days as $day) {
					$series[] = (int) ($daily[$day] ?? 0);
				}

				$rows[] = [
					'title'  => $title,
					'range'  => $in_range,
					'prev'   => $this->sum($daily, $prev_days),
					'total'  => (int) ($entry['count'] ?? 0),
					'last'   => (string) ($entry['last'] ?? ''),
					'source' => (string) ($entry['source'] ?? ''),
					'series' => $series,
				];
			}

			usort(
				$rows,
				static function ($a, $b) use ($sort) {
					switch ($sort) {
						case 'title':
							return strcasecmp($a['title'], $b['title']);
						case 'total':
							return $b['total'] <=> $a['total'];
						case 'last':
							return strcmp($b['last'], $a['last']);
						case 'clicks':
						default:
							// Ties broken by the all-time count, so equal weeks
							// do not reshuffle between page loads.
							return ($b['range'] <=> $a['range']) ?: ($b['total'] <=> $a['total']);
					}
				}
			);

			return $rows;
		}

		/**
		 * One card's own trend, as a tiny inline SVG.
		 *
		 * A shape rather than numbers: at this size the only readable question
		 * is "rising, falling or flat", and that is exactly the question the
		 * column is there to answer.
		 *
		 * @param int[] $series Clicks per day, oldest first.
		 * @return string
		 */
		public static function sparkline(array $series)
		{
			$count = count($series);

			if ($count < 2) {
				return '';
			}

			$width  = 88;
			$height = 22;
			$peak   = max(1, max($series));
			$step   = $width / ($count - 1);

			$points = [];

			foreach ($series as $index => $value) {
				$x = round($index * $step, 1);
				$y = round($height - (($value / $peak) * ($height - 2)) - 1, 1);

				$points[] = $x . ',' . $y;
			}

			$line = implode(' ', $points);

			return '<svg class="icbSpark" viewBox="0 0 ' . $width . ' ' . $height . '" width="' . $width . '" height="' . $height . '"'
				. ' fill="none" aria-hidden="true" focusable="false">'
				. '<polyline points="' . esc_attr($line) . '" stroke="currentColor" stroke-width="1.5"'
				. ' stroke-linecap="round" stroke-linejoin="round" />'
				. '</svg>';
		}

		public function clear()
		{
			if (!current_user_can('manage_options') || !check_admin_referer('infocards_clear_analytics')) {
				wp_die(esc_html__('You are not allowed to do that.', 'info-cards'));
			}

			delete_option(self::OPTION_DATA);
			delete_option(self::OPTION_DAILY);

			wp_safe_redirect(add_query_arg('cleared', '1', admin_url('admin.php?page=' . self::PAGE_SLUG)));
			exit;
		}

		public function save_retention()
		{
			if (!current_user_can('manage_options') || !check_admin_referer('infocards_save_retention')) {
				wp_die(esc_html__('You are not allowed to do that.', 'info-cards'));
			}

			$days = isset($_POST['retention']) ? absint($_POST['retention']) : 0;
			$days = min(self::HISTORY_DAYS, $days);

			update_option(self::OPTION_RETENTION, $days, false);

			$this->apply_retention();

			wp_safe_redirect(add_query_arg('saved', '1', admin_url('admin.php?page=' . self::PAGE_SLUG)));
			exit;
		}

		/**
		 * Drop day buckets older than the retention setting. Runs daily on
		 * cron, and immediately when the setting changes so the effect is
		 * visible rather than promised.
		 */
		public function apply_retention()
		{
			$days = (int) get_option(self::OPTION_RETENTION, 0);

			if ($days < 1) {
				return;
			}

			$cutoff  = $this->day_string($this->today_date()->modify('-' . ($days - 1) . ' days'));
			$entries = $this->read(self::OPTION_DATA);

			foreach ($entries as $key => $entry) {
				$daily = is_array($entry['daily'] ?? null) ? $entry['daily'] : [];

				foreach (array_keys($daily) as $day) {
					if ((string) $day < $cutoff) {
						unset($daily[$day]);
					}
				}

				$entries[$key]['daily'] = $daily;
			}

			$this->write(self::OPTION_DATA, $entries);

			$daily = $this->read(self::OPTION_DAILY);

			foreach (array_keys($daily) as $day) {
				if ((string) $day < $cutoff) {
					unset($daily[$day]);
				}
			}

			$this->write(self::OPTION_DAILY, $daily);
		}

		/* ------------------------------------------------------------- screen */

		public function render()
		{
			if (!current_user_can('manage_options')) {
				wp_die(esc_html__('You are not allowed to view this page.', 'info-cards'));
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only view state.
			$period  = $this->current_window($_GET);
			$periods = $this->periods();
			$range   = $period['days'];
			$start   = $period['start'];
			$end     = $period['end'];
			$days    = $this->day_range($start, $range);

			// Only the site wide series is needed here; the per card rows are
			// built by rows_for_range() below.
			$daily = $this->read(self::OPTION_DAILY);

			$total   = $this->sum($daily, $days);
			$busiest = 0;
			$series  = [];

			foreach ($days as $day) {
				$value    = (int) ($daily[$day] ?? 0);
				$series[] = ['day' => $day, 'value' => $value];

				if ($value > $busiest) {
					$busiest = $value;
				}
			}

			// Rank cards by clicks inside the range, not all time — otherwise
			// the table disagrees with the chart above it. Built by the same
			// method the CSV export uses.
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only view state.
			$sort = isset($_GET['sort']) ? sanitize_key(wp_unslash($_GET['sort'])) : 'clicks';
			$sort = in_array($sort, ['clicks', 'total', 'title', 'last'], true) ? $sort : 'clicks';

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only view state.
			$search = isset($_GET['s']) ? sanitize_text_field(wp_unslash($_GET['s'])) : '';

			$rows = $this->rows_for_range($range, $sort, $search, $start);

			// Everything a link on the screen has to carry to keep the view.
			$view_args = array_filter([
				'range' => $period['key'],
				'sort'  => $sort,
				's'     => $search,
			]);

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only paging.
			$paged  = isset($_GET['paged']) ? max(1, absint($_GET['paged'])) : 1;
			$pages  = max(1, (int) ceil(count($rows) / self::PER_PAGE));
			$paged  = min($paged, $pages);
			$window = array_slice($rows, ($paged - 1) * self::PER_PAGE, self::PER_PAGE);

			include ICB_DIR_PATH . 'includes/views/analytics.php';
		}

		/**
		 * A percentage change as a small coloured badge.
		 *
		 * @param float|null $change
		 * @return string
		 */
		public static function trend_badge($change)
		{
			if (null === $change) {
				return '<span class="icbTrend isFlat">' . esc_html__('no change', 'info-cards') . '</span>';
			}

			$rounded = (int) round($change);
			$class   = ($rounded > 0) ? 'isUp' : (($rounded < 0) ? 'isDown' : 'isFlat');
			$arrow   = ($rounded > 0) ? '▲' : (($rounded < 0) ? '▼' : '–');

			return '<span class="icbTrend ' . esc_attr($class) . '">' . $arrow . ' ' . esc_html(abs($rounded)) . '%</span>';
		}
	}
}
