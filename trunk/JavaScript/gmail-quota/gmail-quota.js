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
 * Gmail Quota
 * Author: Yu-Jie Lin
 * Creation Date: 2007-10-05T02:26:52+0800 
*/

var CP = [
    [1167638400000, 2800],
    [1175414400000, 2835],
    [1192176000000, 2912],
    [1193122800000, 4321],
    [1199433600000, 6283],
    [2147328000000, 43008],
    [46893711600000, Number.MAX_VALUE]];

var targetQuota = 3000.0;

$(function () {
    UpdateQuota();
    // Decide Predict quota
    var quota = parseInt($("#quota")[0].innerHTML);
    targetQuota = (Math.floor(quota / 1000) + 1) * 1000.0;
    $("#storage")[0].value = Number(targetQuota).toFixed(6);
    UpdateTimer();
    UpdateCountdown();
    UpdateRates();
    var pq = $("#preset-quota")[0];
    pq.innerHTML = "";
    for (var i=0; i<CP.length; i++) {
        var date = new Date(CP[i][0]);
        pq.innerHTML += CP[i][1] + " MB at " + date.toUTCString() + "<br/>";
        }
    });
    
function GetQuota() {
    var i;
    var now = (new Date()).getTime();
    for (i = 0; i < CP.length; i++) {
        if (now < CP[i][0]) {
            break;
            }
        }
    if (i == 0) {
        return false;
        }
    else if (i == CP.length) {
        return CP[i-1][1];
        }
    else {
        return ((now-CP[i-1][0]) / (CP[i][0]-CP[i-1][0]) * (CP[i][1]-CP[i-1][1])) + CP[i-1][1];
        } 
    }
function UpdateQuota() {
    var storage = GetQuota();
    if (storage != false)
        $("#quota")[0].innerHTML = Number(storage).toFixed(6);
    setTimeout(UpdateQuota, 1000);
    } 

function GetTime(storage) {
    for (i = 0; i < CP.length; i++) {
        if (storage < CP[i][1]) {
            break;
            }
        }
    if (i == 0) {
        alert("Unable to know! Please enter a number larger than 2800!");
        return; 
        }
    if (i == CP.length)
        i--;
    var time = ((storage - CP[i-1][1]) / (CP[i][1] - CP[i-1][1]) * (CP[i][0] - CP[i-1][0])) + CP[i-1][0];
    var date = new Date(time);
    return date;        
    }

function UpdateRates() {
    var r = $("#growth-rate")[0];
    var i;
    var now = (new Date()).getTime();
    for (i = 0; i < CP.length; i++) {
        if (now < CP[i][0]) {
            break;
            }
        }
    if (i == 0) {
        return; 
        }
    else if (i == CP.length) {
        return;
        }
    else {
        var rate = (CP[i][1] - CP[i - 1][1]) / (CP[i][0] - CP[i - 1][0]);
        rate *= Math.pow(10, 6 + 3); // To Bytes, To Second
        r.innerHTML = format(rate) + "B/sec<br/>";
        r.innerHTML += format(rate*60) + "B/min<br/>";
        r.innerHTML += format(rate*60*60) + "B/hr<br/>";
        r.innerHTML += format(rate*60*60*24) + "B/day<br/>";
        r.innerHTML += format(rate*60*60*24*365) + "B/year<br/>";
        }
    }

function UpdateCountdown() {
    // Doesn't count the leap days.
    var date = GetTime(targetQuota).getTime();
    var now = (new Date()).getTime();
    var diff = date - now;
    var past = false;
    if (diff < 0) {
        diff *= -1;
        past = true;
        }
    var cd = $("#countdown")[0];

    function s(num) {
        return (num == 1)?"":"s";
        }
    cd.innerHTML = ""
    var years = Math.floor(diff / (365*24*60*60*1000));
    diff -= years * 365*24*60*60*1000;
    if (years > 0)
        cd.innerHTML += years + " year" + s(years);
    var days = Math.floor(diff / (24*60*60*1000));
    diff -= days * 24*60*60*1000;
    cd.innerHTML += " " + days + " day" + s(days);
    var hours = Math.floor(diff / (60*60*1000));
    diff -= hours * 60*60*1000;
    cd.innerHTML += " " + hours + " hour" + s(hours);
    var minutes = Math.floor(diff / (60*1000));
    diff -= minutes * 60*1000;
    cd.innerHTML += " " + minutes + " minute" + s(minutes);
    var seconds = Math.floor(diff/1000);
    cd.innerHTML += " " + seconds + " second" + s(seconds);
    if (past)
        cd.innerHTML += " <em>ago</em>";
    setTimeout(UpdateCountdown, 1000);
    }

function UpdateTimer() {
    var newQuota = parseFloat($("#storage")[0].value);
    if (newQuota < 2800 || isNaN(newQuota)) {
        alert("Unable to know! Please enter a number larger than 2800!");
        return; 
        }
    targetQuota = parseFloat($("#storage")[0].value);
    $("#thetime")[0].innerHTML = Number(targetQuota).toFixed(6) + " MB at " + GetTime(targetQuota).toUTCString();
    }

function format(num) {
    var n = num.toExponential(6);
    var exp = /\d+.\d+e([-+]\d+)/.exec(n)[1];
    // http://en.wikipedia.org/wiki/SI_prefix
    var symbols = [
        [-15, 'f', 'femto'],
        [-12, 'p', 'pico'],
        [-9, 'n', 'nano'],
        [-6, '&#181;', 'micro'],
        [-3, 'm', 'milli'],
        [0, '', ''],
        [3, 'k', 'kilo'],
        [6, 'M', 'Mega'],
        [9, 'G', 'Giga'],
        [12, 'T', 'Tera'],
        [15, 'P', 'Peta'],
        [18, '', '']
        ];
    // Too small or too large?
    if (exp < symbols[0][0] || exp >= symbols[symbols.length - 1][0])
        return n;
    // Find the prefix or symbol
    for (var i=1; i<symbols.length; i++) {
        if (exp < symbols[i][0]) {
            exp = Math.floor(exp / 3.0) * 3;
            var newNum = num * Math.pow(10, -exp);
            return newNum.toFixed(6) + ' ' + symbols[i - 1][1];
            }
        }
    }
