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

$id   = $content->getId();
$grid = $content->getParameter('grid', []);
$class = $content->getParameter('class', '');
?>
<div id="<?php echo $id; ?>" class="row container-fluid <?php echo $class; ?>">
	<?php foreach ($content->getChildren() as $i => $element) : ?>
		<div<?php echo isset($grid[$i]) ? " class=\"col-md-{$grid[$i]}\"" : ''; ?>>
			<?php echo $element->html; ?>
		</div>
	<?php endforeach; ?>
</div>
