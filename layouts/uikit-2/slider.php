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

?>
<div id="<?php echo $content->id; ?>">

    <ul class="uk-slideshow" data-uk-slideshow="{autoplay:true}">
        <?php foreach ($content->elements as $i => $element) : ?>
            <li>
                <?php echo $element->html; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<script src="templates/uikit2/js/components/slideshow.min.js"></script>
