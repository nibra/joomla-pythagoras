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

		#if (is_object($cell))
		#{
		#	echo "object " . get_class($cell);
		#}
		#elseif (is_array($cell))
		#{
		#	echo "array (" . count($cell) . ")";
		#}
		#else
		#{
		#	echo $cell;
		#}

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

#$renderer->addJavascript('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js');
$renderer->addJavascript('jquery-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js');
$renderer->addJavascript('tabulator', 'https://cdnjs.cloudflare.com/ajax/libs/tabulator/2.11.0/tabulator.min.js');
$renderer->addCss('tabulator', 'https://cdnjs.cloudflare.com/ajax/libs/tabulator/2.11.0/tabulator.min.css');

$js = <<<JS
$(document).ready(function () {
	$("#{$id}").tabulator({});
});
JS;

$renderer->embedJavascript('.tabulator-' . $id, $js);
