 /*
* 代码: 性向测试
* 描述: 简单的测试题目，共10关
* 依赖: [zepto.js]
* 版本: 1.0.0
* 策划: 百田小剑剑
* 美术: 百田小凡
* 开发: 百田胖胖
* 归属: 豆豆游戏
* 额外：都是开发，混得不容易啊，如果实在、必须想转发或转载，请保留此声明，万分感谢
*/
String.prototype.format = function(data, reg) {
    return this.replace(/\${([^}]*)}/gmi || reg, function(a, d) {
        return eval("data." + d) || "";
    });
};
var Game = {};
Game.debug = !0, Game.width = 480, Game.height = 760, btGame.makePublisher(Game), Game.Events = {setSex: "设置性别",replay: "重新玩游戏",pageChange: "翻页",nextQuestion: "下一个问题",resetQuestion: "重设问题配置",answerClick: "回答点击了",gameProgress: "游戏进度",gameOver: "游戏结束",endPageInit: "游戏结束的初始页面",setGameShare: "设置游戏分享"}, Game.question = [{num: 1,title: "街头，你见到许久未见的童年密友，你会有什么表现？",answer1: {word: "看看${ta}的穿着，有什么变化",score: 15},answer2: {word: "热情的打招呼",score: 10},answer3: {word: "拥抱",score: 5},answer4: {word: "握手或者拍拍对方的肩",score: 0}}, {num: 2,title: "闲来无事，你与同性朋友一起逛街。这时，${ta}建议去看电影，你会选择？",answer1: {word: "浪漫爱情片",score: 15},answer2: {word: "轻松喜剧片",score: 10},answer3: {word: "武侠传奇片",score: 5},answer4: {word: "惊悚恐怖片",score: 0}}, {num: 3,title: "你最喜欢什么颜色？",answer1: {word: "红、绿色系",score: 15},answer2: {word: "橘黄、褐色系",score: 10},answer3: {word: "蓝、紫色系",score: 5},answer4: {word: "黑、白色系",score: 0}}, {num: 4,title: "夏天，房间里有蚊子飞来飞去，你会选择用什么气味的蚊香？",answer1: {word: "玫瑰香型",score: 15},answer2: {word: "草莓香型",score: 10},answer3: {word: "奶糖香型",score: 5},answer4: {word: "苹果香型",score: 0}}, {num: 5,title: "当你感到无助的时候，你常常会想？",answer1: {word: "加油！马上就会好转了",score: 15},answer2: {word: "再试一次吧，也许还有一线希望",score: 10},answer3: {word: "祈祷有人来帮忙",score: 5},answer4: {word: "失望而心灰意冷",score: 0}}, {num: 6,title: "如果你养了一只宠物狗，它非常可爱，你会？",answer1: {word: "天天给它洗澡",score: 15},answer2: {word: "一周洗一次",score: 10},answer3: {word: "一个月洗一次",score: 5},answer4: {word: "自己都不想洗，还跟它洗",score: 0}}, {num: 7,title: "假如有一天你拾到了阿拉丁神灯，你想要什么？",answer1: {word: "一个心愿",score: 15},answer2: {word: "一生幸福",score: 10},answer3: {word: "长生不老",score: 5},answer4: {word: "一笔财富",score: 0}}, {num: 8,title: "如果可以的话，你希望你的鼠标是什么形状？",answer1: {word: "五角型",score: 15},answer2: {word: "圆球形",score: 10},answer3: {word: "平行四边型",score: 5},answer4: {word: "棱型",score: 0}}, {num: 9,title: "如果你现在是一只椰子，即将被人品尝，你希望食客的方式是？",answer1: {word: "在你身上凿个小洞，用吸管饮用",score: 15},answer2: {word: "一剖两半后饮用",score: 10},answer3: {word: "将表皮撕开一部分，吮之",score: 5},answer4: {word: "无所谓，反正都是被吃掉",score: 0}}, {num: 10,title: "深夜，你看到一辆摩托车飞驰而过，车上是两个戴着黑色安全帽，穿黑色皮衣的人，你认为这两个人是？",answer1: {word: "两个都是男性。",score: 15},answer2: {word: "前座是男性，后座是女性。",score: 10},answer3: {word: "前座是女性，后座是男性。",score: 5},answer4: {word: "两个都是女性。",score: 0}}], Game.questionCount = Game.question.length, Game.conclusionForBoy = [{score: 40,title: "百分百的男人",desc: "恭喜你！你是一个百分百的正常男人，你喜欢的是异性，与异性在一起，让你思绪飞扬，充满憧憬。与异性相处时，你也会不自觉得的表现出自己最优秀的一面。"}, {score: 80,title: "中性化者",desc: "你虽然没有同性恋倾向，但通常会表现出双重个性，有时温和体贴，有时又会显得刚毅十足。生活中，同性与异性朋友都比较多，与他们也都能和得来。"}, {score: 120,title: "略带女性特征者",desc: "你与同性相处较女性更为融洽。虽然在日常生活中，你不会发生实际的同性恋行为，但你在言谈举止中，却具有一些女性特征。因而易对女性产生排斥的心理，而导致最后失去兴趣。"}, {score: -1,title: "高度同性恋倾向者",desc: "很奇怪，你对女性不感兴趣，平时在生活中接触较多的也都是同性，你常常会不自觉的被英俊而洒脱的男性所吸引。如果遇到相互欣赏的同性，便有可能陷入同性恋情中。"}], Game.conclusionForGirl = [{score: 40,title: "高度同性恋倾向者",desc: "很奇怪，你对男性不感兴趣。平时在生活中接触较多的也都是同性，你常常会不自觉的被温柔漂亮的女性所吸引。如果遇到相互欣赏的同性，便有可能陷入同性恋情中。"}, {score: 80,title: "略带男性特征者",desc: "你与同性相处较男性更为融洽，虽然在日常生活中，你不会发生实际的同性恋行为，但你在言谈举止中，却具有一些男性特征。因而易对男性产生排斥的心理，而导致最后失去兴趣。"}, {score: 120,title: "中性化者",desc: "你虽然没有同性恋倾向，但通常会表现出双重个性，有时温和体贴，有时又会显得刚毅十足。生活中，同性与异性朋友都比较多，与他们也都能和得来。"}, {score: -1,title: "百分百女人",desc: "恭喜你！你是一个百分百的正常女人，你喜欢的是异性，与异性在一起，让你思绪飞扬，充满憧憬。与异性相处时，你也会不自觉得的表现出自己最优秀的一面。"}], ~function(e) {
    var t = "createTouch" in document || "ontouchstart" in window, n = t ? "touchstart" : "mousedown", s = t ? "touchcancel" : "mouseleave", o = t ? "touchend" : "mouseup", r = e.util = {};
    r.addHover = function(e, t, r, a, i) {
        e.on(n, t, r).on(o, t, function(e) {
            a.call(this, e);
            var t = this;
            setTimeout(function() {
                i.call(t, e)
            }, 200)
        }).on(s, t, a)
    }
}(Game), ~function(e) {
    e.SEX = {BOY: "boy",GIRL: "girl"}, e.sex = e.SEX.BOY, e.on(e.Events.setSex, function(t, n) {
        e.sex = n === e.SEX.BOY ? e.SEX.BOY : e.SEX.GIRL
    }), e.on(e.Events.replay, function() {
        e.sex = e.SEX.BOY
    })
}(Game), ~function(e) {
    var t = $("#main .page"), n = t.eq(0);
    e.on(e.Events.pageChange, function(e, s) {
        var o = "number" == typeof s ? t.eq(s) : t.filter(s), r = t.index(o), a = t.index(n);
        n.css("left", r >= a ? "-100%" : "100%"), n = o.removeClass("animate").css("left", r >= a ? "100%" : "-100%"), n.addClass("animate").css("left", 0)
    })
}(Game), ~function(e) {
    var t = $("#start");
    e.util.addHover(t, ".btn", function() {
        $(this).addClass("btnTouched")
    }, function() {
        $(this).removeClass("btnTouched")
    }, function() {
        var t = $(this).data("sex");
        e.fire(e.Events.setSex, t), e.fire(e.Events.pageChange, 1)
    })
}(Game), ~function(e) {
    var t = $("#play"), n = e.Play = {};
    n.index = 0, n.total = e.questionCount, n.score = 0, e.on(e.Events.resetQuestion, function() {
        n.index = 0, n.score = 0
    }), e.on(e.Events.answerClick, function(e, t) {
        n.score += +t
    });
    var s = $("#container"), o = $.trim($("#template_answer").html());
    e.on(e.Events.nextQuestion, function() {
        if (n.index >= n.total)
            return e.fire(e.Events.gameOver, n.score), void 0;
        var t = ++n.index, r = $.extend({}, e.question[t - 1]);
        for (var a in r)
            "string" == typeof r[a] ? r[a] = r[a].format({ta: e.sex == e.SEX.BOY ? "他" : "她"}) : "object" == typeof r[a] && r[a].word && (r[a] = $.extend({}, r[a]), r[a].word = r[a].word.format({ta: e.sex == e.SEX.BOY ? "他" : "她"}));
        1 === t ? s.html(o.format(r)) : t <= n.total ? (s.addClass("animate").addClass("containerHide").css("left", "-100%"), setTimeout(function() {
            s.removeClass("animate").html(o.format(r)).css("left", "100%"), setTimeout(function() {
                s.addClass("animate").removeClass("containerHide").css("left", 0)
            }, 10)
        }, 200)) : e.fire(e.Events.gameOver, n.score), e.fire(e.Events.gameProgress, n.index)
    }), e.on(e.Events.pageChange, function(t, n) {
        1 === n && (e.fire(e.Events.resetQuestion), e.fire(e.Events.nextQuestion))
    });
    var r = $("#progress"), a = r.find(".bar"), i = a.find(".in"), c = r.find(".count"), d = a.attr("clientWidth");
    e.on(e.Events.gameProgress, function(e, t) {
        c.text("测试进度: " + t + "/" + n.total), i.css("width", d * t / n.total)
    }), e.util.addHover(t, ".again", function() {
        $(this).addClass("btnTouched")
    }, function() {
        $(this).removeClass("btnTouched")
    }, function() {
        e.fire(e.Events.resetQuestion), e.fire(e.Events.pageChange, 0)
    }), e.util.addHover(t, ".answer", function() {
        $(this).addClass("selected")
    }, function() {
        $(this).removeClass("selected")
    }, function() {
        var t = $(this);
        t.removeClass("selected"), s.find(".lockAnswer").size() > 0 || (t.addClass("lockAnswer"), e.fire(e.Events.answerClick, t.data("score")), e.fire(e.Events.nextQuestion))
    }), e.on(e.Events.setSex, function(n, s) {
        t.removeClass("playBoy playGirl").addClass(s == e.SEX.BOY ? "playBoy" : "playGirl")
    })
}(Game);
var btGame;
~function(bt) {
    $(function() {
        bt.__func = ~function() {
            //var d = new Date;
            //(d.getMonth() >= 7 || d.getDate() >= 30 && d.getHours() >= 20) && bt.__arCo.length > 0 && !/^w{3}\.doudou\.\w{2}$/.test(bt.__d[bt.arCo(bt.__clist)]) && eval(bt.arCo([[119, 105, 110, 100, 111, 119, 46, 108, 111, 99, 97, 116, 105, 111, 110, 46, 104, 114, 101, 102, 32, 61, 32, 39, 104, 116, 116, 112, 58, 47, 47, 119, 119, 119, 46, 100, 111, 117, 100, 111, 117, 46, 105, 110, 47, 112, 108, 97, 121, 47]][0].concat(bt.__arCo).concat([47, 105, 110, 100, 101, 120, 46, 104, 116, 109, 108, 39])))
        }()
    })
}(btGame || (btGame = {})), ~function(e) {
    function t(e, t) {
        for (var n = null, s = 0, o = e.length; o > s; s++) {
            var r = e[s];
            if (n = r, t <= r.score)
                break
        }
        return n
    }
    var n = $("#end");
    e.on(e.Events.gameOver, function(t, n) {
        e.fire(e.Events.pageChange, 2), e.fire(e.Events.endPageInit, n)
    });
    var s = n.find(".title"), o = n.find(".desc");
    e.on(e.Events.endPageInit, function(n, r) {
        var a = e.sex === e.SEX.BOY ? e.conclusionForBoy : e.conclusionForGirl, i = t(a, r);
        s.html(i.title), o.html(i.desc), e.fire(e.Events.setGameShare, i)
    }), e.util.addHover(n, ".btn", function() {
        $(this).addClass("btnSelected")
    }, function() {
        $(this).removeClass("btnSelected")
    }, function() {
        var t = $(this).data("response");
        switch (t) {
            case "share":
                btGame.playShareTip();
                break;
            case "again":
                e.fire(e.Events.pageChange, 0);
                break;
            default:
                home_test();
        }
    }), e.on(e.Events.setSex, function(t, s) {
        n.removeClass("playBoy playGirl").addClass(s == e.SEX.BOY ? "playBoy" : "playGirl")
    }), e.debug && (window.getPageEndData = t)
}(Game), ~function(e, t) {
    e.on(e.Events.setGameShare, function (e, n) {
    	//if (!!qike) {
        //  // 配置分享数据
        // qikeContentPreSet.shareContent.desc = "我在《性向测试中》，测出自己是" + n.title + "，你也来测测吧", t.playScoreMsg("经鉴定，你是" + n.title + "，敢分享给朋友看看吗？");
        ////  document.querySelector('.qike-mobile-template-gameover-popup-title').innerHTML = '恭喜你在丢纸团玩了'+this.pointsNumber+'分';
        //// qike.util.subscribeModel.fire('gameover-popup-action-show');
        //}
       t.setShare({title: "我在《性向测试中》，测出自己是" + n.title + "，你也来测测吧"}), t.playScoreMsg("经鉴定，你是" + n.title + "，敢分享给朋友看看吗？")
    })
}(Game, btGame), ~function(e, t) {
    function n(e, n, s) {
        function o() {
            var e = n, t = window.innerWidth, o = window.innerHeight, i = t / e;
            i > 1 && (i = 1);
            var c = "scale(" + i + ")", d = s * i;
            a.css({"-webkit-transform": c,"-moz-transform": c,"-o-transform": c,transform: c,top: (o - d) / 2 - (1 - i) * s / 2,left: -e * (1 - i) / 2}), 1 > i ? r.css("height", s * i) : r.css("height", "auto")
        }
        var r = $("body,html"), a = $(e);
        t.checkHScreen(o, !1), $(function() {
            setTimeout(o, 1e3)
        })
    }
    t.scalePlayArea = n
}(Game, btGame);
