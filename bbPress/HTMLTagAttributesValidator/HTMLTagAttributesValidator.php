<?php
/*
Plugin Name: HTML Tag Attributes Validator
Plugin URI: http://www.livibetter.com/it
Description: Validate HTML tag attributes
Author: Yu-Jie Lin
Author URI: http://www.livibetter.com/it
Version: 0.1
Creation Date: 2007-10-25T16:34:23+0800
*/
/*
 * Copyright 2007 Yu-Jie Lin
 * 
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or (at your
 * option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public
 * License for more details.
 * 
 * You should have received a copy of the GNU General Public License along
 * with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function SampleTags($tags) {
  $tags['img'] = array('src'   => array('/(jpg|jpeg|gif|png)$/i'),
                       'title' => array(),
                       'alt'   => array(),
                       'class' => array('/^(left|right)$/'));

  $tags['object'] = array('width'  => array(),
                          'height' => array(),
//                          'style'  => array(),
                          'class'  => array('/^(left|right)$/'));
  $tags['param'] = array('name'   => array(),
                         'value'  => array());
  $tags['embed'] = array('src'       => array('/^http:\/\/www.youtube.com\/v\/.*/',
                                              '/^http:\/\/video.google.com\/googleplayer.swf\?.*/'),
//                         'style'     => array(),
                         'type'      => array('/application\/x-shockwave-flash/'),
                         'id'        => array(),
                         'flashvars' => array(),
                         'wmode'     => array(),
                         'width'     => array(),
                         'height'    => array());
  return $tags;
  }

function FixObjectTag($text) {
  return preg_replace('/(<p><object.*?>)<br \/>\\n/', '$1', $text);
  }

function ValidateHTMLTagAttributes($text) {
  foreach (bb_allowed_tags() as $tag => $args) {
    if ('br' == $tag)
      continue;
    if ($args)
      foreach ($args as $arg => $limits)
        if ($limits) {
          // If set, then need to match one
          if (preg_match_all('/<(' . $tag . '.+?)' . $arg . '=("|\')(.+?)\\2(.*?)>/i', $text, $matches, PREG_SET_ORDER))
            foreach($matches as $match) {
              $i = 0;
              while ($i < count($limits)) {
                if (preg_match($limits[$i], $match[3]) > 0)
                  break;
                $i++;
                }
              // Not found, then remove this attribute
              if ($i == count($limits))
                $text = str_replace($match[0], "<$match[1]$match[4]>", $text);
              }
          }
    }
  return $text;
  }

add_filter('pre_post', 'ValidateHTMLTagAttributes', 51);
add_filter('pre_post', 'FixObjectTag', 61);
add_filter('bb_allowed_tags', 'SampleTags');
?>
