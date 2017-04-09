<?php
/**
 * Part of the Joomla Framework Content Package
 *
 * @copyright  Copyright (C) 2015 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 * @var \Joomla\Content\Type\Compound $content
 */

$tag   = $content->getType();
$id    = $content->getId();
$id = (isset($id)) ? " id=\"{$id}\"" : "";
$class = $content->getParameter('class');
$class = (isset($class)) ? " class=\"{$class}\"" : "";
$text  = var_export($content->elements, true);

echo "<{$tag}{$id}{$class}>{$text}</{$tag}>";
