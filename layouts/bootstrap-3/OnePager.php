<?php
/**
 * Part of the Joomla Framework Content Package
 *
 * @copyright  Copyright (C) 2015 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Layout\Bootstrap3;

use Joomla\Renderer\LayoutInterface;
use Joomla\Renderer\RendererInterface;

class OnePager implements LayoutInterface
{
	/** @var  \Joomla\Content\Type\Compound */
	private $content;

	public function __construct(\Joomla\Content\Type\OnePager $content)
	{
		$this->content = $content;
	}

	/**
	 * Render the layout.
	 *
	 * @param RendererInterface $renderer
	 *
	 * @return int
	 */
	public function render(RendererInterface $renderer)
	{
		$content = $this->content;
		$id = $content->getId();
		$title = $content->getTitle();

		$len = $renderer->write(<<<HTML
<nav id="{$id}-nav" class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#{$id}-collapse">
                <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand page-scroll" href="#page-top">{$title}</a>
        </div>
        <div class="collapse navbar-collapse" id="{$id}-collapse">
            <ul class="nav navbar-nav navbar-right">
HTML
		);

		$children = $content->getChildren();
		array_shift($children);
		array_pop($children);

		foreach ($children as $i => $child)
		{
			$childId    = $child->getId();
			$childTitle = $child->getTitle();

			$len += $renderer->write("                <li><a class=\"page-scroll\" href=\"#{$childId}\">{$childTitle}</a></li>\n");
		}

		$len += $renderer->write(<<<HTML
            </ul>
        </div>
    </div>
</nav>
HTML
		);

		foreach ($content->getChildren() as $child)
		{
			$len += $child->accept($renderer);
		}

		$renderer->embedJavascript('.navbar', <<<JS
(function($) {
	"use strict";
	$(document).ready(function() {
		// jQuery for page scrolling feature - requires jQuery Easing plugin
		$('a.page-scroll').bind('click', function(event) {
			var anchor = $(this);
			$('html, body').stop().animate({
				scrollTop: ($(anchor.attr('href')).offset().top - 50)
			}, 1250, 'easeInOutExpo');
			event.preventDefault();
		});

		// Highlight the top nav as scrolling occurs
		$('body').scrollspy({
			target: '.navbar-fixed-top',
			offset: 100
		});

		// Closes the Responsive Menu on Menu Item Click
		$('.navbar-collapse ul li a').click(function() {
			$('.navbar-toggle:visible').click();
		});

		// Offset for Main Navigation
		$('#mainNav').affix({
			offset: {
				top: 50
			}
		})
	});

})(jQuery);

JS
		);

		return $len;
	}
}
