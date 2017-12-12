function getWxShareData() {
    return {
        img_url: wxShareImg,
        imgUrl: wxShareImg,
        title: "都教授走前，留下了一个秘密",
        desc: "嘘...快来看留给你的线索！",
        link: wxShareUrl
    }
}
function getWxShareMomentData() {
    return {
        img_url: wxShareImg,
        imgUrl: wxShareImg,
        title: "都教授走前，留下了一个秘密",
        desc: "嘘...快来看留给你的线索！",
        link: wxShareUrl
    }
} !
    function(t) {
        t.Callbacks = function(e) {
            e = t.extend({},
                e);
            var o, n, i, a, s, r, l = [],
                d = !e.once && [],
                u = function(t) {
                    for (o = e.memory && t, n = !0, r = a || 0, a = 0, s = l.length, i = !0; l && s > r; ++r) if (l[r].apply(t[0], t[1]) === !1 && e.stopOnFalse) {
                        o = !1;
                        break
                    }
                    i = !1,
                        l && (d ? d.length && u(d.shift()) : o ? l.length = 0 : h.disable())
                },
                h = {
                    add: function() {
                        if (l) {
                            var n = l.length,
                                r = function(o) {
                                    t.each(o,
                                        function(t, o) {
                                            "function" == typeof o ? e.unique && h.has(o) || l.push(o) : o && o.length && "string" != typeof o && r(o)
                                        })
                                };
                            r(arguments),
                                i ? s = l.length: o && (a = n, u(o))
                        }
                        return this
                    },
                    remove: function() {
                        return l && t.each(arguments,
                            function(e, o) {
                                for (var n; (n = t.inArray(o, l, n)) > -1;) l.splice(n, 1),
                                    i && (s >= n && --s, r >= n && --r)
                            }),
                            this
                    },
                    has: function(e) {
                        return ! (!l || !(e ? t.inArray(e, l) > -1 : l.length))
                    },
                    empty: function() {
                        return s = l.length = 0,
                            this
                    },
                    disable: function() {
                        return l = d = o = void 0,
                            this
                    },
                    disabled: function() {
                        return ! l
                    },
                    lock: function() {
                        return d = void 0,
                            o || h.disable(),
                            this
                    },
                    locked: function() {
                        return ! d
                    },
                    fireWith: function(t, e) {
                        return ! l || n && !d || (e = e || [], e = [t, e.slice ? e.slice() : e], i ? d.push(e) : u(e)),
                            this
                    },
                    fire: function() {
                        return h.fireWith(this, arguments)
                    },
                    fired: function() {
                        return !! n
                    }
                };
            return h
        }
    } (Zepto),
    function(t) {
        function e(o) {
            var n = [["resolve", "done", t.Callbacks({
                    once: 1,
                    memory: 1
                }), "resolved"], ["reject", "fail", t.Callbacks({
                    once: 1,
                    memory: 1
                }), "rejected"], ["notify", "progress", t.Callbacks({
                    memory: 1
                })]],
                i = "pending",
                a = {
                    state: function() {
                        return i
                    },
                    always: function() {
                        return s.done(arguments).fail(arguments),
                            this
                    },
                    then: function() {
                        var o = arguments;
                        return e(function(e) {
                            t.each(n,
                                function(n, i) {
                                    var r = t.isFunction(o[n]) && o[n];
                                    s[i[1]](function() {
                                        var o = r && r.apply(this, arguments);
                                        if (o && t.isFunction(o.promise)) o.promise().done(e.resolve).fail(e.reject).progress(e.notify);
                                        else {
                                            var n = this === a ? e.promise() : this,
                                                s = r ? [o] : arguments;
                                            e[i[0] + "With"](n, s)
                                        }
                                    })
                                }),
                                o = null
                        }).promise()
                    },
                    promise: function(e) {
                        return null != e ? t.extend(e, a) : a
                    }
                },
                s = {};
            return t.each(n,
                function(t, e) {
                    var o = e[2],
                        r = e[3];
                    a[e[1]] = o.add,
                        r && o.add(function() {
                            i = r
                        },
                        n[1 ^ t][2].disable, n[2][2].lock),
                        s[e[0]] = function() {
                            return s[e[0] + "With"](this === s ? a: this, arguments),
                                this
                        },
                        s[e[0] + "With"] = o.fireWith
                }),
                a.promise(s),
                o && o.call(s, s),
                s
        }
        var o = Array.prototype.slice;
        t.when = function(n) {
            var i, a, s, r = o.call(arguments),
                l = r.length,
                d = 0,
                u = 1 !== l || n && t.isFunction(n.promise) ? l: 0,
                h = 1 === u ? n: e(),
                c = function(t, e, n) {
                    return function(a) {
                        e[t] = this,
                            n[t] = arguments.length > 1 ? o.call(arguments) : a,
                                n === i ? h.notifyWith(e, n) : --u || h.resolveWith(e, n)
                    }
                };
            if (l > 1) for (i = new Array(l), a = new Array(l), s = new Array(l); l > d; ++d) r[d] && t.isFunction(r[d].promise) ? r[d].promise().done(c(d, s, r)).fail(h.reject).progress(c(d, a, i)) : --u;
            return u || h.resolveWith(s, r),
                h.promise()
        },
            t.Deferred = e
    } (Zepto);
