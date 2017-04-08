<?php
/**
 * Part of the Joomla Framework Content Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 * @var \Joomla\Content\Type\Paragraph $content
 * @codingStandardsIgnoreStart
 */

$id    = $content->getId();
$class = $content->getParameter('class');
$class = (!empty($class)) ? " class=\"{$class}\"" : "";
$text  = $content->text;

if ($content->variant == Joomla\Content\Type\Paragraph::EMPHASISED)
{
	echo "<p id=\"{$id}\"{$class}><em>{$text}</em></p>";
}
else
{
	echo "<p id=\"{$id}\"{$class}>{$text}</p>";
}
