<?php
/**
 * Part of the Joomla Framework Content Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 * @var \Joomla\Content\Type\DataTable     $content
 * @var \Joomla\Renderer\HtmlRenderer $renderer
 * @codingStandardsIgnoreStart
 */

$id    = $content->getId();
$id    = empty($id) ? uniqid('table-') : $id;
$class = $content->getParameter('class');
$class = empty($class) ? "" : " class=\"{$class}\"";

/** @var \Joomla\ORM\Definition\Parser\Entity $meta */
$meta = $content->getParameter('entity');

echo "<table id=\"{$id}\"{$class}>";
echo "<thead>";
echo "<tr>";

foreach ($meta->fields as $field)
{
	echo "<th>";
	echo $content->getParameter('label', true) ? $field->label : $field->name;
	echo "</th>";
}

echo "</tr>";
echo "</thead>";
echo "<tbody>";

foreach ($content->getChildren() as $row)
{
	echo "<tr>";

	foreach (get_object_vars($row->item) as $cell)
	{
		echo "<td>";

		if (is_scalar($cell))
		{
			echo $cell;
		}
		else
		{
			echo "<pre>";
			echo json_encode($cell, JSON_PRETTY_PRINT);
			echo "</pre>";
		}

		echo "</td>";
	}

	echo "</tr>";
}

echo "</tbody>";
echo "</table>";