var basePath = "http://wximg.gtimg.com/tmt/_events/promo/VJoboTrF/",
    isOn = !0;
if ("function" != typeof webpsupport) var webpsupport = function(t) {
    t()
};
webpsupport(function(t) {
    function e(t, e, o) {
        return function() {
            var n = [].slice.call(arguments),
                i = this,
                a = new $.Deferred;
            return function() {
                return null != e ? n.splice(e, 0, a.resolve) : n.push(a.resolve),
                        null != o ? n.splice(o, 0, a.reject) : n.push(a.reject),
                    t.apply(i, n),
                    a.promise()
            } ()
        }
    }
    $("body").on("touchmove",
        function(t) {
            return t.preventDefault(),
                t.stopPropagation(),
                !1
        });
    for (var o = new WxMoment.Loader,
             n = ["img/bg.jpg"
                 /*tpa=http://wximg.qq.com/wxp/moment/VJoboTrF/js/img/bg.jpg*/
                 , "img/loading.gif"
                 /*tpa=http://wximg.qq.com/wxp/moment/VJoboTrF/js/img/loading.gif*/
                 , "img/du-body.png"
                 /*tpa=http://wximg.qq.com/wxp/moment/VJoboTrF/js/img/du-body.png*/
                 , "img/foot-left.png"
                 /*tpa=http://wximg.qq.com/wxp/moment/VJoboTrF/js/img/foot-left.png*/
                 , "img/foot-right.png"
                 /*tpa=http://wximg.qq.com/wxp/moment/VJoboTrF/js/img/foot-right.png*/
                 , "img/hand-left.png"
                 /*tpa=http://wximg.qq.com/wxp/moment/VJoboTrF/js/img/hand-left.png*/
                 , "img/hand-right.png"
                 /*tpa=http://wximg.qq.com/wxp/moment/VJoboTrF/js/img/hand-right.png*/
                 , "img/star.png"
                 /*tpa=http://wximg.qq.com/wxp/moment/VJoboTrF/js/img/star.png*/
                 , "img/stone.png"
                 /*tpa=http://wximg.qq.com/wxp/moment/VJoboTrF/js/img/stone.png*/
                 , "img/stone1.png"
                 /*tpa=http://wximg.qq.com/wxp/moment/VJoboTrF/js/img/stone1.png*/
                 , "img/little-bottle-cap-light.png"
                 /*tpa=http://wximg.qq.com/wxp/moment/VJoboTrF/js/img/little-bottle-cap-light.png*/
                 , "img/little-bottle-cap.png"
                 /*tpa=http://wximg.qq.com/wxp/moment/VJoboTrF/js/img/little-bottle-cap.png*/
                 , "img/little-bottle-body.png"
                 /*tpa=http://wximg.qq.com/wxp/moment/VJoboTrF/js/img/little-bottle-body.png*/
             ], i = 0; i < n.length; i++) {
        var a = n[i].substring(n[i].lastIndexOf("/") + 1);
        if (t && img_map && a in img_map && img_map[a]) var s = n[i].replace(/\.\w+/, ".webp");
        else var s = n[i];
        o.addImage(basePath + s)
    }
    $(".loading__img").addClass("loading-animation"),
        o.addCompletionListener(function() {
            var t = e(setTimeout, 0).bind(null, 500),
                o = e(setTimeout, 0).bind(null, 1e3),
                n = (e(setTimeout, 0).bind(null, 1500), e(setTimeout, 0).bind(null, 2e3), e(setTimeout, 0).bind(null, 2500)),
                i = (e(setTimeout, 0).bind(null, 3e3), e(setTimeout, 0).bind(null, 3e3), e(setTimeout, 0).bind(null, 5e3), $(".first-page")),
                a = $(".second-page"),
                s = $(".thrid-page"),
                r = $(".du-btn"),
                l = $(".du-teacher"),
                d = $(".cloud1"),
                u = $(".cloud2"),
                h = $(".cloud3"),
                c = $(".bottle-cap");
            n().then(function() {
                return $(".loading-num").hide(),
                    $(".loading__img").addClass("loading-animation1"),
                    o()
            }).then(function() {
                return $(".loading").remove(),
                    i.addClass("zup"),
                    a.hide(),
                    s.hide(),
                    r.hide(),
                    $(".stage").show(),
                    $(".stone").addClass("stone-show"),
                    t()
            }).then(function() {
                return $(".first-page-txt1").addClass("first-page-txt-show"),
                    o()
            }).then(function() {
                return $(".first-page-txt2").addClass("first-page-txt-show"),
                    o()
            }).then(function() {
                return $(".first-page-txt3").addClass("first-page-txt-show"),
                    o()
            }).then(function() {
                return $(".first-page-txt1").removeClass("first-page-txt-show"),
                    $(".first-page-txt2").removeClass("first-page-txt-show"),
                    $(".first-page-txt3").removeClass("first-page-txt-show"),
                    t()
            }).then(function() {
                return $(".first-page-txt-box").remove(),
                    l.addClass("du-teacher-transform"),
                    n()
            }).then(function() {
                l.addClass("du-teacher-animation"),
                    r.show(),
                    r.addClass("opacity-show")
            }),
                l.tap(function() {
                    r.remove(),
                        $(".du-btn-tip").remove(),
                        i.addClass("first-page-move"),
                        i.removeClass("zup"),
                        $(".light-circle").show(),
                        t().then(function() {
                            return $(".light-circle").addClass("light-circle-show"),
                                $(".speed-line").show(),
                                t()
                        }).then(function() {
                            $(".light-line").addClass("light-line-show"),
                                $(".light-line").addClass("light-line-height"),
                                i.removeClass("first-page-move"),
                                i.addClass("first-page-hide");
                            var t = $("body").height() - 4023,
                                e = "translate3d(0," + t + "px, 0)";
                            return $(".stage-bg").css({
                                "-webkit-transform": e
                            }),
                                o()
                        }).then(function() {
                            return i.remove(),
                                $(".light-line2").show(),
                                t()
                        }).then(function() {
                            return a.show(),
                                a.addClass("second-page-show"),
                                a.addClass("zup"),
                                t()
                        }).then(function() {
                            return $(".light-line2").remove(),
                                $(".light-shot2").show(),
                                t()
                        }).then(function() {
                            $(".light-shot2").remove(),
                                $(".speed-line").hide(),
                                $("body").addClass("b-roate"),
                                $(".second-page-txt").addClass("opacity-show")
                        })
                }),
                $(".bottle").tap(function() {
                    isOn = !1,
                        $("body").removeClass("b-roate"),
                        $(".bottle-btn").remove(),
                        $(".light-shot").remove(),
                        $(".light-shot2").remove(),
                        $(".second-page-txt").removeClass("opacity-show"),
                        $(".bottle3").addClass("bottle3-change"),
                        $(".cap-tip-txt").show(),
                        t().then(function() {
                            return $(".second-page-txt").remove(),
                                $(".second-earth").addClass("second-earth-hide"),
                                d.addClass("cloud1-transform"),
                                u.addClass("cloud2-transform"),
                                h.addClass("cloud3-transform"),
                                $(".bottle1").addClass("bottle1-hide"),
                                $(".bottle2").addClass("bottle2-hide"),
                                $(".bottle4").hide(),
                                o()
                        }).then(function() {
                            return $(".bottle3").addClass("bottledrop"),
                                t()
                        }).then(function() {
                            return a.remove(),
                                s.show(),
                                s.addClass("thrid-page-show"),
                                o()
                        }).then(function() {
                            $(".cap-tip-txt").addClass("opacity-show"),
                                $(".cap-tip").show()
                        })
                }),
                c.tap(function() {
                    $(".cap-tip-txt").removeClass("opacity-show"),
                        $(".cap-tip").remove(),
                        $(".bottle-cap-in").addClass("bottle-cap-in-show"),
                        $(".bottle-body").addClass("bottle-body-hide"),
                        c.addClass("bottle-cap-move"),
                        d.removeClass("cloud1-transform"),
                        u.removeClass("cloud2-transform"),
                        h.removeClass("cloud3-transform"),
                        $(".third-page-txt").show(),
                        t().then(function() {
                            return $(".cap-tip-txt").remove(),
                                $(".third-page-txt").addClass("opacity-show"),
                                c.removeClass("bottle-cap-move"),
                                c.addClass("bottle-cap-move2"),
                                t()
                        }).then(function() {
                            return $(".bottle-cap-in").addClass("bottle-cap-light"),
                                $(".earth").addClass("earth-show"),
                                t()
                        }).then(function() {
                            return $(".light").addClass("light-show"),
                                $(".qcode").addClass("qcode-show"),
                                $(".gift-phone").show(),
                                $(".gift-football").show(),
                                $(".gift-ufo").show(),
                                t()
                        }).then(function() {
                            return $(".gift-phone").addClass("gift-phone-show"),
                                $(".gift-football").addClass("gift-football-show"),
                                $(".gift-ufo").addClass("gift-ufo-show"),
                                o()
                        }).then(function() {
                            $(".gift-phone").addClass("gift-animation"),
                                $(".gift-football").addClass("gift-animation2"),
                                $(".gift-ufo").addClass("gift-animation3"),
                                $(".gift-phone-bulle").addClass("gift-phone-bulle-show"),
                                $(".gift-football-bulle").addClass("gift-football-bulle-show"),
                                $(".gift-ufo-bulle").addClass("gift-ufo-bulle-show")
                        })
                }),
                $(".gift-phone").tap(function() {
                    $(this).addClass("zup").siblings().removeClass("zup"),
                        $(".module-block").show(),
                        $(".module1").show(),
                        $(".module1").addClass("gogo"),
                        $(".module2").removeClass("gogo"),
                        $(".module3").removeClass("gogo"),
                        $(".third-page-txt").removeClass("opacity-show").addClass("opacity-hide")
                }),
                $(".gift-football").tap(function() {
                    $(this).addClass("zup").siblings().removeClass("zup"),
                        $(".module-block").show(),
                        $(".module2").show(),
                        $(".module2").addClass("gogo"),
                        $(".module1").removeClass("gogo"),
                        $(".module3").removeClass("gogo"),
                        $(".third-page-txt").removeClass("opacity-show").addClass("opacity-hide")
                }),
                $(".gift-ufo").tap(function() {
                    $(this).addClass("zup").siblings().removeClass("zup"),
                        $(".module-block").show(),
                        $(".module3").show(),
                        $(".module3").addClass("gogo"),
                        $(".module1").removeClass("gogo"),
                        $(".module2").removeClass("gogo"),
                        $(".third-page-txt").removeClass("opacity-show").addClass("opacity-hide")
                }),
                $(".module-close").tap(function() {
                    $(".thrid-txt").addClass("thrid-txt-show"),
                        $(".module-block").hide(),
                        $(".module").hide(),
                        $(".gift-bulle").show(),
                        $(".gift-btn").removeClass("zup"),
                        $(".third-page-txt").removeClass("opacity-hide").addClass("opacity-show")
                }),
                $(".module-block").tap(function() {
                    $(".thrid-txt").addClass("thrid-txt-show"),
                        $(".module-block").hide(),
                        $(".module").hide(),
                        $(".gift-bulle").show(),
                        $(".gift-btn").removeClass("zup"),
                        $(".third-page-txt").removeClass("opacity-hide").addClass("opacity-show")
                })
        }),
        o.addProgressListener(function(t) {
            var e = Math.round(t.completedCount / t.totalCount * 100);
            $(".loading-num").text(e + "%")
        }),
        o.start()
}),
    new WxMoment.OrientationTip;
