! function(t, e) {
	function n(t, e) {
		"use strict";

		function r(t, e) {
			return function() {
				return t.apply(e, arguments)
			}
		}
		var i;
		if (e = e || {}, this.trackingClick = !1, this.trackingClickStart = 0, this.targetElement = null, this.touchStartX = 0, this.touchStartY = 0, this.lastTouchIdentifier = 0, this.touchBoundary = e.touchBoundary || 10, this.layer = t, this.tapDelay = e.tapDelay || 200, !n.notNeeded(t)) {
			for (var s = ["onMouse", "onClick", "onTouchStart", "onTouchMove", "onTouchEnd", "onTouchCancel"], a = this, u = 0, c = s.length; c > u; u++) a[s[u]] = r(a[s[u]], a);
			o && (t.addEventListener("mouseover", this.onMouse, !0), t.addEventListener("mousedown", this.onMouse, !0), t.addEventListener("mouseup", this.onMouse, !0)), t.addEventListener("click", this.onClick, !0), t.addEventListener("touchstart", this.onTouchStart, !1), t.addEventListener("touchmove", this.onTouchMove, !1), t.addEventListener("touchend", this.onTouchEnd, !1), t.addEventListener("touchcancel", this.onTouchCancel, !1), Event.prototype.stopImmediatePropagation || (t.removeEventListener = function(e, n, r) {
				var i = Node.prototype.removeEventListener;
				"click" === e ? i.call(t, e, n.hijacked || n, r) : i.call(t, e, n, r)
			}, t.addEventListener = function(e, n, r) {
				var i = Node.prototype.addEventListener;
				"click" === e ? i.call(t, e, n.hijacked || (n.hijacked = function(t) {
					t.propagationStopped || n(t)
				}), r) : i.call(t, e, n, r)
			}), "function" == typeof t.onclick && (i = t.onclick, t.addEventListener("click", function(t) {
				i(t)
			}, !1), t.onclick = null)
		}
	}
	var r = e;
	if (r.NODEB || function(t) {
		function n(t, e, n) {
			if (l[t] === s && c[t] === s) {
				var r = arguments.length;
				if (2 > r) return void u.error(arguments + "\u53c2\u6570\u4e0d\u591f\u554a");
				var o = 2 == r ? e : n;
				l[t] = o, i(t)
			}
		}

		function r(t) {
			if (!t) return null;
			f[t] && (t = f[t]), l[t] && i(t);
			for (var e = t.split("/"), n = c, r = 0; r < e.length; r++) {
				if (!n) return null;
				n = n[e[r]]
			}
			return n
		}

		function i(e) {
			var n = c,
				i = l[e];
			if (i === s) return !1;
			delete l[e];
			for (var o = e.split("/"), a = 0; a < o.length; a++) {
				var f = o[a];
				if (a == o.length - 1) {
					if (n[f] && !n[f].__$m$__) return;
					try {
						if ("string" == typeof i || "number" == typeof i || "object" == typeof i) n[f] = i;
						else if (i) {
							var h = {}, p = {
									exports: h
								}, d = i.call(t, r, h, p);
							n[f] = p.exports && ("object" != typeof p.exports || Object.keys(p.exports).length) || p.exports != h ? p.exports : d === s ? null : d
						} else n[f] = null
					} catch (g) {
						setTimeout(function() {
							u.error(e, g)
						}, 60)
					}
					break
				}
				n[f] || (n[f] = {
					__$m$__: 1
				}), n = n[f]
			}
		}

		function o(t) {
			return h.call(arguments, 1).forEach(function(e) {
				for (var n in e) t[n] = e[n]
			}), t
		}
		t = t || e;
		var s, a = t.NODEB = {}, u = t.console || {
				log: function() {},
				error: function() {}
			}, c = {}, l = {}, f = {}, h = Array.prototype.slice;
		n.amd = !0, a.alias = function(t) {
			for (var e in t) f[e] = t[e]
		};
		var p = a._config = {};
		a.config = function(t) {
			t && o(p, t)
		}, a.require = r, a.define = n, a.extend = o
	}(e), t = t || r.NODEB.define, e.define = t, t("NODEB", r.NODEB), !e.af || "function" != typeof i) {
		var i = function(t) {
			"use strict";

			function e(t, e) {
				return "number" != typeof e || M[t.toLowerCase()] ? e : e + "px"
			}

			function n(t, e, n) {
				var r = T.createDocumentFragment();
				if (n) {
					for (var i = t.length - 1; i >= 0; i--) r.insertBefore(t[i], r.firstChild);
					e.insertBefore(r, e.firstChild)
				} else {
					for (var o = 0; o < t.length; o++) r.appendChild(t[o]);
					e.appendChild(r)
				}
				r = null
			}

			function r(t) {
				return t in S ? S[t] : S[t] = new RegExp("(^|\\s)" + t + "(\\s|$)")
			}

			function o(t) {
				for (var e = 0; e < t.length; e++) t.indexOf(t[e]) !== e && (t.splice(e, 1), e--);
				return t
			}

			function s(t, e) {
				var n = [];
				if (t == k) return n;
				for (; t; t = t.nextSibling) 1 === t.nodeType && t !== e && n.push(t);
				return n
			}

			function a(t, e) {
				try {
					return e.querySelectorAll(t)
				} catch (n) {
					return []
				}
			}

			function u(t, e) {
				if (t = t.trim(), "#" === t[0] && -1 === t.indexOf(".") && -1 === t.indexOf(",") && -1 === t.indexOf(" ") && -1 === t.indexOf(">")) e === T ? c(e.getElementById(t.replace("#", "")), this) : c(a(t, e), this);
				else if ("<" === t[0] && ">" === t[t.length - 1] || -1 !== t.indexOf("<") && -1 !== t.indexOf(">")) {
					var n = T.createElement("div");
					L ? MSApp.execUnsafeLocalFunction(function() {
						n.innerHTML = t.trim()
					}) : n.innerHTML = t.trim(), c(n.childNodes, this)
				} else c(a(t, e), this);
				return this
			}

			function c(t, e) {
				if (t) {
					if (t.nodeType) return void(e[e.length++] = t);
					for (var n = 0, r = t.length; r > n; n++) e[e.length++] = t[n]
				}
			}

			function l() {}

			function f(e, n) {
				e.os = {}, e.os.webkit = n.match(/WebKit\/([\d.]+)/) ? !0 : !1, e.os.android = n.match(/(Android)\s+([\d.]+)/) || n.match(/Silk-Accelerated/) ? !0 : !1, e.os.androidICS = e.os.android && n.match(/(Android)\s4/) ? !0 : !1, e.os.ipad = n.match(/(iPad).*OS\s([\d_]+)/) ? !0 : !1, e.os.iphone = !e.os.ipad && n.match(/(iPhone\sOS)\s([\d_]+)/) ? !0 : !1, e.os.ios7 = (e.os.ipad || e.os.iphone) && n.match(/7_/) ? !0 : !1, e.os.webos = n.match(/(webOS|hpwOS)[\s\/]([\d.]+)/) ? !0 : !1, e.os.touchpad = e.os.webos && n.match(/TouchPad/) ? !0 : !1, e.os.ios = e.os.ipad || e.os.iphone, e.os.playbook = n.match(/PlayBook/) ? !0 : !1, e.os.blackberry10 = n.match(/BB10/) ? !0 : !1, e.os.blackberry = e.os.playbook || e.os.blackberry10 || n.match(/BlackBerry/) ? !0 : !1, e.os.chrome = n.match(/Chrome/) ? !0 : !1, e.os.opera = n.match(/Opera/) ? !0 : !1, e.os.fennec = n.match(/fennec/i) ? !0 : n.match(/Firefox/) ? !0 : !1, e.os.ie = n.match(/MSIE 10.0/i) || n.match(/Trident\/7/i) ? !0 : !1, e.os.ieTouch = e.os.ie && n.toLowerCase().match(/touch/i) ? !0 : !1, e.os.tizen = n.match(/Tizen/i) ? !0 : !1, e.os.supportsTouch = t.DocumentTouch && T instanceof t.DocumentTouch || "ontouchstart" in t, e.os.kindle = n.match(/Silk-Accelerated/) ? !0 : !1, e.feat = {};
				var r = T.documentElement.getElementsByTagName("head")[0];
				e.feat.nativeTouchScroll = "undefined" != typeof r.style["-webkit-overflow-scrolling"] && (e.os.ios || e.os.blackberry10), e.feat.cssPrefix = e.os.webkit ? "Webkit" : e.os.fennec ? "Moz" : e.os.ie ? "ms" : e.os.opera ? "O" : "", e.feat.cssTransformStart = e.os.opera ? "(" : "3d(", e.feat.cssTransformEnd = e.os.opera ? ")" : ",0)", e.os.android && !e.os.webkit && (e.os.android = !1);
				for (var i = ["Webkit", "Moz", "ms", "O"], o = 0; o < i.length; o++) "" === T.documentElement.style[i[o] + "Transform"] && (e.feat.cssPrefix = i[o])
			}

			function h(t) {
				return t._afmid || (t._afmid = P++)
			}

			function p(t, e, n, r) {
				if (e = d(e), e.ns) var i = g(e.ns);
				return (D[h(t)] || []).filter(function(t) {
					return !(!t || e.e && t.e !== e.e || e.ns && !i.test(t.ns) || n && t.fn !== n && ("function" != typeof t.fn || "function" != typeof n || t.fn !== n) || r && t.sel !== r)
				})
			}

			function d(t) {
				var e = ("" + t).split(".");
				return {
					e: e[0],
					ns: e.slice(1).sort().join(" ")
				}
			}

			function g(t) {
				return new RegExp("(?:^| )" + t.replace(" ", " .* ?") + "(?: |$)")
			}

			function v(t, e, n) {
				F.isObject(t) ? F.each(t, n) : t.split(/\s/).forEach(function(t) {
					n(t, e)
				})
			}

			function m(t, e, n, r, i) {
				var o = h(t),
					s = D[o] || (D[o] = []);
				v(e, n, function(e, n) {
					var o = i && i(n, e),
						a = o || n,
						u = function(e) {
							if (e.ns) {
								var n = g(e.ns);
								if (!n.test(c.ns)) return
							}
							var r = a.apply(t, [e].concat(e.data));
							return r === !1 && e.preventDefault(), r
						}, c = F.extend(d(e), {
							fn: n,
							proxy: u,
							sel: r,
							del: o,
							i: s.length
						});
					s.push(c), t.addEventListener(c.e, u, !1)
				})
			}

			function y(t, e, n, r) {
				var i = h(t);
				v(e || "", n, function(e, n) {
					p(t, e, n, r).forEach(function(e) {
						delete D[i][e.i], t.removeEventListener(e.e, e.proxy, !1)
					})
				})
			}

			function b(t) {
				var e = F.extend({
					originalEvent: t
				}, t);
				return F.each(H, function(n, r) {
					e[n] = function() {
						return this[r] = B, "stopImmediatePropagation" !== n && "stopPropagation" !== n || (t.cancelBubble = !0, t[n]) ? t[n].apply(t, arguments) : void 0
					}, e[r] = R
				}), e
			}

			function x(t, e, n, r, i) {
				m(t, e, n, r, function(e) {
					return function(n) {
						var o, s = F(n.target).closest(r, t).get(0);
						return s ? (o = F.extend(b(n), {
							currentTarget: s,
							liveFired: t,
							delegateTarget: t,
							data: i
						}), e.apply(s, [o].concat([].slice.call(arguments, 1)))) : void 0
					}
				})
			}

			function E(t, e) {
				if (e && t.dispatchEvent) {
					var n = F.Event("destroy", {
						bubbles: !1
					});
					t.dispatchEvent(n)
				}
				var r = h(t);
				if (r && D[r]) {
					for (var i in D[r]) t.removeEventListener(D[r][i].e, D[r][i].proxy, !1);
					delete D[r]
				}
			}

			function C(t, e) {
				if (t) {
					var n = t.childNodes;
					if (n && n.length > 0)
						for (var r; r < n.length; r++) C(n[r], e);
					E(t, e)
				}
			}
			var k, T = t.document,
				w = [],
				O = w.slice,
				S = {}, _ = 1,
				j = /<(\w+)[^>]*>/,
				N = {}, A = {}, M = {
					columncount: !0,
					fontweight: !0,
					lineheight: !0,
					"column-count": !0,
					"font-weight": !0,
					"line-height": !0,
					opacity: !0,
					orphans: !0,
					widows: !0,
					zIndex: !0,
					"z-index": !0,
					zoom: !0
				}, L = "object" == typeof MSApp,
				I = function(t, e) {
					if (this.length = 0, !t) return this;
					if (t instanceof I && e == k) return t;
					if (i.isFunction(t)) return i(T).ready(t);
					if (i.isArray(t) && t.length != k) {
						for (var n = 0; n < t.length; n++) this[this.length++] = t[n];
						return this
					}
					if (i.isObject(t) && i.isObject(e)) {
						if (t.length == k) t.parentNode == e && (this[this.length++] = t);
						else
							for (var r = 0; r < t.length; r++) t[r].parentNode == e && (this[this.length++] = t[r]);
						return this
					}
					if (i.isObject(t) && e == k) return this[this.length++] = t, this;
					if (e !== k) {
						if (e instanceof I) return e.find(t)
					} else e = T;
					return this.selector(t, e)
				}, F = function(t, e) {
					return new I(t, e)
				};
			F.is$ = function(t) {
				return t instanceof I
			}, F.map = function(t, e) {
				var n, r, i, o = [];
				if (F.isArray(t))
					for (r = 0; r < t.length; r++) n = e.apply(t[r], [t[r], r]), n !== k && o.push(n);
				else if (F.isObject(t))
					for (i in t) t.hasOwnProperty(i) && "length" !== i && (n = e(t[i], [t[i], i]), n !== k && o.push(n));
				return o
			}, F.each = function(t, e) {
				var n, r;
				if (F.isArray(t)) {
					for (n = 0; n < t.length; n++)
						if (e(n, t[n]) === !1) return t
				} else if (F.isObject(t))
					for (r in t)
						if (t.hasOwnProperty(r) && "length" !== r && e(r, t[r]) === !1) return t;
				return t
			}, F.extend = function(t) {
				if (t == k && (t = this), 1 === arguments.length) {
					for (var e in t) this[e] = t[e];
					return this
				}
				return O.call(arguments, 1).forEach(function(e) {
					for (var n in e) t[n] = e[n]
				}), t
			}, F.isArray = function(t) {
				return t instanceof Array && t.push != k
			}, F.isFunction = function(t) {
				return "function" == typeof t && !(t instanceof RegExp)
			}, F.isObject = function(t) {
				return "object" == typeof t && null !== t
			}, F.fn = I.prototype = {
				namespace: "appframework",
				constructor: I,
				forEach: w.forEach,
				reduce: w.reduce,
				push: w.push,
				indexOf: w.indexOf,
				concat: w.concat,
				selector: u,
				oldElement: void 0,
				sort: w.sort,
				slice: w.slice,
				length: 0,
				setupOld: function(t) {
					return t == k ? F() : (t.oldElement = this, t)
				},
				map: function(t) {
					var e, n, r = [];
					for (n = 0; n < this.length; n++) e = t.apply(this[n], [n, this[n]]), e !== k && r.push(e);
					return F(r)
				},
				each: function(t) {
					return this.forEach(function(e, n) {
						t.call(e, n, e)
					}), this
				},
				ready: function(t) {
					return "complete" === T.readyState || "loaded" === T.readyState || !F.os.ie && "interactive" === T.readyState ? t() : T.addEventListener("DOMContentLoaded", t, !1), this
				},
				find: function(t) {
					if (0 === this.length) return this;
					for (var e, n = [], r = 0; r < this.length; r++) {
						e = F(t, this[r]);
						for (var i = 0; i < e.length; i++) n.push(e[i])
					}
					return F(o(n))
				},
				html: function(t, e) {
					var n = function() {
						i.innerHTML = t
					};
					if (0 === this.length) return this;
					if (t === k) return this[0].innerHTML;
					for (var r = 0; r < this.length; r++)
						if (e !== !1 && F.cleanUpContent(this[r], !1, !0), L) {
							var i = this[r];
							MSApp.execUnsafeLocalFunction(n)
						} else this[r].innerHTML = t;
					return this
				},
				text: function(t) {
					if (0 === this.length) return this;
					if (t === k) return this[0].textContent;
					for (var e = 0; e < this.length; e++) this[e].textContent = t;
					return this
				},
				css: function(n, r, i) {
					var o = i != k ? i : this[0];
					if (0 === this.length) return this;
					if (r == k && "string" == typeof n) return o.style[n] ? o.style[n] : t.getComputedStyle(o)[n];
					for (var s = 0; s < this.length; s++)
						if (F.isObject(n))
							for (var a in n) this[s].style[a] = e(a, n[a]);
						else this[s].style[n] = e(n, r);
					return this
				},
				vendorCss: function(t, e, n) {
					return this.css(F.feat.cssPrefix + t, e, n)
				},
				cssTranslate: function(t) {
					return this.vendorCss("Transform", "translate" + F.feat.cssTransformStart + t + F.feat.cssTransformEnd)
				},
				computedStyle: function(e) {
					return 0 !== this.length && e != k ? t.getComputedStyle(this[0], "")[e] : void 0
				},
				empty: function() {
					for (var t = 0; t < this.length; t++) F.cleanUpContent(this[t], !1, !0), this[t].textContent = "";
					return this
				},
				hide: function() {
					if (0 === this.length) return this;
					for (var t = 0; t < this.length; t++) "none" !== this.css("display", null, this[t]) && (this[t].setAttribute("afmOldStyle", this.css("display", null, this[t])), this[t].style.display = "none");
					return this
				},
				show: function() {
					if (0 === this.length) return this;
					for (var t = 0; t < this.length; t++) "none" === this.css("display", null, this[t]) && (this[t].style.display = this[t].getAttribute("afmOldStyle") ? this[t].getAttribute("afmOldStyle") : "block", this[t].removeAttribute("afmOldStyle"));
					return this
				},
				toggle: function(t) {
					if (0 === this.length) return this;
					for (var e = t === !0, n = 0; n < this.length; n++) "none" === this.css("display", null, this[n]) || t != k && e !== !1 ? "none" !== this.css("display", null, this[n]) || t != k && e !== !0 || (this[n].style.display = this[n].getAttribute("afmOldStyle") ? this[n].getAttribute("afmOldStyle") : "block", this[n].removeAttribute("afmOldStyle")) : (this[n].setAttribute("afmOldStyle", this.css("display", null, this[n])), this[n].style.display = "none");
					return this
				},
				val: function(t) {
					if (0 === this.length) return t === k ? void 0 : this;
					if (t == k) return this[0].value;
					for (var e = 0; e < this.length; e++) this[e].value = t;
					return this
				},
				attr: function(t, e) {
					if (0 === this.length) return e === k ? void 0 : this;
					if (e === k && !F.isObject(t)) {
						var n = this[0].afmCacheId && N[this[0].afmCacheId] && N[this[0].afmCacheId][t] ? N[this[0].afmCacheId][t] : this[0].getAttribute(t);
						return n
					}
					for (var r = 0; r < this.length; r++)
						if (F.isObject(t))
							for (var i in t) F(this[r]).attr(i, t[i]);
						else F.isArray(e) || F.isObject(e) || F.isFunction(e) ? (this[r].afmCacheId || (this[r].afmCacheId = F.uuid()), N[this[r].afmCacheId] || (N[this[r].afmCacheId] = {}), N[this[r].afmCacheId][t] = e) : null === e ? (this[r].removeAttribute(t), this[r].afmCacheId && N[this[r].afmCacheId][t] && delete N[this[r].afmCacheId][t]) : (this[r].setAttribute(t, e), this[r].afmCacheId && N[this[r].afmCacheId][t] && delete N[this[r].afmCacheId][t]);
					return this
				},
				removeAttr: function(t) {
					for (var e = function(e) {
						n[r].removeAttribute(e), n[r].afmCacheId && N[n[r].afmCacheId] && delete N[n[r].afmCacheId][t]
					}, n = this, r = 0; r < this.length; r++) t.split(/\s+/g).forEach(e);
					return this
				},
				prop: function(t, e) {
					if (0 === this.length) return e === k ? void 0 : this;
					if (e === k && !F.isObject(t)) {
						var n, r = this[0].afmCacheId && A[this[0].afmCacheId] && A[this[0].afmCacheId][t] ? A[this[0].afmCacheId][t] : !(n = this[0][t]) && t in this[0] ? this[0][t] : n;
						return r
					}
					for (var i = 0; i < this.length; i++)
						if (F.isObject(t))
							for (var o in t) F(this[i]).prop(o, t[o]);
						else F.isArray(e) || F.isObject(e) || F.isFunction(e) ? (this[i].afmCacheId || (this[i].afmCacheId = F.uuid()), A[this[i].afmCacheId] || (A[this[i].afmCacheId] = {}), A[this[i].afmCacheId][t] = e) : null === e && void 0 !== e ? F(this[i]).removeProp(t) : (F(this[i]).removeProp(t), this[i][t] = e);
					return this
				},
				removeProp: function(t) {
					for (var e = function(e) {
						n[r][e] && (n[r][e] = void 0), n[r].afmCacheId && A[n[r].afmCacheId] && delete A[n[r].afmCacheId][t]
					}, n = this, r = 0; r < this.length; r++) t.split(/\s+/g).forEach(e);
					return this
				},
				remove: function(t) {
					var e = F(this).filter(t);
					if (e == k) return this;
					for (var n = 0; n < e.length; n++) F.cleanUpContent(e[n], !0, !0), e[n] && e[n].parentNode && e[n].parentNode.removeChild(e[n]);
					return this
				},
				addClass: function(t) {
					var e = function(t) {
						o.hasClass(t, o[n]) || i.push(t)
					};
					if (t == k) return this;
					for (var n = 0; n < this.length; n++) {
						var r = this[n].className,
							i = [],
							o = this;
						t.split(/\s+/g).forEach(e), this[n].className += (r ? " " : "") + i.join(" "), this[n].className = this[n].className.trim()
					}
					return this
				},
				removeClass: function(t) {
					if (t == k) return this;
					for (var e = function(t) {
						i = i.replace(r(t), " ")
					}, n = 0; n < this.length; n++) {
						if (t == k) return this[n].className = "", this;
						var i = this[n].className;
						"object" == typeof this[n].className && (i = " "), t.split(/\s+/g).forEach(e), this[n].className = i.length > 0 ? i.trim() : ""
					}
					return this
				},
				toggleClass: function(t, e) {
					if (t == k) return this;
					for (var n = 0; n < this.length; n++) "boolean" != typeof e && (e = this.hasClass(t, this[n])), F(this[n])[e ? "removeClass" : "addClass"](t);
					return this
				},
				replaceClass: function(t, e) {
					if (t == k || e == k) return this;
					for (var n = function(t) {
						o = o.replace(r(t), " ")
					}, i = 0; i < this.length; i++)
						if (t != k) {
							var o = this[i].className;
							t.split(/\s+/g).concat(e.split(/\s+/g)).forEach(n), o = o.trim(), this[i].className = o.length > 0 ? (o + " " + e).trim() : e
						} else this[i].className = e;
					return this
				},
				hasClass: function(t, e) {
					return 0 === this.length ? !1 : (e || (e = this[0]), r(t).test(e.className))
				},
				append: function(e, r, i) {
					if (e && e.length != k && 0 === e.length) return this;
					(F.isArray(e) || F.isObject(e)) && (e = F(e));
					var o, s;
					for (r && F(this).add(r), o = 0; o < this.length; o++)
						if (e.length && "string" != typeof e) e = F(e), n(e, this[o], i);
						else {
							var a = j.test(e) ? F(e) : void 0;
							if ((a == k || 0 === a.length) && (a = T.createTextNode(e)), a instanceof I)
								for (var u = 0, c = a.length; c > u; u++) s = a[u], s.nodeName == k || "script" !== s.nodeName.toLowerCase() || s.type && "text/javascript" !== s.type.toLowerCase() ? n(F(s), this[o], i) : t.eval(s.innerHTML);
							else i != k ? this[o].insertBefore(a, this[o].firstChild) : this[o].appendChild(a)
						}
					return this
				},
				appendTo: function(t) {
					var e = F(t);
					return e.append(this), this
				},
				prependTo: function(t) {
					var e = F(t);
					return e.append(this, null, !0), this
				},
				prepend: function(t) {
					return this.append(t, null, 1)
				},
				insertBefore: function(t, e) {
					if (0 === this.length) return this;
					if (t = F(t).get(0), !t) return this;
					for (var n = 0; n < this.length; n++) e ? t.parentNode.insertBefore(this[n], t.nextSibling) : t.parentNode.insertBefore(this[n], t);
					return this
				},
				insertAfter: function(t) {
					this.insertBefore(t, !0)
				},
				get: function(t) {
					if (t = t == k ? null : t, 0 > t && (t += this.length), null === t) {
						for (var e = [], n = 0; n < this.length; n++) e.push(this[n]);
						return e
					}
					return this[t] ? this[t] : void 0
				},
				offset: function() {
					var e;
					return 0 === this.length ? this : this[0] === t ? {
						left: 0,
						top: 0,
						right: 0,
						bottom: 0,
						width: t.innerWidth,
						height: t.innerHeight
					} : (e = this[0].getBoundingClientRect(), {
						left: e.left + t.pageXOffset,
						top: e.top + t.pageYOffset,
						right: e.right + t.pageXOffset,
						bottom: e.bottom + t.pageYOffset,
						width: e.right - e.left,
						height: e.bottom - e.top
					})
				},
				height: function(e) {
					if (0 === this.length) return this;
					if (e != k) return this.css("height", e);
					if (this[0] === this[0].window) return t.innerHeight;
					if (this[0].nodeType === this[0].DOCUMENT_NODE) return this[0].documentElement.offsetHeight;
					var n = this.computedStyle("height").replace("px", "");
					return n ? +n : this.offset().height
				},
				width: function(e) {
					if (0 === this.length) return this;
					if (e != k) return this.css("width", e);
					if (this[0] === this[0].window) return t.innerWidth;
					if (this[0].nodeType === this[0].DOCUMENT_NODE) return this[0].documentElement.offsetWidth;
					var n = this.computedStyle("width").replace("px", "");
					return n ? +n : this.offset().width
				},
				parent: function(t, e) {
					if (0 === this.length) return this;
					for (var n = [], r = 0; r < this.length; r++)
						for (var i = this[r]; i.parentNode && i.parentNode !== T && (n.push(i.parentNode), i.parentNode && (i = i.parentNode), e););
					return this.setupOld(F(o(n)).filter(t))
				},
				parents: function(t) {
					return this.parent(t, !0)
				},
				children: function(t) {
					if (0 === this.length) return this;
					for (var e = [], n = 0; n < this.length; n++) e = e.concat(s(this[n].firstChild));
					return this.setupOld(F(e).filter(t))
				},
				siblings: function(t) {
					if (0 === this.length) return this;
					for (var e = [], n = 0; n < this.length; n++) this[n].parentNode && (e = e.concat(s(this[n].parentNode.firstChild, this[n])));
					return this.setupOld(F(e).filter(t))
				},
				contents: function(t) {
					if (0 === this.length) return this;
					for (var e = [], n = 0; n < this.length; n++) this[n].parentNode && c(this[n].childNodes, e);
					return this.setupOld(F(e).filter(t))
				},
				closest: function(t, e) {
					if (0 === this.length) return this;
					var n = this[0],
						r = F(t, e);
					if (0 === r.length) return F();
					for (; n && -1 === r.indexOf(n);) n = n !== e && n !== T && n.parentNode;
					return F(n)
				},
				filter: function(t) {
					if (0 === this.length) return this;
					if (t == k) return this;
					for (var e = [], n = 0; n < this.length; n++) {
						var r = this[n];
						r.parentNode && F(t, r.parentNode).indexOf(r) >= 0 && e.push(r)
					}
					return this.setupOld(F(o(e)))
				},
				not: function(t) {
					if (0 === this.length) return this;
					for (var e = [], n = 0; n < this.length; n++) {
						var r = this[n];
						r.parentNode && -1 === F(t, r.parentNode).indexOf(r) && e.push(r)
					}
					return this.setupOld(F(o(e)))
				},
				data: function(t, e) {
					return this.attr("data-" + t, e)
				},
				end: function() {
					return this.oldElement != k ? this.oldElement : F()
				},
				clone: function(t) {
					if (t = t === !1 ? !1 : !0, 0 === this.length) return this;
					for (var e = [], n = 0; n < this.length; n++) e.push(this[n].cloneNode(t));
					return F(e)
				},
				size: function() {
					return this.length
				},
				serialize: function() {
					if (0 === this.length) return "";
					for (var t = function(t) {
						var n = t.getAttribute("type");
						if ("fieldset" !== t.nodeName.toLowerCase() && !t.disabled && "submit" !== n && "reset" !== n && "button" !== n && ("radio" !== n && "checkbox" !== n || t.checked) && t.getAttribute("name"))
							if ("select-multiple" === t.type)
								for (var r = 0; r < t.options.length; r++) t.options[r].selected && e.push(t.getAttribute("name") + "=" + encodeURIComponent(t.options[r].value));
							else e.push(t.getAttribute("name") + "=" + encodeURIComponent(t.value))
					}, e = [], n = 0; n < this.length; n++) this.slice.call(this[n].elements).forEach(t);
					return e.join("&")
				},
				eq: function(t) {
					return F(this.get(t))
				},
				index: function(t) {
					return t ? this.indexOf(F(t)[0]) : this.parent().children().indexOf(this[0])
				},
				is: function(t) {
					return !!t && this.filter(t).length > 0
				},
				add: function(t) {
					var e, n = F(t),
						r = n.length;
					for (e = 0; r > e; e++) this[this.length++] = n[e];
					return this
				}
			}, F.ajaxSettings = {
				type: "GET",
				beforeSend: l,
				success: l,
				error: l,
				complete: l,
				context: void 0,
				timeout: 0,
				crossDomain: null,
				processData: !0
			}, F.jsonP = function(e) {
				if (L) return e.type = "get", e.dataType = null, F.get(e);
				var n, r, i = "jsonp_callback" + ++_,
					o = "",
					s = T.createElement("script");
				if (t[i] = function(r) {
					clearTimeout(o), F(s).remove(), delete t[i], e.success.call(n, r)
				}, -1 !== e.url.indexOf("callback=?")) s.src = e.url.replace(/=\?/, "=" + i);
				else {
					if (r = e.jsonp ? e.jsonp : "callback", -1 === e.url.indexOf("?")) e.url += "?" + r + "=" + i;
					else if (-1 !== e.url.indexOf("callback=")) {
						var a = "callback=",
							u = e.url.indexOf(a) + a.length,
							c = e.url.indexOf(u); - 1 === c && (c = e.url.length);
						var l = e.url.substr(u, c);
						e.url = e.url.replace(a + l, a + i), l = l.replace("window.", ""), e.success = t[l]
					} else e.url += "&" + r + "=" + i;
					s.src = e.url
				}
				return e.error && (s.onerror = function() {
					clearTimeout(o), e.error.call(n, "", "error")
				}), F("head").append(s), e.timeout > 0 && (o = setTimeout(function() {
					e.error.call(n, "", "timeout")
				}, e.timeout)), {}
			}, F.ajax = function(e) {
				var n, r = F.Deferred();
				if ("string" == typeof e) {
					var i = e;
					e = {
						url: i
					}
				}
				var o = e || {};
				for (var s in F.ajaxSettings) "undefined" == typeof o[s] && (o[s] = F.ajaxSettings[s]);
				try {
					if (o.url || (o.url = t.location), o.headers || (o.headers = {}), "async" in o && o.async === !1 || (o.async = !0), o.processData && F.isObject(o.data) && (o.data = F.param(o.data)), "get" === o.type.toLowerCase() && o.data && (o.url += -1 === o.url.indexOf("?") ? "?" + o.data : "&" + o.data), o.data && (o.contentType || o.contentType === !1 || (o.contentType = "application/x-www-form-urlencoded; charset=UTF-8")), o.dataType) switch (o.dataType) {
						case "script":
							o.dataType = "text/javascript, application/javascript";
							break;
						case "json":
							o.dataType = "application/json";
							break;
						case "xml":
							o.dataType = "application/xml, text/xml";
							break;
						case "html":
							o.dataType = "text/html";
							break;
						case "text":
							o.dataType = "text/plain";
							break;
						case "jsonp":
							return F.jsonP(e);
						default:
							o.dataType = "text/html"
					} else o.dataType = "text/html";
					if (/=\?/.test(o.url)) return F.jsonP(o);
					null === o.crossDomain && (o.crossDomain = /^([\w-]+:)?\/\/([^\/]+)/.test(o.url) && RegExp.$2 !== t.location.host), o.crossDomain || (o.headers = F.extend({
						"X-Requested-With": "XMLHttpRequest"
					}, o.headers));
					var a, u = o.context,
						c = /^([\w-]+:)\/\//.test(o.url) ? RegExp.$1 : t.location.protocol;
					n = new t.XMLHttpRequest, F.extend(n, r.promise), n.onreadystatechange = function() {
						var e = o.dataType;
						if (4 === n.readyState) {
							clearTimeout(a);
							var i, s = !1,
								l = n.getResponseHeader("content-type") || "";
							if (n.status >= 200 && n.status < 300 || 0 === n.status && "file:" === c) {
								if ("application/json" === l || "application/json" === e && !/^\s*$/.test(n.responseText)) try {
									i = JSON.parse(n.responseText)
								} catch (f) {
									s = f
								} else if (-1 !== l.indexOf("javascript")) try {
									i = n.responseText, t.eval(i)
								} catch (f) {
									// console.log(f)
								} else "application/xml, text/xml" === e ? i = n.responseXML : "text/html" === e ? (i = n.responseText, F.parseJS(i)) : i = n.responseText;
								0 === n.status && 0 === i.length && (s = !0), s ? (o.error.call(u, n, "parsererror", s), r.reject.call(u, n, "parsererror", s)) : (r.resolve.call(u, i, "success", n), o.success.call(u, i, "success", n))
							} else s = !0, r.reject.call(u, n, "error"), o.error.call(u, n, "error");
							var h = s ? "error" : "success";
							o.complete.call(u, n, h)
						}
					}, n.open(o.type, o.url, o.async), o.withCredentials && (n.withCredentials = !0), o.contentType && (o.headers["Content-Type"] = o.contentType);
					for (var f in o.headers) "string" == typeof o.headers[f] && n.setRequestHeader(f, o.headers[f]);
					if (o.beforeSend.call(u, n, o) === !1) return n.abort(), !1;
					o.timeout > 0 && (a = setTimeout(function() {
						n.onreadystatechange = l, n.abort(), o.error.call(u, n, "timeout")
					}, o.timeout)), n.send(o.data)
				} catch (h) {
					r.resolve(u, n, "error", h), o.error.call(u, n, "error", h)
				}
				return n
			}, F.get = function(t, e) {
				return this.ajax({
					url: t,
					success: e
				})
			}, F.post = function(t, e, n, r) {
				return "function" == typeof e && (n = e, e = {}), r === k && (r = "html"), this.ajax({
					url: t,
					type: "POST",
					data: e,
					dataType: r,
					success: n
				})
			}, F.getJSON = function(t, e, n) {
				return "function" == typeof e && (n = e, e = {}), this.ajax({
					url: t,
					data: e,
					success: n,
					dataType: "json"
				})
			}, F.getScript = function(t, e) {
				var n = /^([\w-]+:)?\/\/([^\/]+)/.test(t);
				if (n) {
					var r = F.Deferred(),
						i = F.create("script", {
							async: !0,
							src: t
						}).get(0);
					return i.onload = function() {
						e && e(), r.resolve.call(this, "success"), F(this).remove()
					}, i.onerror = function() {
						F(this).remove(), r.reject.call(this, "success")
					}, T.head.appendChild(i), r.promise
				}
				return this.ajax({
					url: t,
					success: e,
					dataType: "script"
				})
			}, F.param = function(t, e) {
				var n = [];
				if (t instanceof I) t.each(function() {
					var t = e ? e + "[" + this.id + "]" : this.id,
						r = this.value;
					n.push(t + "=" + encodeURIComponent(r))
				});
				else
					for (var r in t)
						if (!F.isFunction(t[r])) {
							var i = e ? e + "[" + r + "]" : r,
								o = t[r];
							n.push(F.isObject(o) ? F.param(o, i) : i + "=" + encodeURIComponent(o))
						} return n.join("&")
			}, F.parseJSON = function(t) {
				return JSON.parse(t)
			}, F.parseXML = function(t) {
				return L ? void MSApp.execUnsafeLocalFunction(function() {
					return (new DOMParser).parseFromString(t, "text/xml")
				}) : (new DOMParser).parseFromString(t, "text/xml")
			}, f(F, navigator.userAgent), F.__detectUA = f, F.uuid = function() {
				var t = function() {
					return (65536 * (1 + Math.random()) | 0).toString(16).substring(1)
				};
				return t() + t() + "-" + t() + "-" + t() + "-" + t() + "-" + t() + t() + t()
			}, F.getCssMatrix = function(e) {
				F.is$(e) && (e = e.get(0));
				var n = t.WebKitCSSMatrix || t.MSCSSMatrix;
				if (e === k) return n ? new n : {
					a: 0,
					b: 0,
					c: 0,
					d: 0,
					e: 0,
					f: 0
				};
				var r = t.getComputedStyle(e),
					i = r.webkitTransform || r.transform || r[F.feat.cssPrefix + "Transform"];
				if (n) return new n(i);
				if (i) {
					var o = i.replace(/[^0-9\-.,]/g, "").split(",");
					return {
						a: +o[0],
						b: +o[1],
						c: +o[2],
						d: +o[3],
						e: +o[4],
						f: +o[5]
					}
				}
				return {
					a: 0,
					b: 0,
					c: 0,
					d: 0,
					e: 0,
					f: 0
				}
			}, F.create = function(t, e) {
				var n, r = new I;
				if (e || "<" !== t[0]) {
					e.html && (e.innerHTML = e.html, delete e.html), n = T.createElement(t);
					for (var i in e) n[i] = e[i];
					r[r.length++] = n
				} else n = T.createElement("div"), L ? MSApp.execUnsafeLocalFunction(function() {
					n.innerHTML = t.trim()
				}) : n.innerHTML = t, c(n.childNodes, r);
				return r
			}, F.query = function(t, e) {
				if (!t) return new I;
				e = e || T;
				var n = new I;
				return n.selector(t, e)
			};
			var D = [],
				P = 1;
			F.event = {
				add: m,
				remove: y
			}, F.fn.bind = function(t, e) {
				for (var n = 0, r = this.length; r > n; n++) m(this[n], t, e);
				return this
			}, F.fn.unbind = function(t, e) {
				for (var n = 0, r = this.length; r > n; n++) y(this[n], t, e);
				return this
			}, F.fn.one = function(t, e) {
				return this.each(function(n, r) {
					m(this, t, e, null, function(t, e) {
						return function() {
							y(r, e, t);
							var n = t.apply(r, arguments);
							return n
						}
					})
				})
			};
			var B = function() {
				return !0
			}, R = function() {
					return !1
				}, H = {
					preventDefault: "isDefaultPrevented",
					stopImmediatePropagation: "isImmediatePropagationStopped",
					stopPropagation: "isPropagationStopped"
				};
			F.fn.delegate = function(t, e, n, r) {
				F.isFunction(n) && (r = n, n = null);
				for (var i = 0, o = this.length; o > i; i++) x(this[i], e, r, t, n);
				return this
			}, F.fn.undelegate = function(t, e, n) {
				for (var r = 0, i = this.length; i > r; r++) y(this[r], e, n, t);
				return this
			}, F.fn.on = function(t, e, n, r) {
				return F.isFunction(n) && (r = n, n = null), e === k || F.isFunction(e) ? this.bind(t, e) : this.delegate(e, t, n, r)
			}, F.fn.off = function(t, e, n) {
				return e === k || F.isFunction(e) ? this.unbind(t, e) : this.undelegate(e, t, n)
			}, F.fn.trigger = function(t, e, n) {
				"string" == typeof t && (n = n || {}, t = d(t), n.ns = t.ns, t = F.Event(t.e, n)), t.data = e;
				for (var r = 0, i = this.length; i > r; r++) this[r].dispatchEvent(t);
				return this
			}, F.Event = function(t, e) {
				var n = T.createEvent("Events"),
					r = !0;
				if (e)
					for (var i in e) "bubbles" === i ? r = !! e[i] : n[i] = e[i];
				return n.initEvent(t, r, !0, null, null, null, null, null, null, null, null, null, null, null, null), n
			}, F.bind = function(t, e, n) {
				if (t) {
					t.__events || (t.__events = {}), F.isArray(e) || (e = [e]);
					for (var r = 0; r < e.length; r++) t.__events[e[r]] || (t.__events[e[r]] = []), t.__events[e[r]].push(n)
				}
			}, F.trigger = function(t, e, n) {
				if (t) {
					var r = !0;
					if (!t.__events) return r;
					F.isArray(e) || (e = [e]), F.isArray(n) || (n = []);
					for (var i = 0; i < e.length; i++)
						if (t.__events[e[i]])
							for (var o = t.__events[e[i]].slice(0), s = 0; s < o.length; s++) F.isFunction(o[s]) && o[s].apply(t, n) === !1 && (r = !1);
					return r
				}
			}, F.unbind = function(t, e, n) {
				if (t.__events) {
					if (e == k) return void delete t.__events;
					F.isArray(e) || (e = [e]);
					for (var r = 0; r < e.length; r++)
						if (t.__events[e[r]])
							for (var i = t.__events[e[r]], o = 0; o < i.length; o++)
								if (n == k && delete i[o], i[o] === n) {
									i.splice(o, 1);
									break
								}
				}
			}, F.proxy = function(t, e, n) {
				return function() {
					return n ? t.apply(e, n) : t.apply(e, arguments)
				}
			};
			var q = function(t, e) {
				for (var n = 0; n < t.length; n++) C(t[n], e)
			};
			F.cleanUpContent = function(t, e, n) {
				if (t) {
					var r = t.childNodes;
					r && r.length > 0 && F.asap(q, {}, [O.apply(r, [0]), n]), e && E(t, n)
				}
			};
			var U = [],
				X = [],
				$ = [];
			F.asap = function(e, n, r) {
				if (!F.isFunction(e)) throw "$.asap - argument is not a valid function";
				U.push(e), X.push(n ? n : {}), $.push(r ? r : []), t.postMessage("afm-asap", "*")
			}, t.addEventListener("message", function(e) {
				e.source === t && "afm-asap" === e.data && (e.stopPropagation(), U.length > 0 && U.shift().apply(X.shift(), $.shift()))
			}, !0);
			var z = {};
			return F.parseJS = function(e) {
				if (e) {
					if ("string" == typeof e) {
						var n = T.createElement("div");
						L ? MSApp.execUnsafeLocalFunction(function() {
							n.innerHTML = e
						}) : n.innerHTML = e, e = n
					}
					var r = e.getElementsByTagName("script");
					e = null;
					for (var i = 0; i < r.length; i++)
						if (r[i].src.length > 0 && !z[r[i].src] && !L) {
							var o = T.createElement("script");
							o.type = r[i].type, o.src = r[i].src, T.getElementsByTagName("head")[0].appendChild(o), z[r[i].src] = 1, o = null
						} else t.eval(r[i].innerHTML)
				}
			}, ["click", "keydown", "keyup", "keypress", "submit", "load", "resize", "change", "select", "error"].forEach(function(t) {
				F.fn[t] = function(e) {
					return e ? this.bind(t, e) : this.trigger(t)
				}
			}), ["focus", "blur"].forEach(function(t) {
				F.fn[t] = function(e) {
					if (0 !== this.length) {
						if (e) this.bind(t, e);
						else
							for (var n = 0; n < this.length; n++) try {
								this[n][t]()
							} catch (r) {}
						return this
					}
				}
			}), F.Deferred = function() {
				return {
					reject: function() {},
					resolve: function() {},
					promise: {
						then: function() {},
						fail: function() {}
					}
				}
			}, F
		}(e);
		e.jq = i, "$" in e || (e.$ = i), "function" == typeof t && t.amd ? t("appframework", [], function() {
			"use strict";
			return i
		}) : "undefined" != typeof module && module.exports && (module.exports.af = i, module.exports.$ = i)
	}
	var o = navigator.userAgent.indexOf("Android") > 0,
		s = /iP(ad|hone|od)/.test(navigator.userAgent),
		a = s && /OS 4_\d(_\d)?/.test(navigator.userAgent),
		u = s && /OS ([6-9]|\d{2})_\d/.test(navigator.userAgent);
	n.prototype.needsClick = function(t) {
		"use strict";
		switch (t.nodeName.toLowerCase()) {
			case "button":
			case "select":
			case "textarea":
				if (t.disabled) return !0;
				break;
			case "input":
				if (s && "file" === t.type || t.disabled) return !0;
				break;
			case "label":
			case "video":
				return !0
		}
		return /\bneedsclick\b/.test(t.className)
	}, n.prototype.needsFocus = function(t) {
		"use strict";
		switch (t.nodeName.toLowerCase()) {
			case "textarea":
				return !0;
			case "select":
				return !o;
			case "input":
				switch (t.type) {
					case "button":
					case "checkbox":
					case "file":
					case "image":
					case "radio":
					case "submit":
						return !1
				}
				return !t.disabled && !t.readOnly;
			default:
				return /\bneedsfocus\b/.test(t.className)
		}
	}, n.prototype.sendClick = function(t, n) {
		"use strict";
		var r, i;
		document.activeElement && document.activeElement !== t && document.activeElement.blur(), i = n.changedTouches[0], r = document.createEvent("MouseEvents"), r.initMouseEvent(this.determineEventType(t), !0, !0, e, 1, i.screenX, i.screenY, i.clientX, i.clientY, !1, !1, !1, !1, 0, null), r.forwardedTouchEvent = !0, t.dispatchEvent(r)
	}, n.prototype.determineEventType = function(t) {
		"use strict";
		return o && "select" === t.tagName.toLowerCase() ? "mousedown" : "click"
	}, n.prototype.focus = function(t) {
		"use strict";
		var e;
		s && t.setSelectionRange && 0 !== t.type.indexOf("date") && "time" !== t.type ? (e = t.value.length, t.setSelectionRange(e, e)) : t.focus()
	}, n.prototype.updateScrollParent = function(t) {
		"use strict";
		var e, n;
		if (e = t.fastClickScrollParent, !e || !e.contains(t)) {
			n = t;
			do {
				if (n.scrollHeight > n.offsetHeight) {
					e = n, t.fastClickScrollParent = n;
					break
				}
				n = n.parentElement
			} while (n)
		}
		e && (e.fastClickLastScrollTop = e.scrollTop)
	}, n.prototype.getTargetElementFromEventTarget = function(t) {
		"use strict";
		return t.nodeType === Node.TEXT_NODE ? t.parentNode : t
	}, n.prototype.onTouchStart = function(t) {
		"use strict";
		var n, r, i;
		if (t.targetTouches.length > 1) return !0;
		if (n = this.getTargetElementFromEventTarget(t.target), r = t.targetTouches[0], s) {
			if (i = e.getSelection(), i.rangeCount && !i.isCollapsed) return !0;
			if (!a) {
				if (r.identifier === this.lastTouchIdentifier) return t.preventDefault(), !1;
				this.lastTouchIdentifier = r.identifier, this.updateScrollParent(n)
			}
		}
		return this.trackingClick = !0, this.trackingClickStart = t.timeStamp, this.targetElement = n, this.touchStartX = r.pageX, this.touchStartY = r.pageY, t.timeStamp - this.lastClickTime < this.tapDelay && t.preventDefault(), !0
	}, n.prototype.touchHasMoved = function(t) {
		"use strict";
		var e = t.changedTouches[0],
			n = this.touchBoundary;
		return Math.abs(e.pageX - this.touchStartX) > n || Math.abs(e.pageY - this.touchStartY) > n ? !0 : !1
	}, n.prototype.onTouchMove = function(t) {
		"use strict";
		return this.trackingClick ? ((this.targetElement !== this.getTargetElementFromEventTarget(t.target) || this.touchHasMoved(t)) && (this.trackingClick = !1, this.targetElement = null), !0) : !0
	}, n.prototype.findControl = function(t) {
		"use strict";
		return void 0 !== t.control ? t.control : t.htmlFor ? document.getElementById(t.htmlFor) : t.querySelector("button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea")
	}, n.prototype.onTouchEnd = function(t) {
		"use strict";
		var n, r, i, c, l, f = this.targetElement;
		if (!this.trackingClick) return !0;
		if (t.timeStamp - this.lastClickTime < this.tapDelay) return this.cancelNextClick = !0, !0;
		if (this.cancelNextClick = !1, this.lastClickTime = t.timeStamp, r = this.trackingClickStart, this.trackingClick = !1, this.trackingClickStart = 0, u && (l = t.changedTouches[0], f = document.elementFromPoint(l.pageX - e.pageXOffset, l.pageY - e.pageYOffset) || f, f.fastClickScrollParent = this.targetElement.fastClickScrollParent), i = f.tagName.toLowerCase(), "label" === i) {
			if (n = this.findControl(f)) {
				if (this.focus(f), o) return !1;
				f = n
			}
		} else if (this.needsFocus(f)) return t.timeStamp - r > 100 || s && e.top !== e && "input" === i ? (this.targetElement = null, !1) : (this.focus(f), this.sendClick(f, t), s && "select" === i || (this.targetElement = null, t.preventDefault()), !1);
		return s && !a && (c = f.fastClickScrollParent, c && c.fastClickLastScrollTop !== c.scrollTop) ? !0 : (this.needsClick(f) || (t.preventDefault(), this.sendClick(f, t)), !1)
	}, n.prototype.onTouchCancel = function() {
		"use strict";
		this.trackingClick = !1, this.targetElement = null
	}, n.prototype.onMouse = function(t) {
		"use strict";
		return this.targetElement ? t.forwardedTouchEvent ? !0 : t.cancelable && (!this.needsClick(this.targetElement) || this.cancelNextClick) ? (t.stopImmediatePropagation ? t.stopImmediatePropagation() : t.propagationStopped = !0, t.stopPropagation(), t.preventDefault(), !1) : !0 : !0
	}, n.prototype.onClick = function(t) {
		"use strict";
		var e;
		return this.trackingClick ? (this.targetElement = null, this.trackingClick = !1, !0) : "submit" === t.target.type && 0 === t.detail ? !0 : (e = this.onMouse(t), e || (this.targetElement = null), e)
	}, n.prototype.destroy = function() {
		"use strict";
		var t = this.layer;
		o && (t.removeEventListener("mouseover", this.onMouse, !0), t.removeEventListener("mousedown", this.onMouse, !0), t.removeEventListener("mouseup", this.onMouse, !0)), t.removeEventListener("click", this.onClick, !0), t.removeEventListener("touchstart", this.onTouchStart, !1), t.removeEventListener("touchmove", this.onTouchMove, !1), t.removeEventListener("touchend", this.onTouchEnd, !1), t.removeEventListener("touchcancel", this.onTouchCancel, !1)
	}, n.notNeeded = function(t) {
		"use strict";
		var n, r;
		if ("undefined" == typeof e.ontouchstart) return !0;
		if (r = +(/Chrome\/([0-9]+)/.exec(navigator.userAgent) || [, 0])[1]) {
			if (!o) return !0;
			if (n = document.querySelector("meta[name=viewport]")) {
				if (-1 !== n.content.indexOf("user-scalable=no")) return !0;
				if (r > 31 && document.documentElement.scrollWidth <= e.outerWidth) return !0
			}
		}
		return "none" === t.style.msTouchAction ? !0 : !1
	}, n.attach = function(t, e) {
		"use strict";
		return new n(t, e)
	}, "undefined" != typeof t && t("libs/fastclick", function() {
		"use strict";
		return n
	}),
	function() {
		var e = this,
			n = e._,
			r = {}, i = Array.prototype,
			o = Object.prototype,
			s = Function.prototype,
			a = i.push,
			u = i.slice,
			c = i.concat,
			l = o.toString,
			f = o.hasOwnProperty,
			h = i.forEach,
			p = i.map,
			d = i.reduce,
			g = i.reduceRight,
			v = i.filter,
			m = i.every,
			y = i.some,
			b = i.indexOf,
			x = i.lastIndexOf,
			E = Array.isArray,
			C = Object.keys,
			k = s.bind,
			T = function(t) {
				return t instanceof T ? t : this instanceof T ? void(this._wrapped = t) : new T(t)
			};
		"undefined" != typeof exports ? ("undefined" != typeof module && module.exports && (exports = module.exports = T), exports._ = T) : e._ = T, T.VERSION = "1.6.0";
		var w = T.each = T.forEach = function(t, e, n) {
			if (null == t) return t;
			if (h && t.forEach === h) t.forEach(e, n);
			else if (t.length === +t.length) {
				for (var i = 0, o = t.length; o > i; i++)
					if (e.call(n, t[i], i, t) === r) return
			} else
				for (var s = T.keys(t), i = 0, o = s.length; o > i; i++)
					if (e.call(n, t[s[i]], s[i], t) === r) return; return t
		};
		T.map = T.collect = function(t, e, n) {
			var r = [];
			return null == t ? r : p && t.map === p ? t.map(e, n) : (w(t, function(t, i, o) {
				r.push(e.call(n, t, i, o))
			}), r)
		};
		var O = "Reduce of empty array with no initial value";
		T.reduce = T.foldl = T.inject = function(t, e, n, r) {
			var i = arguments.length > 2;
			if (null == t && (t = []), d && t.reduce === d) return r && (e = T.bind(e, r)), i ? t.reduce(e, n) : t.reduce(e);
			if (w(t, function(t, o, s) {
				i ? n = e.call(r, n, t, o, s) : (n = t, i = !0)
			}), !i) throw new TypeError(O);
			return n
		}, T.reduceRight = T.foldr = function(t, e, n, r) {
			var i = arguments.length > 2;
			if (null == t && (t = []), g && t.reduceRight === g) return r && (e = T.bind(e, r)), i ? t.reduceRight(e, n) : t.reduceRight(e);
			var o = t.length;
			if (o !== +o) {
				var s = T.keys(t);
				o = s.length
			}
			if (w(t, function(a, u, c) {
				u = s ? s[--o] : --o, i ? n = e.call(r, n, t[u], u, c) : (n = t[u], i = !0)
			}), !i) throw new TypeError(O);
			return n
		}, T.find = T.detect = function(t, e, n) {
			var r;
			return S(t, function(t, i, o) {
				return e.call(n, t, i, o) ? (r = t, !0) : void 0
			}), r
		}, T.filter = T.select = function(t, e, n) {
			var r = [];
			return null == t ? r : v && t.filter === v ? t.filter(e, n) : (w(t, function(t, i, o) {
				e.call(n, t, i, o) && r.push(t)
			}), r)
		}, T.reject = function(t, e, n) {
			return T.filter(t, function(t, r, i) {
				return !e.call(n, t, r, i)
			}, n)
		}, T.every = T.all = function(t, e, n) {
			e || (e = T.identity);
			var i = !0;
			return null == t ? i : m && t.every === m ? t.every(e, n) : (w(t, function(t, o, s) {
				return (i = i && e.call(n, t, o, s)) ? void 0 : r
			}), !! i)
		};
		var S = T.some = T.any = function(t, e, n) {
			e || (e = T.identity);
			var i = !1;
			return null == t ? i : y && t.some === y ? t.some(e, n) : (w(t, function(t, o, s) {
				return i || (i = e.call(n, t, o, s)) ? r : void 0
			}), !! i)
		};
		T.contains = T.include = function(t, e) {
			return null == t ? !1 : b && t.indexOf === b ? -1 != t.indexOf(e) : S(t, function(t) {
				return t === e
			})
		}, T.invoke = function(t, e) {
			var n = u.call(arguments, 2),
				r = T.isFunction(e);
			return T.map(t, function(t) {
				return (r ? e : t[e]).apply(t, n)
			})
		}, T.pluck = function(t, e) {
			return T.map(t, T.property(e))
		}, T.where = function(t, e) {
			return T.filter(t, T.matches(e))
		}, T.findWhere = function(t, e) {
			return T.find(t, T.matches(e))
		}, T.max = function(t, e, n) {
			if (!e && T.isArray(t) && t[0] === +t[0] && t.length < 65535) return Math.max.apply(Math, t);
			var r = -1 / 0,
				i = -1 / 0;
			return w(t, function(t, o, s) {
				var a = e ? e.call(n, t, o, s) : t;
				a > i && (r = t, i = a)
			}), r
		}, T.min = function(t, e, n) {
			if (!e && T.isArray(t) && t[0] === +t[0] && t.length < 65535) return Math.min.apply(Math, t);
			var r = 1 / 0,
				i = 1 / 0;
			return w(t, function(t, o, s) {
				var a = e ? e.call(n, t, o, s) : t;
				i > a && (r = t, i = a)
			}), r
		}, T.shuffle = function(t) {
			var e, n = 0,
				r = [];
			return w(t, function(t) {
				e = T.random(n++), r[n - 1] = r[e], r[e] = t
			}), r
		}, T.sample = function(t, e, n) {
			return null == e || n ? (t.length !== +t.length && (t = T.values(t)), t[T.random(t.length - 1)]) : T.shuffle(t).slice(0, Math.max(0, e))
		};
		var _ = function(t) {
			return null == t ? T.identity : T.isFunction(t) ? t : T.property(t)
		};
		T.sortBy = function(t, e, n) {
			return e = _(e), T.pluck(T.map(t, function(t, r, i) {
				return {
					value: t,
					index: r,
					criteria: e.call(n, t, r, i)
				}
			}).sort(function(t, e) {
				var n = t.criteria,
					r = e.criteria;
				if (n !== r) {
					if (n > r || void 0 === n) return 1;
					if (r > n || void 0 === r) return -1
				}
				return t.index - e.index
			}), "value")
		};
		var j = function(t) {
			return function(e, n, r) {
				var i = {};
				return n = _(n), w(e, function(o, s) {
					var a = n.call(r, o, s, e);
					t(i, a, o)
				}), i
			}
		};
		T.groupBy = j(function(t, e, n) {
			T.has(t, e) ? t[e].push(n) : t[e] = [n]
		}), T.indexBy = j(function(t, e, n) {
			t[e] = n
		}), T.countBy = j(function(t, e) {
			T.has(t, e) ? t[e]++ : t[e] = 1
		}), T.sortedIndex = function(t, e, n, r) {
			n = _(n);
			for (var i = n.call(r, e), o = 0, s = t.length; s > o;) {
				var a = o + s >>> 1;
				n.call(r, t[a]) < i ? o = a + 1 : s = a
			}
			return o
		}, T.toArray = function(t) {
			return t ? T.isArray(t) ? u.call(t) : t.length === +t.length ? T.map(t, T.identity) : T.values(t) : []
		}, T.size = function(t) {
			return null == t ? 0 : t.length === +t.length ? t.length : T.keys(t).length
		}, T.first = T.head = T.take = function(t, e, n) {
			return null == t ? void 0 : null == e || n ? t[0] : 0 > e ? [] : u.call(t, 0, e)
		}, T.initial = function(t, e, n) {
			return u.call(t, 0, t.length - (null == e || n ? 1 : e))
		}, T.last = function(t, e, n) {
			return null == t ? void 0 : null == e || n ? t[t.length - 1] : u.call(t, Math.max(t.length - e, 0))
		}, T.rest = T.tail = T.drop = function(t, e, n) {
			return u.call(t, null == e || n ? 1 : e)
		}, T.compact = function(t) {
			return T.filter(t, T.identity)
		};
		var N = function(t, e, n) {
			return e && T.every(t, T.isArray) ? c.apply(n, t) : (w(t, function(t) {
				T.isArray(t) || T.isArguments(t) ? e ? a.apply(n, t) : N(t, e, n) : n.push(t)
			}), n)
		};
		T.flatten = function(t, e) {
			return N(t, e, [])
		}, T.without = function(t) {
			return T.difference(t, u.call(arguments, 1))
		}, T.partition = function(t, e) {
			var n = [],
				r = [];
			return w(t, function(t) {
				(e(t) ? n : r).push(t)
			}), [n, r]
		}, T.uniq = T.unique = function(t, e, n, r) {
			T.isFunction(e) && (r = n, n = e, e = !1);
			var i = n ? T.map(t, n, r) : t,
				o = [],
				s = [];
			return w(i, function(n, r) {
				(e ? r && s[s.length - 1] === n : T.contains(s, n)) || (s.push(n), o.push(t[r]))
			}), o
		}, T.union = function() {
			return T.uniq(T.flatten(arguments, !0))
		}, T.intersection = function(t) {
			var e = u.call(arguments, 1);
			return T.filter(T.uniq(t), function(t) {
				return T.every(e, function(e) {
					return T.contains(e, t)
				})
			})
		}, T.difference = function(t) {
			var e = c.apply(i, u.call(arguments, 1));
			return T.filter(t, function(t) {
				return !T.contains(e, t)
			})
		}, T.zip = function() {
			for (var t = T.max(T.pluck(arguments, "length").concat(0)), e = new Array(t), n = 0; t > n; n++) e[n] = T.pluck(arguments, "" + n);
			return e
		}, T.object = function(t, e) {
			if (null == t) return {};
			for (var n = {}, r = 0, i = t.length; i > r; r++) e ? n[t[r]] = e[r] : n[t[r][0]] = t[r][1];
			return n
		}, T.indexOf = function(t, e, n) {
			if (null == t) return -1;
			var r = 0,
				i = t.length;
			if (n) {
				if ("number" != typeof n) return r = T.sortedIndex(t, e), t[r] === e ? r : -1;
				r = 0 > n ? Math.max(0, i + n) : n
			}
			if (b && t.indexOf === b) return t.indexOf(e, n);
			for (; i > r; r++)
				if (t[r] === e) return r;
			return -1
		}, T.lastIndexOf = function(t, e, n) {
			if (null == t) return -1;
			var r = null != n;
			if (x && t.lastIndexOf === x) return r ? t.lastIndexOf(e, n) : t.lastIndexOf(e);
			for (var i = r ? n : t.length; i--;)
				if (t[i] === e) return i;
			return -1
		}, T.range = function(t, e, n) {
			arguments.length <= 1 && (e = t || 0, t = 0), n = arguments[2] || 1;
			for (var r = Math.max(Math.ceil((e - t) / n), 0), i = 0, o = new Array(r); r > i;) o[i++] = t, t += n;
			return o
		};
		var A = function() {};
		T.bind = function(t, e) {
			var n, r;
			if (k && t.bind === k) return k.apply(t, u.call(arguments, 1));
			if (!T.isFunction(t)) throw new TypeError;
			return n = u.call(arguments, 2), r = function() {
				if (!(this instanceof r)) return t.apply(e, n.concat(u.call(arguments)));
				A.prototype = t.prototype;
				var i = new A;
				A.prototype = null;
				var o = t.apply(i, n.concat(u.call(arguments)));
				return Object(o) === o ? o : i
			}
		}, T.partial = function(t) {
			var e = u.call(arguments, 1);
			return function() {
				for (var n = 0, r = e.slice(), i = 0, o = r.length; o > i; i++) r[i] === T && (r[i] = arguments[n++]);
				for (; n < arguments.length;) r.push(arguments[n++]);
				return t.apply(this, r)
			}
		}, T.bindAll = function(t) {
			var e = u.call(arguments, 1);
			if (0 === e.length) throw new Error("bindAll must be passed function names");
			return w(e, function(e) {
				t[e] = T.bind(t[e], t)
			}), t
		}, T.memoize = function(t, e) {
			var n = {};
			return e || (e = T.identity),
			function() {
				var r = e.apply(this, arguments);
				return T.has(n, r) ? n[r] : n[r] = t.apply(this, arguments)
			}
		}, T.delay = function(t, e) {
			var n = u.call(arguments, 2);
			return setTimeout(function() {
				return t.apply(null, n)
			}, e)
		}, T.defer = function(t) {
			return T.delay.apply(T, [t, 1].concat(u.call(arguments, 1)))
		}, T.throttle = function(t, e, n) {
			var r, i, o, s = null,
				a = 0;
			n || (n = {});
			var u = function() {
				a = n.leading === !1 ? 0 : T.now(), s = null, o = t.apply(r, i), r = i = null
			};
			return function() {
				var c = T.now();
				a || n.leading !== !1 || (a = c);
				var l = e - (c - a);
				return r = this, i = arguments, 0 >= l ? (clearTimeout(s), s = null, a = c, o = t.apply(r, i), r = i = null) : s || n.trailing === !1 || (s = setTimeout(u, l)), o
			}
		}, T.debounce = function(t, e, n) {
			var r, i, o, s, a, u = function() {
					var c = T.now() - s;
					e > c ? r = setTimeout(u, e - c) : (r = null, n || (a = t.apply(o, i), o = i = null))
				};
			return function() {
				o = this, i = arguments, s = T.now();
				var c = n && !r;
				return r || (r = setTimeout(u, e)), c && (a = t.apply(o, i), o = i = null), a
			}
		}, T.once = function(t) {
			var e, n = !1;
			return function() {
				return n ? e : (n = !0, e = t.apply(this, arguments), t = null, e)
			}
		}, T.wrap = function(t, e) {
			return T.partial(e, t)
		}, T.compose = function() {
			var t = arguments;
			return function() {
				for (var e = arguments, n = t.length - 1; n >= 0; n--) e = [t[n].apply(this, e)];
				return e[0]
			}
		}, T.after = function(t, e) {
			return function() {
				return --t < 1 ? e.apply(this, arguments) : void 0
			}
		}, T.keys = function(t) {
			if (!T.isObject(t)) return [];
			if (C) return C(t);
			var e = [];
			for (var n in t) T.has(t, n) && e.push(n);
			return e
		}, T.values = function(t) {
			for (var e = T.keys(t), n = e.length, r = new Array(n), i = 0; n > i; i++) r[i] = t[e[i]];
			return r
		}, T.pairs = function(t) {
			for (var e = T.keys(t), n = e.length, r = new Array(n), i = 0; n > i; i++) r[i] = [e[i], t[e[i]]];
			return r
		}, T.invert = function(t) {
			for (var e = {}, n = T.keys(t), r = 0, i = n.length; i > r; r++) e[t[n[r]]] = n[r];
			return e
		}, T.functions = T.methods = function(t) {
			var e = [];
			for (var n in t) T.isFunction(t[n]) && e.push(n);
			return e.sort()
		}, T.extend = function(t) {
			return w(u.call(arguments, 1), function(e) {
				if (e)
					for (var n in e) t[n] = e[n]
			}), t
		}, T.pick = function(t) {
			var e = {}, n = c.apply(i, u.call(arguments, 1));
			return w(n, function(n) {
				n in t && (e[n] = t[n])
			}), e
		}, T.omit = function(t) {
			var e = {}, n = c.apply(i, u.call(arguments, 1));
			for (var r in t) T.contains(n, r) || (e[r] = t[r]);
			return e
		}, T.defaults = function(t) {
			return w(u.call(arguments, 1), function(e) {
				if (e)
					for (var n in e) void 0 === t[n] && (t[n] = e[n])
			}), t
		}, T.clone = function(t) {
			return T.isObject(t) ? T.isArray(t) ? t.slice() : T.extend({}, t) : t
		}, T.tap = function(t, e) {
			return e(t), t
		};
		var M = function(t, e, n, r) {
			if (t === e) return 0 !== t || 1 / t == 1 / e;
			if (null == t || null == e) return t === e;
			t instanceof T && (t = t._wrapped), e instanceof T && (e = e._wrapped);
			var i = l.call(t);
			if (i != l.call(e)) return !1;
			switch (i) {
				case "[object String]":
					return t == String(e);
				case "[object Number]":
					return t != +t ? e != +e : 0 == t ? 1 / t == 1 / e : t == +e;
				case "[object Date]":
				case "[object Boolean]":
					return +t == +e;
				case "[object RegExp]":
					return t.source == e.source && t.global == e.global && t.multiline == e.multiline && t.ignoreCase == e.ignoreCase
			}
			if ("object" != typeof t || "object" != typeof e) return !1;
			for (var o = n.length; o--;)
				if (n[o] == t) return r[o] == e;
			var s = t.constructor,
				a = e.constructor;
			if (s !== a && !(T.isFunction(s) && s instanceof s && T.isFunction(a) && a instanceof a) && "constructor" in t && "constructor" in e) return !1;
			n.push(t), r.push(e);
			var u = 0,
				c = !0;
			if ("[object Array]" == i) {
				if (u = t.length, c = u == e.length)
					for (; u-- && (c = M(t[u], e[u], n, r)););
			} else {
				for (var f in t)
					if (T.has(t, f) && (u++, !(c = T.has(e, f) && M(t[f], e[f], n, r)))) break;
				if (c) {
					for (f in e)
						if (T.has(e, f) && !u--) break;
					c = !u
				}
			}
			return n.pop(), r.pop(), c
		};
		T.isEqual = function(t, e) {
			return M(t, e, [], [])
		}, T.isEmpty = function(t) {
			if (null == t) return !0;
			if (T.isArray(t) || T.isString(t)) return 0 === t.length;
			for (var e in t)
				if (T.has(t, e)) return !1;
			return !0
		}, T.isElement = function(t) {
			return !(!t || 1 !== t.nodeType)
		}, T.isArray = E || function(t) {
			return "[object Array]" == l.call(t)
		}, T.isObject = function(t) {
			return t === Object(t)
		}, w(["Arguments", "Function", "String", "Number", "Date", "RegExp"], function(t) {
			T["is" + t] = function(e) {
				return l.call(e) == "[object " + t + "]"
			}
		}), T.isArguments(arguments) || (T.isArguments = function(t) {
			return !(!t || !T.has(t, "callee"))
		}), "function" != typeof / . / && (T.isFunction = function(t) {
			return "function" == typeof t
		}), T.isFinite = function(t) {
			return isFinite(t) && !isNaN(parseFloat(t))
		}, T.isNaN = function(t) {
			return T.isNumber(t) && t != +t
		}, T.isBoolean = function(t) {
			return t === !0 || t === !1 || "[object Boolean]" == l.call(t)
		}, T.isNull = function(t) {
			return null === t
		}, T.isUndefined = function(t) {
			return void 0 === t
		}, T.has = function(t, e) {
			return f.call(t, e)
		}, T.noConflict = function() {
			return e._ = n, this
		}, T.identity = function(t) {
			return t
		}, T.constant = function(t) {
			return function() {
				return t
			}
		}, T.property = function(t) {
			return function(e) {
				return e[t]
			}
		}, T.matches = function(t) {
			return function(e) {
				if (e === t) return !0;
				for (var n in t)
					if (t[n] !== e[n]) return !1;
				return !0
			}
		}, T.times = function(t, e, n) {
			for (var r = Array(Math.max(0, t)), i = 0; t > i; i++) r[i] = e.call(n, i);
			return r
		}, T.random = function(t, e) {
			return null == e && (e = t, t = 0), t + Math.floor(Math.random() * (e - t + 1))
		}, T.now = Date.now || function() {
			return (new Date).getTime()
		};
		var L = {
			escape: {
				"&": "&amp;",
				"<": "&lt;",
				">": "&gt;",
				'"': "&quot;",
				"'": "&#x27;"
			}
		};
		L.unescape = T.invert(L.escape);
		var I = {
			escape: new RegExp("[" + T.keys(L.escape).join("") + "]", "g"),
			unescape: new RegExp("(" + T.keys(L.unescape).join("|") + ")", "g")
		};
		T.each(["escape", "unescape"], function(t) {
			T[t] = function(e) {
				return null == e ? "" : ("" + e).replace(I[t], function(e) {
					return L[t][e]
				})
			}
		}), T.result = function(t, e) {
			if (null == t) return void 0;
			var n = t[e];
			return T.isFunction(n) ? n.call(t) : n
		}, T.mixin = function(t) {
			w(T.functions(t), function(e) {
				var n = T[e] = t[e];
				T.prototype[e] = function() {
					var t = [this._wrapped];
					return a.apply(t, arguments), R.call(this, n.apply(T, t))
				}
			})
		};
		var F = 0;
		T.uniqueId = function(t) {
			var e = ++F + "";
			return t ? t + e : e
		}, T.templateSettings = {
			evaluate: /<%([\s\S]+?)%>/g,
			interpolate: /<%=([\s\S]+?)%>/g,
			escape: /<%-([\s\S]+?)%>/g
		};
		var D = /(.)^/,
			P = {
				"'": "'",
				"\\": "\\",
				"\r": "r",
				"\n": "n",
				"	": "t",
				"\u2028": "u2028",
				"\u2029": "u2029"
			}, B = /\\|'|\r|\n|\t|\u2028|\u2029/g;
		T.template = function(t, e, n) {
			var r;
			n = T.defaults({}, n, T.templateSettings);
			var i = new RegExp([(n.escape || D).source, (n.interpolate || D).source, (n.evaluate || D).source].join("|") + "|$", "g"),
				o = 0,
				s = "__p+='";
			t.replace(i, function(e, n, r, i, a) {
				return s += t.slice(o, a).replace(B, function(t) {
					return "\\" + P[t]
				}), n && (s += "'+\n((__t=(" + n + "))==null?'':_.escape(__t))+\n'"), r && (s += "'+\n((__t=(" + r + "))==null?'':__t)+\n'"), i && (s += "';\n" + i + "\n__p+='"), o = a + e.length, e
			}), s += "';\n", n.variable || (s = "with(obj||{}){\n" + s + "}\n"), s = "var __t,__p='',__j=Array.prototype.join,print=function(){__p+=__j.call(arguments,'');};\n" + s + "return __p;\n";
			try {
				r = new Function(n.variable || "obj", "_", s)
			} catch (a) {
				throw a.source = s, a
			}
			if (e) return r(e, T);
			var u = function(t) {
				return r.call(this, t, T)
			};
			return u.source = "function(" + (n.variable || "obj") + "){\n" + s + "}", u
		}, T.chain = function(t) {
			return T(t).chain()
		};
		var R = function(t) {
			return this._chain ? T(t).chain() : t
		};
		T.mixin(T), w(["pop", "push", "reverse", "shift", "sort", "splice", "unshift"], function(t) {
			var e = i[t];
			T.prototype[t] = function() {
				var n = this._wrapped;
				return e.apply(n, arguments), "shift" != t && "splice" != t || 0 !== n.length || delete n[0], R.call(this, n)
			}
		}), w(["concat", "join", "slice"], function(t) {
			var e = i[t];
			T.prototype[t] = function() {
				return R.call(this, e.apply(this._wrapped, arguments))
			}
		}), T.extend(T.prototype, {
			chain: function() {
				return this._chain = !0, this
			},
			value: function() {
				return this._wrapped
			}
		}), "function" == typeof t && t.amd && t("underscore", [], function() {
			return T
		})
	}.call(this), t("common", function(t) {
		{
			var n = (t("nodeb"), t("libs/appframework"), t("libs/fastclick"));
			t("libs/underscore")
		}
		e.__FastClick__ || (e.__FastClick__ = new n(document.body)), NODEB.alias({
			af: "appframework",
			FastClick: "libs/fastclick",
			underscore: "underscore",
			PageModel: "page/PageModel"
		})
	})
}(window.NODEB && window.NODEB.define || window.define, window);