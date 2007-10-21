<?php
/*
Plugin Name: On this day Plugin
Plugin URI:  http://code.google.com/p/llbbsc/wiki/OnThisDayPlugin
Description: This is a simple plugin for creating a new View for listing topics started a year or years ago on the same calendar date.
Author: Yu-Jie Lin
Author URI: http://www.livibetter.com/
Version: 0.1
Creation Date: 2007-10-19 09:11:33 UTC+8
*/

function OTDHook_bb_view_otd_where($where) {
    $key = 'YEAR(t.topic_start_time) ';
    return str_replace("$key=", "$key<", $where);
    }

/* Hooks
======================================== */

add_filter('bb_view_otd_where', 'OTDHook_bb_view_otd_where');

/* Views
======================================== */

bb_register_view('otd', __('On this day...'), array('started' => date('Y-m-d')));

?>