var baseUrl = window.location.href,
    wxShareUrl = "../html/index.html"
/*tpa=http://wximg.qq.com/wxp/moment/VJoboTrF/html/index.html*/
    ,
    wxShareImg = "../img/share-img.jpg"
/*tpa=http://wximg.qq.com/wxp/moment/VJoboTrF/img/share-img.jpg*/
    ;
window.wx && (wx.onMenuShareTimeline(getWxShareData()), wx.onMenuShareAppMessage(getWxShareMomentData()));
var bindit = function() {
    WeixinJSBridge.on("menu:share:appmessage",
        function() {
            WeixinJSBridge.invoke("sendAppMessage", getWxShareData(),
                function(t) {})
        }),
        WeixinJSBridge.on("menu:share:timeline",
            function() {
                WeixinJSBridge.invoke("shareTimeline", getWxShareMomentData(),
                    function(t) {})
            }),
        WeixinJSBridge.on("menu:share:weibo",
            function() {
                WeixinJSBridge.invoke("shareWeibo", getWxShareMomentData(),
                    function(t) {})
            }),
        WeixinJSBridge.on("menu:share:facebook", getWxShareData(),
            function(t) {})
};
document.addEventListener("WeixinJSBridgeReady", bindit, !1);
var wa = new WxMoment.Analytics({
    projectName: "20150802hengdabingquan"
});
$(".du-teacher").tap(function() {
    wa.sendEvent("click", "du-teacher")
}),
    $(".bottle").tap(function() {
        wa.sendEvent("click", "bottle")
    }),
    $(".bottle-cap").tap(function() {
        wa.sendEvent("click", "bottle-cap")
    }),
    $(".gift-phone").tap(function() {
        wa.sendEvent("click", "gift-phone")
    }),
    $(".module-close").tap(function() {
        wa.sendEvent("click", "module-close")
    }),
    $(".gift-ufo").tap(function() {
        wa.sendEvent("click", "gift-ufo")
    }),
    $(".qrcode2").tap(function() {
        wa.sendEvent("click", "qrcode2")
    });