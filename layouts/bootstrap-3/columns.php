<?php
/**
 * Part of the Joomla Framework Content Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 * @var \Joomla\Content\Type\Columns $content
 * @var \Joomla\Renderer\HtmlRenderer $renderer
 * @codingStandardsIgnoreStart
 */

$grid = $content->getParameter('grid', []);
?>
<div id="<?php echo $content->getId(); ?>" class="row container-fluid">
	<?php foreach ($content->getChildren() as $i => $element) : ?>
		<div<?php echo isset($grid[$i]) ? " class=\"col-md-{$grid[$i]}\"" : ''; ?>>
			<?php echo $element->html; ?>
		</div>
	<?php endforeach; ?>
</div>
