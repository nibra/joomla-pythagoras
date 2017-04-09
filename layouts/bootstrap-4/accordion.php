<?php
/**
 * Part of the Joomla Framework Content Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 * @var \Joomla\Content\Type\Accordion $content
 * @codingStandardsIgnoreStart
 */

$id    = $content->getId();
$class = $content->getParameter('class', '');
?>
<div class="panel-group <?php echo $class; ?>" id="<?php echo $id; ?>">
	<?php foreach ($content->elements as $i => $element) : ?>
		<?php
		$title = $element->getTitle();
		$elemId = $id . '-' . $i;
		?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#<?php echo $id; ?>" href="#<?php echo $elemId; ?>">
						<?php echo $title; ?></a>
                </h4>
            </div>
            <div id="<?php echo $elemId; ?>" class="panel-collapse collapse<?php echo $i == 0 ? ' in' : ''; ?>">
                <div class="panel-body">
					<?php echo $element->html; ?>
                </div>
            </div>
        </div>
	<?php endforeach; ?>
</div>
