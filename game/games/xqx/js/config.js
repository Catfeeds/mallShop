﻿function getrd(n, m) {
    var c = m - n + 1;
    return Math.floor(Math.random() * c + n);
}

//var ciarr = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
//var securl = "";

//for (var ai = 0; ai < getrd(3, 4) ; ai++) { //一位码
//    securl += ciarr[getrd(26, ciarr.length - 1)];
//}

//if (getrd(1, 10000) > 5000) {
//    securl += ".";
//    for (var ai = 0; ai < getrd(3, 4) ; ai++) {//二位码
//        securl += ciarr[getrd(0, 25)];
//    }
//}
//securl += ".";
//for (var ai = 0; ai < getrd(3, 9) ; ai++) {//三位码
//    securl += ciarr[getrd(0, ciarr.length-1)];
//}


var urlarr = [ "http://157.10.1.2/demo/game", "http://157.10.1.2/demo/game"]

var siteurl = "http://157.10.1.2/demo/game";
var gotopage = "/gane/index.html";



//location.href = siteurl + window.location.pathname;


function home() {
    location.href = siteurl;
}

function home_test() {
    location.href = siteurl;
    //location.href = "http://mp.weixin.qq.com/s?__biz=MjM5OTM4NDMyNQ==&mid=200401266&idx=1&sn=ce91b8b97c61a65d30c2bfd1c87a6fc5#rd";
}

function gzh() {
    location.href = siteurl + "/index.html";
}

function homeurl(url) {
    location.href = siteurl + "/" + url;
}