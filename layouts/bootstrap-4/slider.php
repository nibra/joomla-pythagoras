<?php
/**
 * Part of the Joomla Framework Content Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 * @var \Joomla\Content\Type\Slider $content
 *
 * @codingStandardsIgnoreStart
 */

if (!isset($content->params))
{
	$content->params = new stdClass;
}

if (!isset($content->params->class))
{
	$content->params->class = 'slide';
}
?>
<div id="<?php echo $content->id; ?>" class="carousel <?php echo $content->params->class; ?>" data-ride="carousel">
	<!-- Indicators -->
	<ol class="carousel-indicators">
		<?php for ($i=0, $n = count($content->elements); $i < $n; ++$i) : ?>
		<li data-target="#<?php echo $content->id; ?>" data-slide-to="<?php echo $i; ?>"<?php echo $i == 0 ? ' class="active"' : '';?>></li>
		<?php endfor; ?>
	</ol>

	<!-- Wrapper for slides -->
	<div class="carousel-inner" role="listbox">
		<?php foreach ($content->elements as $i => $element) : ?>
		<div class="item<?php echo $i == 0 ? ' active' : ''; ?>">
			<?php echo $element->html; ?>
		</div>
		<?php endforeach; ?>
	</div>

	<!-- Left and right controls -->
	<a class="left carousel-control" href="#<?php echo $content->id; ?>" role="button" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control" href="#<?php echo $content->id; ?>" role="button" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
</div>
