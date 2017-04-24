<?php
/**
 * Part of the Joomla Framework Content Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 * @var \Joomla\Content\Type\OnePagerSection $content
 * @var \Joomla\Renderer\RendererInterface   $renderer
 * @codingStandardsIgnoreStart
 */

$tag            = $content->getType();
$id             = $content->getId();
$background     = $content->getParameter('background', 'default');
$alignment      = $content->getParameter('alignment', 'left');
$height         = $content->getParameter('height', 'auto');
$sectionClass   = " class=\"bg-{$background} text-{$alignment} {$height}-height vertical-center\"";
$containerClass = $content->getParameter('class');

$renderer->write(<<<HTML
<{$tag} id=\"{$id}\"{$sectionClass}>
    <div class=\"container $containerClass\">
HTML
);

foreach ($content->getChildren() as $child)
{
	$child->accept($renderer);
}

$renderer->write(<<<HTML
    </div>
<{$tag}>
HTML
);
