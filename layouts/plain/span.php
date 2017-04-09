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
$id    = empty($id) ? "" : " id=\"{$id}\"";
$class = $content->getParameter('class');
$class = empty($class) ? "" : " class=\"{$class}\"";
$text  = $content->text;

echo "<span{$id}{$class}>{$text}</span>";
