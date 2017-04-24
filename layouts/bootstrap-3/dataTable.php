<?php
/**
 * Part of the Joomla Framework Content Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 * @var \Joomla\Content\Type\DataTable $content
 * @var \Joomla\Renderer\HtmlRenderer  $renderer
 * @codingStandardsIgnoreStart
 */

$id    = $content->getId();
$id    = empty($id) ? uniqid('table-') : $id;
$class = $content->getParameter('class');
$class = empty($class) ? "" : " class=\"{$class}\"";

/** @var \Joomla\ORM\Definition\Parser\Entity $meta */
$meta = $content->getParameter('entity');

echo "<div id=\"{$id}\"{$class}></div>";

$config = [
	'height'             => '50%',
	'fitColumns'         => true,
	#'responsiveLayout'   => true,
	'pagination'         => 'local',
	'paginationSize'     => 8,
	'movableCols'        => true,
	'persistentLayout'   => true,
	'persistentLayoutID' => $meta->name . "Persistence",
	'columns'            => [],
];

/**
 * @var string                              $key
 * @var \Joomla\ORM\Definition\Parser\Field $field
 */
foreach ($meta->fields as $key => $field)
{
	$column = [
		'title'        => $field->label,
		'field'        => $field->name,
	];

	switch ($field->type)
	{
		case 'id':
		case 'integer':
			$column['align']  = 'right';
			$column['sorter'] = 'number';
			break;
		case 'json':
			$column['formatter'] = 'textarea';
			break;
		case 'boolean':
			$column['formatter'] = 'tickCross';
			break;
		case 'string':
		case 'title':
		case 'url':
		case 'path':
			$column['headerFilter'] = true;
			break;
	}

	$config['columns'][] = $column;
}

$data = [];

foreach ($content->getChildren() as $row)
{
	$values = [];
	foreach (get_object_vars($row->item) as $name => $cell)
	{
		if (is_scalar($cell))
		{
			$values[$name] = $cell;
		}
		else
		{
			$values[$name] = "<pre>" . json_encode($cell, JSON_PRETTY_PRINT) . "</pre>";
		}
	}

	$data[] = $values;
}

$data = json_encode($data, JSON_PRETTY_PRINT);

#$renderer->addJavascript('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js');
$renderer->addRemoteJavascript('jquery-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js');
$renderer->addRemoteJavascript('tabulator', 'https://cdnjs.cloudflare.com/ajax/libs/tabulator/2.11.0/tabulator.min.js');
$renderer->addRemoteCss('tabulator', 'https://cdnjs.cloudflare.com/ajax/libs/tabulator/2.11.0/tabulator.min.css');

$var   = \Joomla\String\Normalise::toVariable($id) . 'Data';
$setup = json_encode($config, JSON_PRETTY_PRINT);
$js    = <<<JS

var {$var} = {$data};

$(document).ready(function () {
	$("#{$id}").tabulator({$setup});
	$("#{$id}").tabulator("setData", {$var});
	$("#{$id}").tabulator("setPage", 1);
});
JS;

$renderer->embedJavascript('.tabulator-' . $id, $js);
