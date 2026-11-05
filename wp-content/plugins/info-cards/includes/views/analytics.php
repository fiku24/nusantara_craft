<?php
/**
 * Info Cards — the Analytics screen.
 *
 * The chart is inline SVG built here rather than a charting library: it is a
 * bar per day, it never animates, and shipping a hundred kilobytes of
 * JavaScript to draw thirty rectangles would be the heaviest thing in the
 * plugin.
 *
 * @var array               $series   [{day, value}], oldest first.
 * @var array               $window   The page of card rows being shown.
 * @var array               $rows     Every card row in range.
 * @var int                 $total    Clicks in range.
 * @var int                 $busiest  Highest single day in range.
 * @var int                 $range    Range length in days.
 * @var int                 $paged
 * @var int                 $pages
 * @var DateTimeImmutable   $start
 * @var DateTimeImmutable   $end
 *
 * @package Info_Cards
 */

if (!defined('ABSPATH')) {
	exit;
}

$base_url  = admin_url('admin.php?page=' . InfoCards_Analytics::PAGE_SLUG);
$retention = (int) get_option(InfoCards_Analytics::OPTION_RETENTION, 0);
?>
<div class="wrap icbWrap">
	<div class="icbHeader">
		<div>
			<h1><?php esc_html_e('Info Cards Analytics', 'info-cards'); ?></h1>
			<p class="icbSubtitle">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
				<?php
				printf(
					/* translators: 1: start date, 2: end date. */
					esc_html__('%1$s to %2$s', 'info-cards'),
					esc_html(wp_date(get_option('date_format'), $start->getTimestamp())),
					esc_html(wp_date(get_option('date_format'), $end->getTimestamp()))
				);
				?>
			</p>
		</div>
	</div>

	<?php
	// Changing the period keeps the sort and the search but drops the
	// page — page four of the last 7 days rarely exists.
	$keep_args = array_diff_key($view_args, ['range' => 1]);
	?>
	<div class="icbFilterBar">
		<div class="icbSeg" role="group" aria-label="<?php esc_attr_e('Date range', 'info-cards'); ?>">
			<?php // Pro-only presets: static links to the pricing tab, no logic behind them. ?>
			<a class="icbSegItem isPro" href="<?php echo esc_url(admin_url('admin.php?page=info-cards-dashboard#/pricing')); ?>" title="<?php esc_attr_e('Available in Pro', 'info-cards'); ?>" target="_blank" rel="noopener noreferrer">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
				<?php esc_html_e('Custom', 'info-cards'); ?>
				<span class="icbProBadge"><?php esc_html_e('Pro', 'info-cards'); ?></span>
			</a>
			<a class="icbSegItem isPro" href="<?php echo esc_url(admin_url('admin.php?page=info-cards-dashboard#/pricing')); ?>" title="<?php esc_attr_e('Available in Pro', 'info-cards'); ?>" target="_blank" rel="noopener noreferrer">
				<?php esc_html_e('Today', 'info-cards'); ?>
				<span class="icbProBadge"><?php esc_html_e('Pro', 'info-cards'); ?></span>
			</a>
			<a class="icbSegItem isPro" href="<?php echo esc_url(admin_url('admin.php?page=info-cards-dashboard#/pricing')); ?>" title="<?php esc_attr_e('Available in Pro', 'info-cards'); ?>" target="_blank" rel="noopener noreferrer">
				<?php esc_html_e('Yesterday', 'info-cards'); ?>
				<span class="icbProBadge"><?php esc_html_e('Pro', 'info-cards'); ?></span>
			</a>

			<?php foreach ($periods as $key => $preset) : ?>
				<a class="icbSegItem<?php echo (string) $key === $period['key'] ? ' isActive' : ''; ?>"
					href="<?php echo esc_url(add_query_arg(array_merge(['range' => (string) $key], $keep_args), $base_url)); ?>"
					<?php echo (string) $key === $period['key'] ? 'aria-current="true"' : ''; ?>>
					<?php echo esc_html($preset['label']); ?>
				</a>
			<?php endforeach; ?>

			<a class="icbSegItem isPro" href="<?php echo esc_url(admin_url('admin.php?page=info-cards-dashboard#/pricing')); ?>" title="<?php esc_attr_e('Available in Pro', 'info-cards'); ?>" target="_blank" rel="noopener noreferrer">
				<?php esc_html_e('6M', 'info-cards'); ?>
				<span class="icbProBadge"><?php esc_html_e('Pro', 'info-cards'); ?></span>
			</a>
		</div>

		<div class="icbProCompare">
			<span class="icbProCompareLabel"><?php esc_html_e('compared to', 'info-cards'); ?></span>
			<a class="icbProCompareBox isPro" href="<?php echo esc_url(admin_url('admin.php?page=info-cards-dashboard#/pricing')); ?>" title="<?php esc_attr_e('Available in Pro', 'info-cards'); ?>" target="_blank" rel="noopener noreferrer">
				<?php esc_html_e('Previous 6 months', 'info-cards'); ?>
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
				<span class="icbProBadge"><?php esc_html_e('Pro', 'info-cards'); ?></span>
			</a>
		</div>
	</div>

	<?php // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only notice flags. ?>
	<?php if (isset($_GET['cleared'])) : ?>
		<div class="notice notice-success is-dismissible icbNotice"><p><?php esc_html_e('Analytics cleared.', 'info-cards'); ?></p></div>
	<?php endif; ?>
	<?php // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
	<?php if (isset($_GET['saved'])) : ?>
		<div class="notice notice-success is-dismissible icbNotice"><p><?php esc_html_e('Retention saved.', 'info-cards'); ?></p></div>
	<?php endif; ?>

	<?php // Pro-only stat cards: static sample figures, blurred, under a lock that links to the pricing tab. No logic behind them. ?>
	<a class="icbProLock" href="<?php echo esc_url(admin_url('admin.php?page=info-cards-dashboard#/pricing')); ?>" target="_blank" rel="noopener noreferrer">
		<div class="icbCards" aria-hidden="true">
			<div class="icbCard">
				<div class="icbCardHeader">
					<span class="icbCardLabel"><?php esc_html_e('Clicks', 'info-cards'); ?></span>
					<div class="icbCardIcon">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5"/></svg>
					</div>
				</div>
				<strong class="icbCardValue">1,383</strong>
				<div class="icbCardFooter">
					<span class="icbTrend isUp">▲ 24%</span>
				</div>
			</div>

			<div class="icbCard">
				<div class="icbCardHeader">
					<span class="icbCardLabel"><?php esc_html_e('Cards clicked', 'info-cards'); ?></span>
					<div class="icbCardIcon">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
					</div>
				</div>
				<strong class="icbCardValue">8</strong>
				<span class="icbCardNote"><?php esc_html_e('distinct cards in this range', 'info-cards'); ?></span>
			</div>

			<div class="icbCard">
				<div class="icbCardHeader">
					<span class="icbCardLabel"><?php esc_html_e('Busiest day', 'info-cards'); ?></span>
					<div class="icbCardIcon">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
					</div>
				</div>
				<strong class="icbCardValue">25</strong>
				<span class="icbCardNote"><?php esc_html_e('clicks in a single day', 'info-cards'); ?></span>
			</div>

			<div class="icbCard">
				<div class="icbCardHeader">
					<span class="icbCardLabel"><?php esc_html_e('Daily average', 'info-cards'); ?></span>
					<div class="icbCardIcon">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
					</div>
				</div>
				<strong class="icbCardValue">46</strong>
				<span class="icbCardNote"><?php esc_html_e('across the range', 'info-cards'); ?></span>
			</div>
		</div>

		<div class="icbProLockBox">
			<span class="icbProLockIcon">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
			</span>
			<p>
				<strong><?php esc_html_e('Click summary is available in Info Cards Pro.', 'info-cards'); ?></strong>
				<?php esc_html_e('See total clicks, cards clicked, your busiest day and daily average at a glance.', 'info-cards'); ?>
			</p>
			<span class="icbProLockCta">
				<?php esc_html_e('Upgrade to Pro', 'info-cards'); ?>
				<span class="icbProBadge"><?php esc_html_e('Pro', 'info-cards'); ?></span>
			</span>
		</div>
	</a>

	<div class="icbPanel">
		<div class="icbPanelTitleRow">
			<div>
				<h2><?php esc_html_e('Clicks per day', 'info-cards'); ?></h2>
				<?php if (0 !== $total) : ?>
					<p class="icbChartSub">
						<?php
						printf(
							/* translators: %s: number of clicks in the selected range. */
							esc_html(_n('%s click recorded', '%s clicks recorded', $total, 'info-cards')),
							'<strong>' . esc_html(number_format_i18n($total)) . '</strong>'
						);
						?>
					</p>
				<?php endif; ?>
			</div>
		</div>

		<?php if (0 === $total) : ?>
			<div class="icbEmpty">
				<svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
				<p>
					<?php
					// A quiet week is not the same as a site with no clicks at all.
					echo array_sum(array_map('intval', $daily)) > 0
						? esc_html__('No clicks in this period. Try a longer range.', 'info-cards')
						: esc_html__('No clicks recorded yet. Add an Info Cards block to a page, give its cards real links, and the clicks will show up here.', 'info-cards');
					?>
				</p>
			</div>
		<?php else : ?>
			<?php
			/*
			 * A line chart. The line and gridlines are an SVG stretched to the
			 * plot (non-scaling strokes keep them crisp); the labels, the end
			 * dot and the hover points are HTML placed by percentage, so text
			 * never stretches with the plot.
			 */
			$count = count($series);
			$last  = max(1, $count - 1);

			// Gridlines on a round step so the axis reads 0, 4, 8, 12 rather
			// than 0, 3.3, 6.7, 10.
			$raw  = max(1, $busiest) / 3;
			$mag  = $raw >= 1 ? pow(10, floor(log10($raw))) : 1;
			$step = 10 * $mag;
			foreach ([1, 1.5, 2, 2.5, 4, 5, 7.5, 10] as $multiple) {
				$candidate = $multiple * $mag;
				if ($candidate >= $raw && floor($candidate) == $candidate) {
					$step = $candidate;
					break;
				}
			}
			$step    = max(1, (int) $step);
			$ceiling = $step * 3;

			$points = [];
			$line   = [];
			foreach ($series as $index => $point) {
				$x = $count > 1 ? ($index / $last) * 100 : 50;
				$y = 100 - (($point['value'] / $ceiling) * 100);

				$points[] = [
					'x'     => $x,
					'y'     => $y,
					'value' => (int) $point['value'],
					'label' => wp_date('M j', $start->modify('+' . $index . ' days')->getTimestamp()),
				];
				$line[] = round($x * 10, 2) . ',' . round($y * 2, 2);
			}

			// Around seven date labels, always ending on the last day shown.
			$every = max(1, (int) ceil($last / 6));
			$ticks = $count > 1 ? range(0, $last, $every) : [0];
			if ($count > 1 && end($ticks) !== $last) {
				if (($last - end($ticks)) < ($every / 2)) {
					array_pop($ticks);
				}
				$ticks[] = $last;
			}

			$end_point = end($points);
			?>
			<div class="icbLineChart<?php echo 1 === $count ? ' isSingle' : ''; ?>" role="img" aria-label="<?php esc_attr_e('Clicks per day', 'info-cards'); ?>">
				<div class="icbLineYAxis" aria-hidden="true">
					<?php for ($tick = 0; $tick <= 3; $tick++) : ?>
						<span style="bottom: <?php echo esc_attr(round(($tick / 3) * 100, 4)); ?>%;"><?php echo esc_html(number_format_i18n($tick * $step)); ?></span>
					<?php endfor; ?>
				</div>

				<div class="icbLinePlot">
					<svg class="icbLineSvg" viewBox="0 0 1000 200" preserveAspectRatio="none" aria-hidden="true" focusable="false">
						<?php for ($tick = 0; $tick <= 3; $tick++) : ?>
							<?php $grid_y = round(200 - (($tick / 3) * 200), 2); ?>
							<line class="icbLineGrid" x1="0" y1="<?php echo esc_attr($grid_y); ?>" x2="1000" y2="<?php echo esc_attr($grid_y); ?>" vector-effect="non-scaling-stroke" />
						<?php endfor; ?>
						<polyline class="icbLinePath" points="<?php echo esc_attr(implode(' ', $line)); ?>" vector-effect="non-scaling-stroke" />
					</svg>

					<span class="icbLineEndDot" aria-hidden="true" style="left: <?php echo esc_attr(round($end_point['x'], 4)); ?>%; top: <?php echo esc_attr(round($end_point['y'], 4)); ?>%;"></span>

					<?php foreach ($points as $point) : ?>
						<div class="icbLinePoint" tabindex="0" style="left: <?php echo esc_attr(round($point['x'], 4)); ?>%; width: <?php echo esc_attr(round(100 / ($count > 1 ? $last : 1), 4)); ?>%;">
							<span class="icbLineDot" style="top: <?php echo esc_attr(round($point['y'], 4)); ?>%;"></span>
							<span class="icbLineTip" style="top: <?php echo esc_attr(round($point['y'], 4)); ?>%;">
								<span class="icbLineTipDate"><?php echo esc_html($point['label']); ?></span>
								<strong>
									<?php
									/* translators: %s: number of clicks. */
									echo esc_html(sprintf(_n('%s click', '%s clicks', $point['value'], 'info-cards'), number_format_i18n($point['value'])));
									?>
								</strong>
							</span>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="icbLineXAxis" aria-hidden="true">
					<?php foreach ($ticks as $position => $tick) : ?>
						<span class="<?php echo ($position % 2 && $tick !== $last) ? 'isAlt' : ''; ?>" style="left: <?php echo esc_attr(round($points[$tick]['x'], 4)); ?>%;"><?php echo esc_html($points[$tick]['label']); ?></span>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<div class="icbPanel">
		<div class="icbPanelHead">
			<h2><?php esc_html_e('Most clicked cards', 'info-cards'); ?></h2>

			<div class="icbPanelTools">
				<form method="get" class="icbSearch">
					<input type="hidden" name="page" value="<?php echo esc_attr(InfoCards_Analytics::PAGE_SLUG); ?>">
					<?php foreach (array_diff_key($view_args, ['s' => 1]) as $name => $value) : ?>
						<input type="hidden" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>">
					<?php endforeach; ?>

					<div class="icbSearchInputWrap">
						<svg class="icbSearchIcon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
						<label class="screen-reader-text" for="icbSearchInput"><?php esc_html_e('Search cards', 'info-cards'); ?></label>
						<input type="search" id="icbSearchInput" name="s" value="<?php echo esc_attr($search); ?>"
							placeholder="<?php esc_attr_e('Search cards…', 'info-cards'); ?>">
					</div>

					<button type="submit" class="button icbBtn icbBtnSecondary"><?php esc_html_e('Search', 'info-cards'); ?></button>

					<?php if ('' !== $search) : ?>
						<a class="button button-link icbBtnClear" href="<?php echo esc_url(add_query_arg(array_diff_key($view_args, ['s' => 1]), $base_url)); ?>">
							<?php esc_html_e('Clear', 'info-cards'); ?>
						</a>
					<?php endif; ?>
				</form>

				<?php if ($rows) : ?>
					<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
						<?php wp_nonce_field('infocards_export_analytics'); ?>
						<input type="hidden" name="action" value="infocards_export_analytics">
						<?php // Carried through so the export describes the range on screen. ?>
						<input type="hidden" name="range" value="<?php echo esc_attr($period['key']); ?>">
						<button type="submit" class="button icbBtn icbBtnPrimary">
							<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
							<?php esc_html_e('Export CSV', 'info-cards'); ?>
						</button>
					</form>
				<?php endif; ?>
			</div>
		</div>

		<?php if (!$window) : ?>
			<div class="icbEmpty">
				<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
				<p>
					<?php
					echo '' !== $search
						? esc_html__('No cards match that search in this range.', 'info-cards')
						: esc_html__('Nothing clicked in this range.', 'info-cards');
					?>
				</p>
			</div>
		<?php else : ?>
			<?php
			/**
			 * A sortable column header. Sorting is a link rather than a script:
			 * it survives a reload, it can be bookmarked, and it works before
			 * any JavaScript has run.
			 */
			$column = static function ($key, $label, $numeric = false) use ($base_url, $view_args, $sort) {
				$url = add_query_arg(array_merge($view_args, ['sort' => $key]), $base_url);

				printf(
					'<th%1$s><a class="icbSort%2$s" href="%3$s">%4$s<span class="icbSortMark" aria-hidden="true">%5$s</span></a></th>',
					$numeric ? ' class="icbNum"' : '',
					$sort === $key ? ' isActive' : '',
					esc_url($url),
					esc_html($label),
					$sort === $key ? '▾' : ''
				);
			};
			?>
			<div class="icbTableWrap">
				<table class="widefat striped icbTable">
					<thead>
						<tr>
							<?php $column('title', __('Card', 'info-cards')); ?>
							<th>
								<?php
								printf(
									/* translators: %d: number of days in the selected range. */
									esc_html__('Last %d days', 'info-cards'),
									esc_html($range)
								);
								?>
							</th>
							<?php $column('clicks', __('In range', 'info-cards'), true); ?>
							<th class="icbNum"><?php esc_html_e('Trend', 'info-cards'); ?></th>
							<?php $column('total', __('All time', 'info-cards'), true); ?>
							<?php $column('last', __('Last clicked', 'info-cards')); ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($window as $row) : ?>
							<tr>
								<td class="icbCellCard">
									<strong class="icbCardTitle"><?php echo esc_html($row['title']); ?></strong>
									<?php if ($row['source']) : ?>
										<div class="icbRowSource">
											<a href="<?php echo esc_url($row['source']); ?>" target="_blank" rel="noopener noreferrer">
												<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
												<?php echo esc_html(wp_parse_url($row['source'], PHP_URL_PATH) ?: $row['source']); ?>
											</a>
										</div>
									<?php endif; ?>
								</td>
								<td class="icbSparkCell">
									<?php echo InfoCards_Analytics::sparkline($row['series']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from esc_attr()'d numbers. ?>
								</td>
								<td class="icbNum icbValHighlight"><?php echo esc_html(number_format_i18n($row['range'])); ?></td>
								<td class="icbNum"><?php echo InfoCards_Analytics::trend_badge($this->delta($row['range'], $row['prev'])); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
								<td class="icbNum"><?php echo esc_html(number_format_i18n($row['total'])); ?></td>
								<td class="icbMuted"><?php echo $row['last'] ? esc_html($row['last']) : '&mdash;'; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<?php if ($pages > 1) : ?>
				<div class="icbPager">
					<?php for ($page = 1; $page <= $pages; $page++) : ?>
						<?php
						// The sort and the search have to survive paging, or
						// page two quietly answers a different question.
						$page_url = add_query_arg(array_merge($view_args, ['paged' => $page]), $base_url);
						?>
						<a class="icbPage<?php echo $page === $paged ? ' isActive' : ''; ?>" href="<?php echo esc_url($page_url); ?>">
							<?php echo esc_html($page); ?>
						</a>
					<?php endfor; ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>

	<div class="icbPanel icbPanelSplit">
		<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="icbRetention">
			<?php wp_nonce_field('infocards_save_retention'); ?>
			<input type="hidden" name="action" value="infocards_save_retention">

			<label for="icbRetention">
				<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
				<?php esc_html_e('Keep history for', 'info-cards'); ?>
			</label>

			<select name="retention" id="icbRetention">
				<option value="0" <?php selected($retention, 0); ?>><?php esc_html_e('As long as possible (90 days)', 'info-cards'); ?></option>
				<option value="7" <?php selected($retention, 7); ?>><?php esc_html_e('7 days', 'info-cards'); ?></option>
				<option value="30" <?php selected($retention, 30); ?>><?php esc_html_e('30 days', 'info-cards'); ?></option>
				<option value="60" <?php selected($retention, 60); ?>><?php esc_html_e('60 days', 'info-cards'); ?></option>
			</select>

			<button type="submit" class="button icbBtn icbBtnSecondary"><?php esc_html_e('Save', 'info-cards'); ?></button>
		</form>

		<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
			onsubmit="return confirm('<?php echo esc_js(__('Delete all recorded clicks? This cannot be undone.', 'info-cards')); ?>');">
			<?php wp_nonce_field('infocards_clear_analytics'); ?>
			<input type="hidden" name="action" value="infocards_clear_analytics">
			<button type="submit" class="button button-link-delete icbBtnClearAnalytics">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
				<?php esc_html_e('Clear analytics', 'info-cards'); ?>
			</button>
		</form>
	</div>
</div>
