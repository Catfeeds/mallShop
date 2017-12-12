;(
    function(){
        var ua = navigator.userAgent;
        var isAndroid = /android/ig.test(ua) && /mqq/ig.test(ua);
        if(typeof mtt !== "undefined")
        {
            isAndroid = true;
        }
        var isIos = /iphone|ipod|ios|ipad/ig.test(ua.toLowerCase()) && /mqq/ig.test(ua.toLowerCase());

        function getUA()
        {
            if(isAndroid)
            {
                var info = x5.android.getBrowserParam();
                if(info)
                {
                    info = eval('(' + info + ')');
                    var qua = info.qua + "";
                    qua = qua.match(/([0-9\.]+)/ig, '');
                    if(qua && qua.length > 0)
                        return qua[0];
                }
            }
            try
            {
                var ua = navigator.userAgent;
                var reg = /MQQBrowser\/(\d{2})/;
                var regRemoveDot = /\./g;
                ua =  ua.replace(regRemoveDot,'');
                var res = reg.exec(ua);
                if(res && res.length > 1)
                {
                    return res[1];
                }
                return undefined;
            }
            catch(e)
            {
                return undefined;
            }
        }

        /////////////////// ios QQ浏览器接口 start /////////////////////
        var x5 = {
            commandQueue:[],
            commandQueueFlushing:false,
            resources: {
                base: !0
            }
        };

        x5.callbackId = 0;
        x5.callbacks = {};
        x5.callbackStatus = {
            NO_RESULT:0,
            OK:1,
            CLASS_NOT_FOUND_EXCEPTION:2,
            ILLEGAL_ACCESS_EXCEPTION:3,
            INSTANTIATION_EXCEPTION:4,
            MALFORMED_URL_EXCEPTION:5,
            IO_EXCEPTION:6,
            INVALID_ACTION:7,
            JSON_EXCEPTION:8,
            ERROR:9
        };

        x5.createBridge = function () {
            var bridge = document.createElement("iframe");
            bridge.setAttribute("style", "display:none;");
            bridge.setAttribute("height", "0px");
            bridge.setAttribute("width", "0px");
            bridge.setAttribute("frameborder", "0");
            document.documentElement.appendChild(bridge);
            return bridge;
        };

        x5.exec = function (successCallback, errorCallback, service, action, options) {

            var callbackId = null;
            var command = {
                className:service,
                methodName:action,
                options:{},
                arguments:[]
            };

            if (successCallback || errorCallback) {
                callbackId = service + x5.callbackId++;
                x5.callbacks[callbackId] = {
                    success:successCallback,
                    fail:errorCallback
                };
            }

            if (callbackId != null) {
                command.arguments.push(callbackId);
            }

            for (var i = 0; i < options.length; ++i) {
                var arg = options[i];
                if (arg == undefined || arg == null) {
                    continue;
                } else if (typeof(arg) == 'object') {
                    command.options = arg;
                } else {
                    command.arguments.push(arg);
                }
            }

            x5.commandQueue.push(JSON.stringify(command));
            if (x5.commandQueue.length == 1 && !x5.commandQueueFlushing) {
                if (!x5.bridge) {
                    x5.bridge = x5.createBridge();
                }
                x5.bridge.src = "mtt:" + service + ":" + action;
            }
        };

        // 浏览器调用接口
        x5.getAndClearQueuedCommands = function () {
            var json = JSON.stringify(x5.commandQueue);
            x5.commandQueue = [];
            return json;
        };

        // 浏览器执行成功的回调函数
        x5.callbackSuccess = function (callbackId, args) {
            if (x5.callbacks[callbackId]) {
                if (args.status === x5.callbackStatus.OK) {
                    try {
                        if (x5.callbacks[callbackId].success) {
                            x5.callbacks[callbackId].success(args.message);
                        }
                    } catch (e) {
                        console.log("Error in success callback: " + callbackId + " = " + e);
                    }
                }
                if (!args.keepCallback) {
                    delete x5.callbacks[callbackId];
                }
            }
        };

        // 浏览器执行失败的回调函数
        x5.callbackError = function (callbackId, args) {
            if (x5.callbacks[callbackId]) {
                try {
                    if (x5.callbacks[callbackId].fail) {
                        x5.callbacks[callbackId].fail(args.message);
                    }
                } catch (e) {
                    console.log("Error in error callback: " + callbackId + " = " + e);
                }
                if (!args.keepCallback) {
                    delete x5.callbacks[callbackId];
                }
            }
        };

        x5.ios = x5.ios || {};

        x5.ios.getCryptText = function (options, succCallback, errCallback) {
            x5.exec(succCallback, errCallback, "app", "getCryptText", [options]);
        };

        x5.ios.getBrowserSignature = function (options, succCallback, errCallback) {
            x5.exec(succCallback, errCallback, "app", "getBrowserSignature", [options]);
        };
		x5.ios.getMobileAppSupport = function (options, succCallback, errCallback) {
            x5.exec(succCallback, errCallback, "app", "getMobileAppSupport", [options]);
        };

        /////////////////// ios QQ浏览器接口 end /////////////////////

        x5.android = x5.android || {};

        /////////////////// 安卓5.0以下接口的版本 start /////////////////////
        // 获取浏览器信息
        x5.android.getBrowserParam = function(){
            var browserparam = "";
            if (typeof mtt !== "undefined")
            {
                try
                {
                    if(mtt.getBrowserParam)
                        browserparam =  mtt.getBrowserParam() + "";
                    return browserparam;
                }
                catch(e)
                {
                    return "";
                }
            }
            else
            {
                return "";
            }
        };
        /////////////////// 安卓5.0以下接口的版本 end /////////////////////

        /////////////////// 安卓5.0及其以上接口的版本 start /////////////////////
        // 小包版(js注入漏洞), qb_bridge为非全部变量，大包版中qb_bridge为系统全部变量
        if (window.qb_bridge == undefined) {
            // check ua to make sure it's our lite version instead of other browsers
            var version = getUA();
            if (version >= 50) {
                window.qb_bridge = {
                    nativeExec : function (service, action, callbackId, argsJson) {
                        return prompt(argsJson, 'mtt:[' + [service, action, callbackId] + ']');
                    }
                };
            } else {
                console.log('Not a qq browser or version too old');
            }
        }

        if(!window.qb_bridge)
        {
            window.qb_bridge = {}; // 兼容没有这个变量的版本，避免js报错
        }

        qb_bridge.callbackId = Math.floor(Math.random() * 2000000000);
        qb_bridge.callbacks = {};

        qb_bridge.exec = function (success, fail, service, action, args) {
            var callbackId = service + qb_bridge.callbackId++,
                argsJson = args ? JSON.stringify(args) : "";

            if (success || fail) {
                qb_bridge.callbacks[callbackId] = {success:success, fail:fail};
            }

            var ret = qb_bridge.nativeExec(service, action, callbackId, argsJson);
            if (ret === 'true') {
                return true;
            } else if (ret === 'false') {
                return false;
            } else {
                return ret;
            }
        };

        qb_bridge.callbackFromNative = function (callbackId, args) {
            var callback = qb_bridge.callbacks[callbackId];
            var argsJson = JSON.parse(args);

            if (callback) {
                if (argsJson.succ) {
                    callback.success && callback.success(argsJson.msg);
                } else {
                    callback.fail && callback.fail(argsJson.msg);
                }

                if (!argsJson.keep) {
                    delete qb_bridge.callbacks[callbackId];
                }
            }
        };

        x5.android.qbGetCrypText = function(options, suc, err){
            qb_bridge.exec(suc, err, "app", "getCryptText", options);
        };

        x5.android.qbGetBrowserSignature = function(options, suc, err){
            qb_bridge.exec(suc, err, "app", "getBrowserSignature", options);
        };

        /////////////////// 安卓5.0及其以上接口的版本 end /////////////////////


        x5.android.getCryptText = function(option, suc, err){
            if(typeof x5mtt != "undefined")
            {
                x5mtt.getCryptText(suc, err, JSON.stringify(option));
            }
            else
            {
                err && err();
            }
        };

        x5.android.getBrowserSignature = function(option, suc, err){
            if(typeof x5mtt != "undefined")
            {
                if(x5mtt.getBrowserSignature)
                {
                    var ret = x5mtt.getBrowserSignature(option);
                    suc && suc(ret);
                }
                else
                {
                    err && err();
                }
            }
            else
            {
                err && err();
            }
        };

        // 旧的版本采用T5Kit,新的版本采用x5(协议)
        window.T5Kit = {};
        for(var i in x5)
        {
            T5Kit[i] = x5[i];
        }

        x5.getQQBrowerVer = getUA;

        x5.getCrypText = function(option, suc, err)
        {
            if(!isAndroid && !isIos) // 不是qq浏览器
            {
                err && err({
                    message: "浏览器不支持该接口"
                });
            }
            else
            {
                if(isAndroid)
                {
                    x5.android.getCryptText(option, suc, err);
                }
                else
                {
                    x5.ios.getCryptText(option, suc, err);
                }
            }
        };

        x5.getCryptext = x5.getCrypText; // 为了兼容就的测试例子

        x5.getBrowserSignature = function(option, suc, err)
        {
            if(!isAndroid && !isIos) // 不是qq浏览器
            {
                err && err({
                    message: "浏览器不支持该接口"
                });
            }
            else
            {
                if(isAndroid)
                {
                    x5.android.getBrowserSignature(option, suc, err);
                }
                else
                {
                    x5.ios.getBrowserSignature({data: option}, function(ret){
                        suc && suc(ret["data"]);
                    }, err);
                }
            }
        };

        window.x5 = x5;
    }
)();
