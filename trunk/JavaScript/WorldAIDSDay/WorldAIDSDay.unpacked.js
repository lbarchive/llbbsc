/*
 * Copyright 2007 Yu-Jie Lin
 * 
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 3 of the License, or (at your option)
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 * 
 * You should have received a copy of the GNU General Public License along
 * with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * World AIDS Day Countdown Widget
 *
 * Author: Yu-Jie Lin
 * Creation Date: 2007-11-13T06:40:53+0800
 */

var WAD;
var WADDate = null;
// Prevent from glitch
var WADTooltipRemovalTimer = null;

// Initializing
if (jQuery) {
	jQuery(function($) {
		WAD = $('.WAD');
		if (WAD) {
			WADInitialize();
			}
		});
	}

// Initialize WAD's content
function WADInitialize() {
	var $ = jQuery;
	if (!$) return;

	// Decide the coming WAD date
	var now = new Date();
	WADDate = new Date(now.getFullYear() + ((now.getMonth() == 11) ? 1 : 0), 11, 1, 0, 0, 0, 0);

	WAD.mouseup(function(e) {
		var button;	
		if (e.which == null) {
			/* IE case */
			button = ((e.button < 2) ? "LEFT" : ((e.button == 4) ? "MIDDLE" : "RIGHT"));
			}
		else {
			/* All others */
			button = ((e.which < 2) ? "LEFT" : ((e.which == 2) ? "MIDDLE" : "RIGHT"));
			}
		if (button == 'LEFT')
			window.open('http://www.worldvision.org/worldaidsday');
		else if (button == 'RIGHT')
			window.open('http://www.livibetter.com/blog/projects/wad/');
		});
	WAD.bind('contextmenu', function(e) {return false;});
	WAD.mousemove(WADMouseMove);
	WAD.mouseout(WADMouseOut);
	// Countdown timer
	WAD.append('<p class="WAD-CD"></p>');

	// Styles
	WAD.css({
		width: '160px',
		height: '100px',
		background: 'url(http://livibetter.googlepages.com/WAD.gif)',
		margin: 0,
		padding: 0,
		cursor: 'pointer'
		});

	$('.WAD-CD').css({
		margin: 0,
		padding: '0 10px',
		position: 'relative',
		left: '0px',
		top: '50px',
		fontFamily: 'Courier',
		fontSize: '12px',
		fontWeight: 'bold',
		color: 'Red',
		textAlign: 'right'
		});
	
	// Initialize Countdown timer
	WADCountdown();
	}

// Countdown Event
function WADCountdown() {
	var $ = jQuery;
	if (!$) return;

	var now = new Date();
	var WAD_CD = $('.WAD-CD');

	if (now.getMonth() == 11) {
		WAD_CD.html("<br/>Today!<br/>It's time to act!");
		return;
		}
	else {
		var d = DateDiff(now, WADDate);
		WAD_CD.html(d[1] + ' Day' + Pluralize(d[1], 's', '&nbsp;') + '<br/>' +
					d[2] + ' Hour' + Pluralize(d[2], 's', '&nbsp;') + '<br/>' +
					d[3] + ' Min' + Pluralize(d[3], 's', '&nbsp;') + '&nbsp;' +
					PaddingFront(d[4], '  ') + ' Sec' + Pluralize(d[4], 's', '&nbsp;'));
		}
	// Countdown timer
	setTimeout(WADCountdown, 1000);
	}

// Display Information
function WADMouseMove(e) {
	var $ = jQuery;
	if (!$) return;
	// Check tooltip
	if ($('#WAD-tooltip').length == 0) {
		$('<div id="WAD-tooltip">Click to see World AIDS Day at World Vision<br/><br/>Right click to read more about this widget</div>')
			.appendTo('body')
			.css({
				position: 'fixed',
				border: '2px solid Orange',
				backgroundColor: '#FFEC00',
				margin: 0,
				padding: '5px',
				fontSize: '12px',
				textAlign: 'center',
				opacity: 0.01,
				zIndex: 1000
				})
			.fadeTo('fast', 0.8);
		}
	if (WADTooltipRemovalTimer) {
		clearTimeout(WADTooltipRemovalTimer);
		WADTooltipRemovalTimer = null;
		}
	var tooltip = $('#WAD-tooltip');
	tooltip.css({
		left: e.clientX + 20,
		top: e.clientY + 20
		});
	}

function WADMouseOut() {
	WADTooltipRemovalTimer = setTimeout(WADRemoveTooltip, 100);
	}

function WADRemoveTooltip() {
	var $ = jQuery;
	if (!$) return;
	// Check tooltip
	var tooltip = $('#WAD-tooltip');
	if (tooltip.length == 0) return;
	tooltip.fadeOut('fast', function() {
		$('#WAD-tooltip').remove();
		});
	}
