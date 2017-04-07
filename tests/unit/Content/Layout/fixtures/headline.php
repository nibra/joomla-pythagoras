<?php
/**
 * Part of the Joomla Framework Content Package Test Suite
 *
 * @copyright  Copyright (C) 2015 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 * @var \Joomla\Content\Type\Headline $content
 * @codingStandardsIgnoreStart
 */

$level = $content->level;
$id    = $content->getId();
$class = (isset($content->getParameters()->class)) ? " class=\"{$content->getParameters()->class}\"" : "";
$text  = $content->text;

echo "<h{$level} id=\"{$id}\"{$class}>{$text}</h{$level}>";
