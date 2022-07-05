"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_components_public_Topic_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/public/Topic.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/public/Topic.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return exports; }; var exports = {}, Op = Object.prototype, hasOwn = Op.hasOwnProperty, $Symbol = "function" == typeof Symbol ? Symbol : {}, iteratorSymbol = $Symbol.iterator || "@@iterator", asyncIteratorSymbol = $Symbol.asyncIterator || "@@asyncIterator", toStringTagSymbol = $Symbol.toStringTag || "@@toStringTag"; function define(obj, key, value) { return Object.defineProperty(obj, key, { value: value, enumerable: !0, configurable: !0, writable: !0 }), obj[key]; } try { define({}, ""); } catch (err) { define = function define(obj, key, value) { return obj[key] = value; }; } function wrap(innerFn, outerFn, self, tryLocsList) { var protoGenerator = outerFn && outerFn.prototype instanceof Generator ? outerFn : Generator, generator = Object.create(protoGenerator.prototype), context = new Context(tryLocsList || []); return generator._invoke = function (innerFn, self, context) { var state = "suspendedStart"; return function (method, arg) { if ("executing" === state) throw new Error("Generator is already running"); if ("completed" === state) { if ("throw" === method) throw arg; return doneResult(); } for (context.method = method, context.arg = arg;;) { var delegate = context.delegate; if (delegate) { var delegateResult = maybeInvokeDelegate(delegate, context); if (delegateResult) { if (delegateResult === ContinueSentinel) continue; return delegateResult; } } if ("next" === context.method) context.sent = context._sent = context.arg;else if ("throw" === context.method) { if ("suspendedStart" === state) throw state = "completed", context.arg; context.dispatchException(context.arg); } else "return" === context.method && context.abrupt("return", context.arg); state = "executing"; var record = tryCatch(innerFn, self, context); if ("normal" === record.type) { if (state = context.done ? "completed" : "suspendedYield", record.arg === ContinueSentinel) continue; return { value: record.arg, done: context.done }; } "throw" === record.type && (state = "completed", context.method = "throw", context.arg = record.arg); } }; }(innerFn, self, context), generator; } function tryCatch(fn, obj, arg) { try { return { type: "normal", arg: fn.call(obj, arg) }; } catch (err) { return { type: "throw", arg: err }; } } exports.wrap = wrap; var ContinueSentinel = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var IteratorPrototype = {}; define(IteratorPrototype, iteratorSymbol, function () { return this; }); var getProto = Object.getPrototypeOf, NativeIteratorPrototype = getProto && getProto(getProto(values([]))); NativeIteratorPrototype && NativeIteratorPrototype !== Op && hasOwn.call(NativeIteratorPrototype, iteratorSymbol) && (IteratorPrototype = NativeIteratorPrototype); var Gp = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(IteratorPrototype); function defineIteratorMethods(prototype) { ["next", "throw", "return"].forEach(function (method) { define(prototype, method, function (arg) { return this._invoke(method, arg); }); }); } function AsyncIterator(generator, PromiseImpl) { function invoke(method, arg, resolve, reject) { var record = tryCatch(generator[method], generator, arg); if ("throw" !== record.type) { var result = record.arg, value = result.value; return value && "object" == _typeof(value) && hasOwn.call(value, "__await") ? PromiseImpl.resolve(value.__await).then(function (value) { invoke("next", value, resolve, reject); }, function (err) { invoke("throw", err, resolve, reject); }) : PromiseImpl.resolve(value).then(function (unwrapped) { result.value = unwrapped, resolve(result); }, function (error) { return invoke("throw", error, resolve, reject); }); } reject(record.arg); } var previousPromise; this._invoke = function (method, arg) { function callInvokeWithMethodAndArg() { return new PromiseImpl(function (resolve, reject) { invoke(method, arg, resolve, reject); }); } return previousPromise = previousPromise ? previousPromise.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); }; } function maybeInvokeDelegate(delegate, context) { var method = delegate.iterator[context.method]; if (undefined === method) { if (context.delegate = null, "throw" === context.method) { if (delegate.iterator["return"] && (context.method = "return", context.arg = undefined, maybeInvokeDelegate(delegate, context), "throw" === context.method)) return ContinueSentinel; context.method = "throw", context.arg = new TypeError("The iterator does not provide a 'throw' method"); } return ContinueSentinel; } var record = tryCatch(method, delegate.iterator, context.arg); if ("throw" === record.type) return context.method = "throw", context.arg = record.arg, context.delegate = null, ContinueSentinel; var info = record.arg; return info ? info.done ? (context[delegate.resultName] = info.value, context.next = delegate.nextLoc, "return" !== context.method && (context.method = "next", context.arg = undefined), context.delegate = null, ContinueSentinel) : info : (context.method = "throw", context.arg = new TypeError("iterator result is not an object"), context.delegate = null, ContinueSentinel); } function pushTryEntry(locs) { var entry = { tryLoc: locs[0] }; 1 in locs && (entry.catchLoc = locs[1]), 2 in locs && (entry.finallyLoc = locs[2], entry.afterLoc = locs[3]), this.tryEntries.push(entry); } function resetTryEntry(entry) { var record = entry.completion || {}; record.type = "normal", delete record.arg, entry.completion = record; } function Context(tryLocsList) { this.tryEntries = [{ tryLoc: "root" }], tryLocsList.forEach(pushTryEntry, this), this.reset(!0); } function values(iterable) { if (iterable) { var iteratorMethod = iterable[iteratorSymbol]; if (iteratorMethod) return iteratorMethod.call(iterable); if ("function" == typeof iterable.next) return iterable; if (!isNaN(iterable.length)) { var i = -1, next = function next() { for (; ++i < iterable.length;) { if (hasOwn.call(iterable, i)) return next.value = iterable[i], next.done = !1, next; } return next.value = undefined, next.done = !0, next; }; return next.next = next; } } return { next: doneResult }; } function doneResult() { return { value: undefined, done: !0 }; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, define(Gp, "constructor", GeneratorFunctionPrototype), define(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, toStringTagSymbol, "GeneratorFunction"), exports.isGeneratorFunction = function (genFun) { var ctor = "function" == typeof genFun && genFun.constructor; return !!ctor && (ctor === GeneratorFunction || "GeneratorFunction" === (ctor.displayName || ctor.name)); }, exports.mark = function (genFun) { return Object.setPrototypeOf ? Object.setPrototypeOf(genFun, GeneratorFunctionPrototype) : (genFun.__proto__ = GeneratorFunctionPrototype, define(genFun, toStringTagSymbol, "GeneratorFunction")), genFun.prototype = Object.create(Gp), genFun; }, exports.awrap = function (arg) { return { __await: arg }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, asyncIteratorSymbol, function () { return this; }), exports.AsyncIterator = AsyncIterator, exports.async = function (innerFn, outerFn, self, tryLocsList, PromiseImpl) { void 0 === PromiseImpl && (PromiseImpl = Promise); var iter = new AsyncIterator(wrap(innerFn, outerFn, self, tryLocsList), PromiseImpl); return exports.isGeneratorFunction(outerFn) ? iter : iter.next().then(function (result) { return result.done ? result.value : iter.next(); }); }, defineIteratorMethods(Gp), define(Gp, toStringTagSymbol, "Generator"), define(Gp, iteratorSymbol, function () { return this; }), define(Gp, "toString", function () { return "[object Generator]"; }), exports.keys = function (object) { var keys = []; for (var key in object) { keys.push(key); } return keys.reverse(), function next() { for (; keys.length;) { var key = keys.pop(); if (key in object) return next.value = key, next.done = !1, next; } return next.done = !0, next; }; }, exports.values = values, Context.prototype = { constructor: Context, reset: function reset(skipTempReset) { if (this.prev = 0, this.next = 0, this.sent = this._sent = undefined, this.done = !1, this.delegate = null, this.method = "next", this.arg = undefined, this.tryEntries.forEach(resetTryEntry), !skipTempReset) for (var name in this) { "t" === name.charAt(0) && hasOwn.call(this, name) && !isNaN(+name.slice(1)) && (this[name] = undefined); } }, stop: function stop() { this.done = !0; var rootRecord = this.tryEntries[0].completion; if ("throw" === rootRecord.type) throw rootRecord.arg; return this.rval; }, dispatchException: function dispatchException(exception) { if (this.done) throw exception; var context = this; function handle(loc, caught) { return record.type = "throw", record.arg = exception, context.next = loc, caught && (context.method = "next", context.arg = undefined), !!caught; } for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i], record = entry.completion; if ("root" === entry.tryLoc) return handle("end"); if (entry.tryLoc <= this.prev) { var hasCatch = hasOwn.call(entry, "catchLoc"), hasFinally = hasOwn.call(entry, "finallyLoc"); if (hasCatch && hasFinally) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } else if (hasCatch) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); } else { if (!hasFinally) throw new Error("try statement without catch or finally"); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } } } }, abrupt: function abrupt(type, arg) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc <= this.prev && hasOwn.call(entry, "finallyLoc") && this.prev < entry.finallyLoc) { var finallyEntry = entry; break; } } finallyEntry && ("break" === type || "continue" === type) && finallyEntry.tryLoc <= arg && arg <= finallyEntry.finallyLoc && (finallyEntry = null); var record = finallyEntry ? finallyEntry.completion : {}; return record.type = type, record.arg = arg, finallyEntry ? (this.method = "next", this.next = finallyEntry.finallyLoc, ContinueSentinel) : this.complete(record); }, complete: function complete(record, afterLoc) { if ("throw" === record.type) throw record.arg; return "break" === record.type || "continue" === record.type ? this.next = record.arg : "return" === record.type ? (this.rval = this.arg = record.arg, this.method = "return", this.next = "end") : "normal" === record.type && afterLoc && (this.next = afterLoc), ContinueSentinel; }, finish: function finish(finallyLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.finallyLoc === finallyLoc) return this.complete(entry.completion, entry.afterLoc), resetTryEntry(entry), ContinueSentinel; } }, "catch": function _catch(tryLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc === tryLoc) { var record = entry.completion; if ("throw" === record.type) { var thrown = record.arg; resetTryEntry(entry); } return thrown; } } throw new Error("illegal catch attempt"); }, delegateYield: function delegateYield(iterable, resultName, nextLoc) { return this.delegate = { iterator: values(iterable), resultName: resultName, nextLoc: nextLoc }, "next" === this.method && (this.arg = undefined), ContinueSentinel; } }, exports; }

function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'PublicTopic',
  data: function data() {
    return {
      overlay: false,
      topic: {
        name: "",
        "abstract": "",
        body: "",
        created_at: "",
        updated_at: "",
        img: "",
        lazy_img: "",
        user: "",
        user_email: "",
        user_avatar: "",
        user_lazy_avatar: "",
        user_update: "",
        user_update_email: "",
        user_avatar_update: "",
        user_lazy_avatar_update: "",
        subject: "",
        tags: []
      }
    };
  },
  mounted: function mounted() {
    this.getTopic();
  },
  methods: {
    getTopic: function getTopic() {
      var _this = this;

      return _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee() {
        return _regeneratorRuntime().wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                _this.overlay = true;

                if (!(_this.$route.params.subject && _this.$route.params.topic)) {
                  _context.next = 6;
                  break;
                }

                _context.next = 4;
                return _this.axios.get('/api/gettopic/' + _this.$route.params.topic).then(function (response) {
                  var item = response.data;

                  if (!item.topic) {
                    _this.overlay = false;

                    _this.$router.push({
                      name: "publicSubject",
                      params: {
                        subject: _this.$route.params.subject
                      }
                    });
                  } else {
                    _this.topic.name = item.topic.name;
                    _this.topic["abstract"] = item.topic["abstract"];
                    _this.topic.user = item.topic.user;
                    _this.topic.user_email = item.topic.user_email;
                    _this.topic.user_update = item.topic.user_update;
                    _this.topic.user_update_email = item.topic.user_update_email;
                    _this.topic.subject = item.topic.subject;
                    _this.topic.created_at = item.topic.created_at;
                    _this.topic.updated_at = item.topic.updated_at;
                    _this.topic.body = item.topic.body; // Imagen del topic

                    if (item.topic.img) {
                      _this.topic.img = "/img/topics/" + item.topic.img;
                      _this.topic.lazy_img = "/img/lazy_topics/" + item.topic.img;
                    } else {
                      _this.topic.img = "/img/topics/blank.png";
                      _this.topic.lazy_img = "/img/lazy_topics/blank.png";
                    } // Imagenes de los usuarios


                    if (item.topic.user_avatar) {
                      _this.topic.user_avatar = "/img/users/" + item.topic.user_avatar;
                      _this.topic.user_lazy_avatar = "/img/lazy_users/" + item.topic.user_avatar;
                    } else {
                      _this.topic.user_avatar = "/img/users/blank.png";
                      _this.topic.user_lazy_avatar = "/img/lazy_users/blank.png";
                    } // Imagenes de los usuarios


                    if (item.topic.user_update_avatar) {
                      _this.topic.user_avatar_update = "/img/users/" + item.topic.user_update_avatar;
                      _this.topic.user_lazy_avatar_update = "/img/lazy_users/" + item.topic.user_update_avatar;
                    } else {
                      _this.topic.user_avatar_update = "/img/users/blank.png";
                      _this.topic.user_lazy_avatar_update = "/img/lazy_users/blank.png";
                    } // Tags


                    if (item.tags) {
                      _this.topic.tags = item.tags;
                      console.log(_this.topic.tags);
                    }

                    _this.overlay = false;
                  }
                })["catch"](function (error) {
                  console.log(error);
                  _this.overlay = false;

                  _this.$router.push("/");
                });

              case 4:
                _context.next = 7;
                break;

              case 6:
                if (_this.$route.params.subject && !_this.$route.params.topic) {
                  _this.overlay = false;

                  _this.$router.push({
                    name: "publicSubject",
                    params: {
                      subject: _this.$route.params.subject
                    }
                  });
                } else {
                  _this.overlay = false;

                  _this.$router.push("/");
                }

              case 7:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[1]!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[3]!./node_modules/vuetify/src/components/VChip/VChip.sass":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[1]!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[3]!./node_modules/vuetify/src/components/VChip/VChip.sass ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".v-chip:not(.v-chip--outlined).primary, .v-chip:not(.v-chip--outlined).secondary, .v-chip:not(.v-chip--outlined).accent, .v-chip:not(.v-chip--outlined).success, .v-chip:not(.v-chip--outlined).error, .v-chip:not(.v-chip--outlined).warning, .v-chip:not(.v-chip--outlined).info {\n  color: #FFFFFF;\n}\n\n.theme--light.v-chip {\n  border-color: rgba(0, 0, 0, 0.12);\n  color: rgba(0, 0, 0, 0.87);\n}\n.theme--light.v-chip:not(.v-chip--active) {\n  background: #e0e0e0;\n}\n.theme--light.v-chip:hover::before {\n  opacity: 0.04;\n}\n.theme--light.v-chip:focus::before {\n  opacity: 0.12;\n}\n.theme--light.v-chip--active:hover::before, .theme--light.v-chip--active::before {\n  opacity: 0.12;\n}\n.theme--light.v-chip--active:focus::before {\n  opacity: 0.16;\n}\n\n.theme--dark.v-chip {\n  border-color: rgba(255, 255, 255, 0.12);\n  color: #FFFFFF;\n}\n.theme--dark.v-chip:not(.v-chip--active) {\n  background: #555;\n}\n.theme--dark.v-chip:hover::before {\n  opacity: 0.08;\n}\n.theme--dark.v-chip:focus::before {\n  opacity: 0.24;\n}\n.theme--dark.v-chip--active:hover::before, .theme--dark.v-chip--active::before {\n  opacity: 0.24;\n}\n.theme--dark.v-chip--active:focus::before {\n  opacity: 0.32;\n}\n\n.v-chip {\n  align-items: center;\n  cursor: default;\n  display: inline-flex;\n  line-height: 20px;\n  max-width: 100%;\n  outline: none;\n  overflow: hidden;\n  padding: 0 12px;\n  position: relative;\n  text-decoration: none;\n  transition-duration: 0.28s;\n  transition-property: box-shadow, opacity;\n  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);\n  vertical-align: middle;\n  white-space: nowrap;\n}\n.v-chip:before {\n  background-color: currentColor;\n  bottom: 0;\n  border-radius: inherit;\n  content: \"\";\n  left: 0;\n  opacity: 0;\n  position: absolute;\n  pointer-events: none;\n  right: 0;\n  top: 0;\n}\n.v-chip .v-avatar {\n  height: 24px !important;\n  min-width: 24px !important;\n  width: 24px !important;\n}\n.v-chip .v-icon {\n  font-size: 24px;\n}\n.v-application--is-ltr .v-chip .v-avatar--left,\n.v-application--is-ltr .v-chip .v-icon--left {\n  margin-left: -6px;\n  margin-right: 6px;\n}\n.v-application--is-ltr .v-chip .v-avatar--right,\n.v-application--is-ltr .v-chip .v-icon--right {\n  margin-left: 6px;\n  margin-right: -6px;\n}\n.v-application--is-rtl .v-chip .v-avatar--left,\n.v-application--is-rtl .v-chip .v-icon--left {\n  margin-left: 6px;\n  margin-right: -6px;\n}\n.v-application--is-rtl .v-chip .v-avatar--right,\n.v-application--is-rtl .v-chip .v-icon--right {\n  margin-left: -6px;\n  margin-right: 6px;\n}\n.v-chip:not(.v-chip--no-color) .v-icon {\n  color: inherit;\n}\n\n.v-chip .v-chip__close.v-icon {\n  font-size: 18px;\n  max-height: 18px;\n  max-width: 18px;\n  -webkit-user-select: none;\n     -moz-user-select: none;\n      -ms-user-select: none;\n          user-select: none;\n}\n.v-application--is-ltr .v-chip .v-chip__close.v-icon.v-icon--right {\n  margin-right: -4px;\n}\n.v-application--is-rtl .v-chip .v-chip__close.v-icon.v-icon--right {\n  margin-left: -4px;\n}\n.v-chip .v-chip__close.v-icon:hover, .v-chip .v-chip__close.v-icon:focus, .v-chip .v-chip__close.v-icon:active {\n  opacity: 0.72;\n}\n.v-chip .v-chip__content {\n  align-items: center;\n  display: inline-flex;\n  height: 100%;\n  max-width: 100%;\n}\n\n.v-chip--active .v-icon {\n  color: inherit;\n}\n\n.v-chip--link::before {\n  transition: opacity 0.3s cubic-bezier(0.25, 0.8, 0.5, 1);\n}\n.v-chip--link:focus::before {\n  opacity: 0.32;\n}\n\n.v-chip--clickable {\n  cursor: pointer;\n  -webkit-user-select: none;\n     -moz-user-select: none;\n      -ms-user-select: none;\n          user-select: none;\n}\n.v-chip--clickable:active {\n  box-shadow: 0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12);\n}\n\n.v-chip--disabled {\n  opacity: 0.4;\n  pointer-events: none;\n  -webkit-user-select: none;\n     -moz-user-select: none;\n      -ms-user-select: none;\n          user-select: none;\n}\n\n.v-chip__filter {\n  max-width: 24px;\n}\n.v-chip__filter.v-icon {\n  color: inherit;\n}\n.v-chip__filter.expand-x-transition-leave-active, .v-chip__filter.expand-x-transition-enter {\n  margin: 0;\n}\n\n.v-chip--pill .v-chip__filter {\n  margin-right: 0 16px 0 0;\n}\n.v-chip--pill .v-avatar {\n  height: 32px !important;\n  width: 32px !important;\n}\n.v-application--is-ltr .v-chip--pill .v-avatar--left {\n  margin-left: -12px;\n}\n.v-application--is-ltr .v-chip--pill .v-avatar--right {\n  margin-right: -12px;\n}\n.v-application--is-rtl .v-chip--pill .v-avatar--left {\n  margin-right: -12px;\n}\n.v-application--is-rtl .v-chip--pill .v-avatar--right {\n  margin-left: -12px;\n}\n\n.v-chip--label {\n  border-radius: 4px !important;\n}\n\n.v-chip.v-chip--outlined {\n  border-width: thin;\n  border-style: solid;\n}\n.v-chip.v-chip--outlined.v-chip--active:before {\n  opacity: 0.08;\n}\n.v-chip.v-chip--outlined .v-icon {\n  color: inherit;\n}\n.v-chip.v-chip--outlined.v-chip.v-chip {\n  background-color: transparent !important;\n}\n\n.v-chip.v-chip--selected {\n  background: transparent;\n}\n.v-chip.v-chip--selected:after {\n  opacity: 0.28;\n}\n\n.v-chip.v-size--x-small {\n  border-radius: 8px;\n  font-size: 10px;\n  height: 16px;\n}\n.v-chip.v-size--small {\n  border-radius: 12px;\n  font-size: 12px;\n  height: 24px;\n}\n.v-chip.v-size--default {\n  border-radius: 16px;\n  font-size: 14px;\n  height: 32px;\n}\n.v-chip.v-size--large {\n  border-radius: 27px;\n  font-size: 16px;\n  height: 54px;\n}\n.v-chip.v-size--x-large {\n  border-radius: 33px;\n  font-size: 18px;\n  height: 66px;\n}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[1]!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[3]!./node_modules/vuetify/src/components/VSkeletonLoader/VSkeletonLoader.sass":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[1]!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[3]!./node_modules/vuetify/src/components/VSkeletonLoader/VSkeletonLoader.sass ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, ".theme--light.v-skeleton-loader .v-skeleton-loader__bone::after {\n  background: linear-gradient(90deg, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0));\n}\n.theme--light.v-skeleton-loader .v-skeleton-loader__avatar,\n.theme--light.v-skeleton-loader .v-skeleton-loader__button,\n.theme--light.v-skeleton-loader .v-skeleton-loader__chip,\n.theme--light.v-skeleton-loader .v-skeleton-loader__divider,\n.theme--light.v-skeleton-loader .v-skeleton-loader__heading,\n.theme--light.v-skeleton-loader .v-skeleton-loader__image,\n.theme--light.v-skeleton-loader .v-skeleton-loader__text {\n  background: rgba(0, 0, 0, 0.12);\n}\n.theme--light.v-skeleton-loader .v-skeleton-loader__actions,\n.theme--light.v-skeleton-loader .v-skeleton-loader__article,\n.theme--light.v-skeleton-loader .v-skeleton-loader__card-heading,\n.theme--light.v-skeleton-loader .v-skeleton-loader__card-text,\n.theme--light.v-skeleton-loader .v-skeleton-loader__date-picker,\n.theme--light.v-skeleton-loader .v-skeleton-loader__list-item,\n.theme--light.v-skeleton-loader .v-skeleton-loader__list-item-avatar,\n.theme--light.v-skeleton-loader .v-skeleton-loader__list-item-text,\n.theme--light.v-skeleton-loader .v-skeleton-loader__list-item-two-line,\n.theme--light.v-skeleton-loader .v-skeleton-loader__list-item-avatar-two-line,\n.theme--light.v-skeleton-loader .v-skeleton-loader__list-item-three-line,\n.theme--light.v-skeleton-loader .v-skeleton-loader__list-item-avatar-three-line,\n.theme--light.v-skeleton-loader .v-skeleton-loader__table-heading,\n.theme--light.v-skeleton-loader .v-skeleton-loader__table-thead,\n.theme--light.v-skeleton-loader .v-skeleton-loader__table-tbody,\n.theme--light.v-skeleton-loader .v-skeleton-loader__table-tfoot {\n  background: #FFFFFF;\n}\n\n.theme--dark.v-skeleton-loader .v-skeleton-loader__bone::after {\n  background: linear-gradient(90deg, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0));\n}\n.theme--dark.v-skeleton-loader .v-skeleton-loader__avatar,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__button,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__chip,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__divider,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__heading,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__image,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__text {\n  background: rgba(255, 255, 255, 0.12);\n}\n.theme--dark.v-skeleton-loader .v-skeleton-loader__actions,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__article,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__card-heading,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__card-text,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__date-picker,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__list-item,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__list-item-avatar,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__list-item-text,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__list-item-two-line,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__list-item-avatar-two-line,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__list-item-three-line,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__list-item-avatar-three-line,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__table-heading,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__table-thead,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__table-tbody,\n.theme--dark.v-skeleton-loader .v-skeleton-loader__table-tfoot {\n  background: #1E1E1E;\n}\n\n.v-skeleton-loader {\n  border-radius: 4px;\n  position: relative;\n  vertical-align: top;\n}\n.v-skeleton-loader__actions {\n  padding: 16px 16px 8px;\n  text-align: right;\n}\n.v-skeleton-loader__actions .v-skeleton-loader__button {\n  display: inline-block;\n}\n.v-application--is-ltr .v-skeleton-loader__actions .v-skeleton-loader__button:first-child {\n  margin-right: 12px;\n}\n.v-application--is-rtl .v-skeleton-loader__actions .v-skeleton-loader__button:first-child {\n  margin-left: 12px;\n}\n.v-skeleton-loader .v-skeleton-loader__list-item,\n.v-skeleton-loader .v-skeleton-loader__list-item-avatar,\n.v-skeleton-loader .v-skeleton-loader__list-item-text,\n.v-skeleton-loader .v-skeleton-loader__list-item-two-line,\n.v-skeleton-loader .v-skeleton-loader__list-item-avatar-two-line,\n.v-skeleton-loader .v-skeleton-loader__list-item-three-line,\n.v-skeleton-loader .v-skeleton-loader__list-item-avatar-three-line {\n  border-radius: 4px;\n}\n.v-skeleton-loader .v-skeleton-loader__actions::after,\n.v-skeleton-loader .v-skeleton-loader__article::after,\n.v-skeleton-loader .v-skeleton-loader__card::after,\n.v-skeleton-loader .v-skeleton-loader__card-avatar::after,\n.v-skeleton-loader .v-skeleton-loader__card-heading::after,\n.v-skeleton-loader .v-skeleton-loader__card-text::after,\n.v-skeleton-loader .v-skeleton-loader__date-picker::after,\n.v-skeleton-loader .v-skeleton-loader__date-picker-options::after,\n.v-skeleton-loader .v-skeleton-loader__date-picker-days::after,\n.v-skeleton-loader .v-skeleton-loader__list-item::after,\n.v-skeleton-loader .v-skeleton-loader__list-item-avatar::after,\n.v-skeleton-loader .v-skeleton-loader__list-item-text::after,\n.v-skeleton-loader .v-skeleton-loader__list-item-two-line::after,\n.v-skeleton-loader .v-skeleton-loader__list-item-avatar-two-line::after,\n.v-skeleton-loader .v-skeleton-loader__list-item-three-line::after,\n.v-skeleton-loader .v-skeleton-loader__list-item-avatar-three-line::after,\n.v-skeleton-loader .v-skeleton-loader__paragraph::after,\n.v-skeleton-loader .v-skeleton-loader__sentences::after,\n.v-skeleton-loader .v-skeleton-loader__table::after,\n.v-skeleton-loader .v-skeleton-loader__table-cell::after,\n.v-skeleton-loader .v-skeleton-loader__table-heading::after,\n.v-skeleton-loader .v-skeleton-loader__table-thead::after,\n.v-skeleton-loader .v-skeleton-loader__table-tbody::after,\n.v-skeleton-loader .v-skeleton-loader__table-tfoot::after,\n.v-skeleton-loader .v-skeleton-loader__table-row::after,\n.v-skeleton-loader .v-skeleton-loader__table-row-divider::after {\n  display: none;\n}\n.v-application--is-ltr .v-skeleton-loader__article .v-skeleton-loader__heading {\n  margin: 16px 0 16px 16px;\n}\n.v-application--is-rtl .v-skeleton-loader__article .v-skeleton-loader__heading {\n  margin: 16px 16px 0 16px;\n}\n.v-skeleton-loader__article .v-skeleton-loader__paragraph {\n  padding: 16px;\n}\n.v-skeleton-loader__bone {\n  border-radius: inherit;\n  overflow: hidden;\n  position: relative;\n}\n.v-skeleton-loader__bone::after {\n  -webkit-animation: loading 1.5s infinite;\n          animation: loading 1.5s infinite;\n  content: \"\";\n  height: 100%;\n  left: 0;\n  position: absolute;\n  right: 0;\n  top: 0;\n  transform: translateX(-100%);\n  z-index: 1;\n}\n.v-skeleton-loader__avatar {\n  border-radius: 50%;\n  height: 48px;\n  width: 48px;\n}\n.v-skeleton-loader__button {\n  border-radius: 4px;\n  height: 36px;\n  width: 64px;\n}\n.v-skeleton-loader__card .v-skeleton-loader__image {\n  border-radius: 0;\n}\n.v-skeleton-loader__card-heading .v-skeleton-loader__heading {\n  margin: 16px;\n}\n.v-skeleton-loader__card-text {\n  padding: 16px;\n}\n.v-skeleton-loader__chip {\n  border-radius: 16px;\n  height: 32px;\n  width: 96px;\n}\n.v-skeleton-loader__date-picker {\n  border-radius: inherit;\n}\n.v-skeleton-loader__date-picker .v-skeleton-loader__list-item:first-child .v-skeleton-loader__text {\n  max-width: 88px;\n  width: 20%;\n}\n.v-skeleton-loader__date-picker .v-skeleton-loader__heading {\n  max-width: 256px;\n  width: 40%;\n}\n.v-skeleton-loader__date-picker-days {\n  display: flex;\n  flex-wrap: wrap;\n  padding: 0 12px;\n  margin: 0 auto;\n}\n.v-skeleton-loader__date-picker-days .v-skeleton-loader__avatar {\n  border-radius: 4px;\n  flex: 1 1 auto;\n  margin: 4px;\n  height: 40px;\n  width: 40px;\n}\n.v-skeleton-loader__date-picker-options {\n  align-items: center;\n  display: flex;\n  padding: 16px;\n}\n.v-skeleton-loader__date-picker-options .v-skeleton-loader__avatar {\n  height: 40px;\n  width: 40px;\n}\n.v-skeleton-loader__date-picker-options .v-skeleton-loader__avatar:nth-child(2) {\n  margin-left: auto;\n}\n.v-application--is-ltr .v-skeleton-loader__date-picker-options .v-skeleton-loader__avatar:nth-child(2) {\n  margin-right: 8px;\n}\n.v-application--is-rtl .v-skeleton-loader__date-picker-options .v-skeleton-loader__avatar:nth-child(2) {\n  margin-left: 8px;\n}\n.v-skeleton-loader__date-picker-options .v-skeleton-loader__text.v-skeleton-loader__bone:first-child {\n  margin-bottom: 0px;\n  max-width: 50%;\n  width: 456px;\n}\n.v-skeleton-loader__divider {\n  border-radius: 1px;\n  height: 2px;\n}\n.v-skeleton-loader__heading {\n  border-radius: 12px;\n  height: 24px;\n  width: 45%;\n}\n.v-skeleton-loader__image {\n  height: 200px;\n  border-radius: 0;\n}\n.v-skeleton-loader__image ~ .v-skeleton-loader__card-heading {\n  border-radius: 0;\n}\n.v-skeleton-loader__image::first-child, .v-skeleton-loader__image::last-child {\n  border-radius: inherit;\n}\n.v-skeleton-loader__list-item {\n  height: 48px;\n}\n.v-skeleton-loader__list-item-three-line {\n  flex-wrap: wrap;\n}\n.v-skeleton-loader__list-item-three-line > * {\n  flex: 1 0 100%;\n  width: 100%;\n}\n.v-skeleton-loader__list-item-avatar .v-skeleton-loader__avatar, .v-skeleton-loader__list-item-avatar-two-line .v-skeleton-loader__avatar, .v-skeleton-loader__list-item-avatar-three-line .v-skeleton-loader__avatar {\n  height: 40px;\n  width: 40px;\n}\n.v-skeleton-loader__list-item-avatar {\n  height: 48px;\n}\n.v-skeleton-loader__list-item-two-line, .v-skeleton-loader__list-item-avatar-two-line {\n  height: 72px;\n}\n.v-skeleton-loader__list-item-three-line, .v-skeleton-loader__list-item-avatar-three-line {\n  height: 88px;\n}\n.v-skeleton-loader__list-item-avatar-three-line .v-skeleton-loader__avatar {\n  align-self: flex-start;\n}\n.v-skeleton-loader__list-item, .v-skeleton-loader__list-item-avatar, .v-skeleton-loader__list-item-two-line, .v-skeleton-loader__list-item-three-line, .v-skeleton-loader__list-item-avatar-two-line, .v-skeleton-loader__list-item-avatar-three-line {\n  align-content: center;\n  align-items: center;\n  display: flex;\n  flex-wrap: wrap;\n  padding: 0 16px;\n}\n.v-application--is-ltr .v-skeleton-loader__list-item .v-skeleton-loader__avatar, .v-application--is-ltr .v-skeleton-loader__list-item-avatar .v-skeleton-loader__avatar, .v-application--is-ltr .v-skeleton-loader__list-item-two-line .v-skeleton-loader__avatar, .v-application--is-ltr .v-skeleton-loader__list-item-three-line .v-skeleton-loader__avatar, .v-application--is-ltr .v-skeleton-loader__list-item-avatar-two-line .v-skeleton-loader__avatar, .v-application--is-ltr .v-skeleton-loader__list-item-avatar-three-line .v-skeleton-loader__avatar {\n  margin-right: 16px;\n}\n.v-application--is-rtl .v-skeleton-loader__list-item .v-skeleton-loader__avatar, .v-application--is-rtl .v-skeleton-loader__list-item-avatar .v-skeleton-loader__avatar, .v-application--is-rtl .v-skeleton-loader__list-item-two-line .v-skeleton-loader__avatar, .v-application--is-rtl .v-skeleton-loader__list-item-three-line .v-skeleton-loader__avatar, .v-application--is-rtl .v-skeleton-loader__list-item-avatar-two-line .v-skeleton-loader__avatar, .v-application--is-rtl .v-skeleton-loader__list-item-avatar-three-line .v-skeleton-loader__avatar {\n  margin-left: 16px;\n}\n.v-skeleton-loader__list-item .v-skeleton-loader__text:last-child,\n.v-skeleton-loader__list-item .v-skeleton-loader__text:only-child, .v-skeleton-loader__list-item-avatar .v-skeleton-loader__text:last-child,\n.v-skeleton-loader__list-item-avatar .v-skeleton-loader__text:only-child, .v-skeleton-loader__list-item-two-line .v-skeleton-loader__text:last-child,\n.v-skeleton-loader__list-item-two-line .v-skeleton-loader__text:only-child, .v-skeleton-loader__list-item-three-line .v-skeleton-loader__text:last-child,\n.v-skeleton-loader__list-item-three-line .v-skeleton-loader__text:only-child, .v-skeleton-loader__list-item-avatar-two-line .v-skeleton-loader__text:last-child,\n.v-skeleton-loader__list-item-avatar-two-line .v-skeleton-loader__text:only-child, .v-skeleton-loader__list-item-avatar-three-line .v-skeleton-loader__text:last-child,\n.v-skeleton-loader__list-item-avatar-three-line .v-skeleton-loader__text:only-child {\n  margin-bottom: 0;\n}\n.v-skeleton-loader__paragraph, .v-skeleton-loader__sentences {\n  flex: 1 0 auto;\n}\n.v-skeleton-loader__paragraph:not(:last-child) {\n  margin-bottom: 6px;\n}\n.v-skeleton-loader__paragraph .v-skeleton-loader__text:nth-child(1) {\n  max-width: 100%;\n}\n.v-skeleton-loader__paragraph .v-skeleton-loader__text:nth-child(2) {\n  max-width: 50%;\n}\n.v-skeleton-loader__paragraph .v-skeleton-loader__text:nth-child(3) {\n  max-width: 70%;\n}\n.v-skeleton-loader__sentences .v-skeleton-loader__text:nth-child(2) {\n  max-width: 70%;\n}\n.v-skeleton-loader__sentences:not(:last-child) {\n  margin-bottom: 6px;\n}\n.v-skeleton-loader__table-heading {\n  align-items: center;\n  display: flex;\n  justify-content: space-between;\n  padding: 16px;\n}\n.v-skeleton-loader__table-heading .v-skeleton-loader__heading {\n  max-width: 15%;\n}\n.v-skeleton-loader__table-heading .v-skeleton-loader__text {\n  max-width: 40%;\n}\n.v-skeleton-loader__table-thead {\n  display: flex;\n  justify-content: space-between;\n  padding: 16px;\n}\n.v-skeleton-loader__table-thead .v-skeleton-loader__heading {\n  max-width: 5%;\n}\n.v-skeleton-loader__table-tbody {\n  padding: 16px 16px 0;\n}\n.v-skeleton-loader__table-tfoot {\n  align-items: center;\n  display: flex;\n  justify-content: flex-end;\n  padding: 16px;\n}\n.v-application--is-ltr .v-skeleton-loader__table-tfoot > * {\n  margin-left: 8px;\n}\n.v-application--is-rtl .v-skeleton-loader__table-tfoot > * {\n  margin-right: 8px;\n}\n.v-skeleton-loader__table-tfoot .v-skeleton-loader__avatar {\n  height: 40px;\n  width: 40px;\n}\n.v-skeleton-loader__table-tfoot .v-skeleton-loader__text {\n  margin-bottom: 0;\n}\n.v-skeleton-loader__table-tfoot .v-skeleton-loader__text:nth-child(1) {\n  max-width: 128px;\n}\n.v-skeleton-loader__table-tfoot .v-skeleton-loader__text:nth-child(2) {\n  max-width: 64px;\n}\n.v-skeleton-loader__table-row {\n  display: flex;\n  justify-content: space-between;\n}\n.v-skeleton-loader__table-cell {\n  align-items: center;\n  display: flex;\n  height: 48px;\n  width: 88px;\n}\n.v-skeleton-loader__table-cell .v-skeleton-loader__text {\n  margin-bottom: 0;\n}\n.v-skeleton-loader__text {\n  border-radius: 6px;\n  flex: 1 0 auto;\n  height: 12px;\n  margin-bottom: 6px;\n}\n.v-skeleton-loader--boilerplate .v-skeleton-loader__bone:after {\n  display: none;\n}\n.v-skeleton-loader--is-loading {\n  overflow: hidden;\n}\n.v-skeleton-loader--tile {\n  border-radius: 0;\n}\n.v-skeleton-loader--tile .v-skeleton-loader__bone {\n  border-radius: 0;\n}\n\n@-webkit-keyframes loading {\n  100% {\n    transform: translateX(100%);\n  }\n}\n\n@keyframes loading {\n  100% {\n    transform: translateX(100%);\n  }\n}", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/vuetify/src/components/VChip/VChip.sass":
/*!**************************************************************!*\
  !*** ./node_modules/vuetify/src/components/VChip/VChip.sass ***!
  \**************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _css_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_1_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_2_sass_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_3_VChip_sass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../css-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[1]!../../../../laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[2]!../../../../sass-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[3]!./VChip.sass */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[1]!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[3]!./node_modules/vuetify/src/components/VChip/VChip.sass");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_css_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_1_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_2_sass_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_3_VChip_sass__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_css_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_1_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_2_sass_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_3_VChip_sass__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/vuetify/src/components/VSkeletonLoader/VSkeletonLoader.sass":
/*!**********************************************************************************!*\
  !*** ./node_modules/vuetify/src/components/VSkeletonLoader/VSkeletonLoader.sass ***!
  \**********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _css_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_1_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_2_sass_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_3_VSkeletonLoader_sass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../css-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[1]!../../../../laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[2]!../../../../sass-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[3]!./VSkeletonLoader.sass */ "./node_modules/css-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[1]!./node_modules/laravel-mix/node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[2]!./node_modules/sass-loader/dist/cjs.js??clonedRuleSet-15[0].rules[0].use[3]!./node_modules/vuetify/src/components/VSkeletonLoader/VSkeletonLoader.sass");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_css_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_1_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_2_sass_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_3_VSkeletonLoader_sass__WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_css_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_1_laravel_mix_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_2_sass_loader_dist_cjs_js_clonedRuleSet_15_0_rules_0_use_3_VSkeletonLoader_sass__WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/components/public/Topic.vue?vue&type=script&lang=js&":
/*!***************************************************************************!*\
  !*** ./resources/js/components/public/Topic.vue?vue&type=script&lang=js& ***!
  \***************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Topic_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Topic.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/public/Topic.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Topic_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/public/Topic.vue?vue&type=template&id=ce9f9598&":
/*!*********************************************************************************!*\
  !*** ./resources/js/components/public/Topic.vue?vue&type=template&id=ce9f9598& ***!
  \*********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Topic_vue_vue_type_template_id_ce9f9598___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Topic_vue_vue_type_template_id_ce9f9598___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Topic_vue_vue_type_template_id_ce9f9598___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./Topic.vue?vue&type=template&id=ce9f9598& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/public/Topic.vue?vue&type=template&id=ce9f9598&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/public/Topic.vue?vue&type=template&id=ce9f9598&":
/*!************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/public/Topic.vue?vue&type=template&id=ce9f9598& ***!
  \************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "v-main",
    [
      _c(
        "v-overlay",
        { attrs: { value: _vm.overlay } },
        [
          _c("v-progress-circular", {
            attrs: { indeterminate: "", size: "64" },
          }),
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "ml-16" },
        [
          _c(
            "v-card",
            {
              staticClass: "mt-2 mx-auto",
              attrs: { elevation: "2", "max-width": "1200" },
            },
            [
              _vm.overlay == false
                ? [
                    _c("v-img", {
                      staticClass: "mx-auto",
                      attrs: {
                        src: _vm.topic.img,
                        "lazy-src": _vm.topic.lazy_img,
                        "max-height": "250",
                      },
                      scopedSlots: _vm._u(
                        [
                          {
                            key: "placeholder",
                            fn: function () {
                              return [
                                _c(
                                  "v-row",
                                  {
                                    staticClass: "fill-height ma-0",
                                    attrs: {
                                      align: "center",
                                      justify: "center",
                                    },
                                  },
                                  [
                                    _c("v-progress-circular", {
                                      attrs: {
                                        indeterminate: "",
                                        color: "grey lighten-5",
                                      },
                                    }),
                                  ],
                                  1
                                ),
                              ]
                            },
                            proxy: true,
                          },
                        ],
                        null,
                        false,
                        4034393411
                      ),
                    }),
                    _vm._v(" "),
                    _vm.topic.tags.length > 0
                      ? [
                          _c(
                            "div",
                            { staticClass: "mt-6 mx-4" },
                            [
                              _vm._l(_vm.topic.tags, function (item) {
                                return [
                                  _c(
                                    "v-chip",
                                    {
                                      style: "color:" + item.text_color + ";",
                                      attrs: {
                                        label: "",
                                        color: item.background_color,
                                      },
                                    },
                                    [
                                      _c("v-icon", { attrs: { left: "" } }, [
                                        _vm._v("label"),
                                      ]),
                                      _vm._v(
                                        " " +
                                          _vm._s(item.name) +
                                          "\n                            "
                                      ),
                                    ],
                                    1
                                  ),
                                ]
                              }),
                            ],
                            2
                          ),
                        ]
                      : _vm._e(),
                    _vm._v(" "),
                    _c("v-card-title", [
                      _c(
                        "span",
                        {
                          staticClass:
                            "text-h5 text-uppercase font-weight-bold",
                        },
                        [
                          _vm._v(
                            "\n                        " +
                              _vm._s(_vm.topic.name) +
                              "\n                    "
                          ),
                        ]
                      ),
                    ]),
                    _vm._v(" "),
                    _c(
                      "v-card-text",
                      [
                        _c(
                          "v-list-item",
                          [
                            _c(
                              "v-list-item-avatar",
                              [
                                _c("v-img", {
                                  attrs: {
                                    src: _vm.topic.user_update
                                      ? _vm.topic.user_avatar_update
                                      : _vm.topic.user_avatar,
                                    "max-height": "38",
                                    "max-width": "38",
                                    "lazy-src": _vm.topic.user_update
                                      ? _vm.topic.user_lazy_avatar_update
                                      : _vm.topic.user_lazy_avatar,
                                  },
                                  scopedSlots: _vm._u(
                                    [
                                      {
                                        key: "placeholder",
                                        fn: function () {
                                          return [
                                            _c(
                                              "v-row",
                                              {
                                                staticClass: "fill-height ma-0",
                                                attrs: {
                                                  align: "center",
                                                  justify: "center",
                                                },
                                              },
                                              [
                                                _c("v-progress-circular", {
                                                  attrs: {
                                                    indeterminate: "",
                                                    color: "grey lighten-5",
                                                  },
                                                }),
                                              ],
                                              1
                                            ),
                                          ]
                                        },
                                        proxy: true,
                                      },
                                    ],
                                    null,
                                    false,
                                    4034393411
                                  ),
                                }),
                              ],
                              1
                            ),
                            _vm._v(" "),
                            _c("v-list-item-title", [
                              _c(
                                "span",
                                {
                                  staticClass:
                                    "subtitle-1 font-italic font-weight-bold",
                                },
                                [
                                  _vm._v(
                                    "\n                                " +
                                      _vm._s(
                                        _vm.topic.user_update
                                          ? _vm.topic.user_update
                                          : _vm.topic.user
                                      ) +
                                      "\n                            "
                                  ),
                                ]
                              ),
                              _vm._v(" "),
                              _c("br"),
                              _vm._v(" "),
                              _c(
                                "span",
                                { staticClass: "subtitle-2 font-italic" },
                                [
                                  _vm._v(
                                    "\n                                Contacto: " +
                                      _vm._s(
                                        _vm.topic.user_update
                                          ? _vm.topic.user_update_email
                                          : _vm.topic.user_email
                                      ) +
                                      "\n                            "
                                  ),
                                ]
                              ),
                              _vm._v(" "),
                              _c("br"),
                              _vm._v(" "),
                              _c(
                                "span",
                                { staticClass: "subtitle-2 font-italic" },
                                [
                                  _vm._v(
                                    "\n                                Ultima actualización: " +
                                      _vm._s(
                                        _vm.topic.user_update
                                          ? _vm.topic.updated_at
                                          : _vm.topic.created_at
                                      ) +
                                      "\n                            "
                                  ),
                                ]
                              ),
                            ]),
                          ],
                          1
                        ),
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _vm.topic.abstract
                      ? [
                          _c("v-card-text", [
                            _c(
                              "div",
                              {
                                staticClass: "bk_tags_bk txt_black mx-2 pa-2",
                                attrs: { id: "data_abstract" },
                              },
                              [
                                _c(
                                  "span",
                                  {
                                    staticClass: "subtitle-2 font-weight-bold",
                                  },
                                  [
                                    _vm._v(
                                      "\n                                Descripción\n                            "
                                    ),
                                  ]
                                ),
                                _vm._v(" "),
                                _c("br"),
                                _vm._v(" "),
                                _c("span", { staticClass: "body-2" }, [
                                  _vm._v(
                                    "\n                                " +
                                      _vm._s(_vm.topic.abstract) +
                                      "\n                            "
                                  ),
                                ]),
                              ]
                            ),
                          ]),
                        ]
                      : _vm._e(),
                    _vm._v(" "),
                    _c("v-card-text", [
                      _c(
                        "div",
                        { staticClass: "px-2", attrs: { id: "data_body" } },
                        [
                          _vm._v(
                            "\n                        " +
                              _vm._s(_vm.topic.body) +
                              "\n                    "
                          ),
                        ]
                      ),
                    ]),
                  ]
                : [
                    _c(
                      "v-row",
                      [
                        _c(
                          "v-col",
                          { attrs: { cols: "12" } },
                          [
                            _c(
                              "v-skeleton-loader",
                              _vm._b(
                                {
                                  attrs: {
                                    type: "list-item-avatar-three-line, image, article",
                                  },
                                },
                                "v-skeleton-loader",
                                _vm.attrs,
                                false
                              )
                            ),
                          ],
                          1
                        ),
                      ],
                      1
                    ),
                  ],
            ],
            2
          ),
        ],
        1
      ),
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/public/Topic.vue":
/*!**************************************************!*\
  !*** ./resources/js/components/public/Topic.vue ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Topic_vue_vue_type_template_id_ce9f9598___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Topic.vue?vue&type=template&id=ce9f9598& */ "./resources/js/components/public/Topic.vue?vue&type=template&id=ce9f9598&");
/* harmony import */ var _Topic_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Topic.vue?vue&type=script&lang=js& */ "./resources/js/components/public/Topic.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
/* harmony import */ var _node_modules_vuetify_loader_lib_runtime_installComponents_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../node_modules/vuetify-loader/lib/runtime/installComponents.js */ "./node_modules/vuetify-loader/lib/runtime/installComponents.js");
/* harmony import */ var _node_modules_vuetify_loader_lib_runtime_installComponents_js__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_node_modules_vuetify_loader_lib_runtime_installComponents_js__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var vuetify_lib_components_VCard__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! vuetify/lib/components/VCard */ "./node_modules/vuetify/lib/components/VCard/VCard.js");
/* harmony import */ var vuetify_lib_components_VCard__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! vuetify/lib/components/VCard */ "./node_modules/vuetify/lib/components/VCard/index.js");
/* harmony import */ var vuetify_lib_components_VChip__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! vuetify/lib/components/VChip */ "./node_modules/vuetify/lib/components/VChip/VChip.js");
/* harmony import */ var vuetify_lib_components_VGrid__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! vuetify/lib/components/VGrid */ "./node_modules/vuetify/lib/components/VGrid/VCol.js");
/* harmony import */ var vuetify_lib_components_VIcon__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! vuetify/lib/components/VIcon */ "./node_modules/vuetify/lib/components/VIcon/VIcon.js");
/* harmony import */ var vuetify_lib_components_VImg__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! vuetify/lib/components/VImg */ "./node_modules/vuetify/lib/components/VImg/VImg.js");
/* harmony import */ var vuetify_lib_components_VList__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! vuetify/lib/components/VList */ "./node_modules/vuetify/lib/components/VList/VListItem.js");
/* harmony import */ var vuetify_lib_components_VList__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! vuetify/lib/components/VList */ "./node_modules/vuetify/lib/components/VList/VListItemAvatar.js");
/* harmony import */ var vuetify_lib_components_VList__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! vuetify/lib/components/VList */ "./node_modules/vuetify/lib/components/VList/index.js");
/* harmony import */ var vuetify_lib_components_VMain__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! vuetify/lib/components/VMain */ "./node_modules/vuetify/lib/components/VMain/VMain.js");
/* harmony import */ var vuetify_lib_components_VOverlay__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! vuetify/lib/components/VOverlay */ "./node_modules/vuetify/lib/components/VOverlay/VOverlay.js");
/* harmony import */ var vuetify_lib_components_VProgressCircular__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! vuetify/lib/components/VProgressCircular */ "./node_modules/vuetify/lib/components/VProgressCircular/VProgressCircular.js");
/* harmony import */ var vuetify_lib_components_VGrid__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! vuetify/lib/components/VGrid */ "./node_modules/vuetify/lib/components/VGrid/VRow.js");
/* harmony import */ var vuetify_lib_components_VSkeletonLoader__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! vuetify/lib/components/VSkeletonLoader */ "./node_modules/vuetify/lib/components/VSkeletonLoader/VSkeletonLoader.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Topic_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Topic_vue_vue_type_template_id_ce9f9598___WEBPACK_IMPORTED_MODULE_0__.render,
  _Topic_vue_vue_type_template_id_ce9f9598___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* vuetify-loader */
;















_node_modules_vuetify_loader_lib_runtime_installComponents_js__WEBPACK_IMPORTED_MODULE_3___default()(component, {VCard: vuetify_lib_components_VCard__WEBPACK_IMPORTED_MODULE_4__["default"],VCardText: vuetify_lib_components_VCard__WEBPACK_IMPORTED_MODULE_5__.VCardText,VCardTitle: vuetify_lib_components_VCard__WEBPACK_IMPORTED_MODULE_5__.VCardTitle,VChip: vuetify_lib_components_VChip__WEBPACK_IMPORTED_MODULE_6__["default"],VCol: vuetify_lib_components_VGrid__WEBPACK_IMPORTED_MODULE_7__["default"],VIcon: vuetify_lib_components_VIcon__WEBPACK_IMPORTED_MODULE_8__["default"],VImg: vuetify_lib_components_VImg__WEBPACK_IMPORTED_MODULE_9__["default"],VListItem: vuetify_lib_components_VList__WEBPACK_IMPORTED_MODULE_10__["default"],VListItemAvatar: vuetify_lib_components_VList__WEBPACK_IMPORTED_MODULE_11__["default"],VListItemTitle: vuetify_lib_components_VList__WEBPACK_IMPORTED_MODULE_12__.VListItemTitle,VMain: vuetify_lib_components_VMain__WEBPACK_IMPORTED_MODULE_13__["default"],VOverlay: vuetify_lib_components_VOverlay__WEBPACK_IMPORTED_MODULE_14__["default"],VProgressCircular: vuetify_lib_components_VProgressCircular__WEBPACK_IMPORTED_MODULE_15__["default"],VRow: vuetify_lib_components_VGrid__WEBPACK_IMPORTED_MODULE_16__["default"],VSkeletonLoader: vuetify_lib_components_VSkeletonLoader__WEBPACK_IMPORTED_MODULE_17__["default"]})


/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/public/Topic.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./node_modules/vuetify/lib/components/VChip/VChip.js":
/*!************************************************************!*\
  !*** ./node_modules/vuetify/lib/components/VChip/VChip.js ***!
  \************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _src_components_VChip_VChip_sass__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../src/components/VChip/VChip.sass */ "./node_modules/vuetify/src/components/VChip/VChip.sass");
/* harmony import */ var _util_mixins__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../util/mixins */ "./node_modules/vuetify/lib/util/mixins.js");
/* harmony import */ var _transitions__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../transitions */ "./node_modules/vuetify/lib/components/transitions/index.js");
/* harmony import */ var _VIcon__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../VIcon */ "./node_modules/vuetify/lib/components/VIcon/index.js");
/* harmony import */ var _mixins_colorable__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../mixins/colorable */ "./node_modules/vuetify/lib/mixins/colorable/index.js");
/* harmony import */ var _mixins_groupable__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../../mixins/groupable */ "./node_modules/vuetify/lib/mixins/groupable/index.js");
/* harmony import */ var _mixins_themeable__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../mixins/themeable */ "./node_modules/vuetify/lib/mixins/themeable/index.js");
/* harmony import */ var _mixins_toggleable__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../../mixins/toggleable */ "./node_modules/vuetify/lib/mixins/toggleable/index.js");
/* harmony import */ var _mixins_routable__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../mixins/routable */ "./node_modules/vuetify/lib/mixins/routable/index.js");
/* harmony import */ var _mixins_sizeable__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../mixins/sizeable */ "./node_modules/vuetify/lib/mixins/sizeable/index.js");
/* harmony import */ var _util_console__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../../util/console */ "./node_modules/vuetify/lib/util/console.js");
// Styles

 // Components


 // Mixins






 // Utilities


/* @vue/component */

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,_util_mixins__WEBPACK_IMPORTED_MODULE_1__["default"])(_mixins_colorable__WEBPACK_IMPORTED_MODULE_2__["default"], _mixins_sizeable__WEBPACK_IMPORTED_MODULE_3__["default"], _mixins_routable__WEBPACK_IMPORTED_MODULE_4__["default"], _mixins_themeable__WEBPACK_IMPORTED_MODULE_5__["default"], (0,_mixins_groupable__WEBPACK_IMPORTED_MODULE_6__.factory)('chipGroup'), (0,_mixins_toggleable__WEBPACK_IMPORTED_MODULE_7__.factory)('inputValue')).extend({
  name: 'v-chip',
  props: {
    active: {
      type: Boolean,
      default: true
    },
    activeClass: {
      type: String,

      default() {
        if (!this.chipGroup) return '';
        return this.chipGroup.activeClass;
      }

    },
    close: Boolean,
    closeIcon: {
      type: String,
      default: '$delete'
    },
    closeLabel: {
      type: String,
      default: '$vuetify.close'
    },
    disabled: Boolean,
    draggable: Boolean,
    filter: Boolean,
    filterIcon: {
      type: String,
      default: '$complete'
    },
    label: Boolean,
    link: Boolean,
    outlined: Boolean,
    pill: Boolean,
    tag: {
      type: String,
      default: 'span'
    },
    textColor: String,
    value: null
  },
  data: () => ({
    proxyClass: 'v-chip--active'
  }),
  computed: {
    classes() {
      return {
        'v-chip': true,
        ..._mixins_routable__WEBPACK_IMPORTED_MODULE_4__["default"].options.computed.classes.call(this),
        'v-chip--clickable': this.isClickable,
        'v-chip--disabled': this.disabled,
        'v-chip--draggable': this.draggable,
        'v-chip--label': this.label,
        'v-chip--link': this.isLink,
        'v-chip--no-color': !this.color,
        'v-chip--outlined': this.outlined,
        'v-chip--pill': this.pill,
        'v-chip--removable': this.hasClose,
        ...this.themeClasses,
        ...this.sizeableClasses,
        ...this.groupClasses
      };
    },

    hasClose() {
      return Boolean(this.close);
    },

    isClickable() {
      return Boolean(_mixins_routable__WEBPACK_IMPORTED_MODULE_4__["default"].options.computed.isClickable.call(this) || this.chipGroup);
    }

  },

  created() {
    const breakingProps = [['outline', 'outlined'], ['selected', 'input-value'], ['value', 'active'], ['@input', '@active.sync']];
    /* istanbul ignore next */

    breakingProps.forEach(([original, replacement]) => {
      if (this.$attrs.hasOwnProperty(original)) (0,_util_console__WEBPACK_IMPORTED_MODULE_8__.breaking)(original, replacement, this);
    });
  },

  methods: {
    click(e) {
      this.$emit('click', e);
      this.chipGroup && this.toggle();
    },

    genFilter() {
      const children = [];

      if (this.isActive) {
        children.push(this.$createElement(_VIcon__WEBPACK_IMPORTED_MODULE_9__["default"], {
          staticClass: 'v-chip__filter',
          props: {
            left: true
          }
        }, this.filterIcon));
      }

      return this.$createElement(_transitions__WEBPACK_IMPORTED_MODULE_10__.VExpandXTransition, children);
    },

    genClose() {
      return this.$createElement(_VIcon__WEBPACK_IMPORTED_MODULE_9__["default"], {
        staticClass: 'v-chip__close',
        props: {
          right: true,
          size: 18
        },
        attrs: {
          'aria-label': this.$vuetify.lang.t(this.closeLabel)
        },
        on: {
          click: e => {
            e.stopPropagation();
            e.preventDefault();
            this.$emit('click:close');
            this.$emit('update:active', false);
          }
        }
      }, this.closeIcon);
    },

    genContent() {
      return this.$createElement('span', {
        staticClass: 'v-chip__content'
      }, [this.filter && this.genFilter(), this.$slots.default, this.hasClose && this.genClose()]);
    }

  },

  render(h) {
    const children = [this.genContent()];
    let {
      tag,
      data
    } = this.generateRouteLink();
    data.attrs = { ...data.attrs,
      draggable: this.draggable ? 'true' : undefined,
      tabindex: this.chipGroup && !this.disabled ? 0 : data.attrs.tabindex
    };
    data.directives.push({
      name: 'show',
      value: this.active
    });
    data = this.setBackgroundColor(this.color, data);
    const color = this.textColor || this.outlined && this.color;
    return h(tag, this.setTextColor(color, data), children);
  }

}));
//# sourceMappingURL=VChip.js.map

/***/ }),

/***/ "./node_modules/vuetify/lib/components/VSkeletonLoader/VSkeletonLoader.js":
/*!********************************************************************************!*\
  !*** ./node_modules/vuetify/lib/components/VSkeletonLoader/VSkeletonLoader.js ***!
  \********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _src_components_VSkeletonLoader_VSkeletonLoader_sass__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../src/components/VSkeletonLoader/VSkeletonLoader.sass */ "./node_modules/vuetify/src/components/VSkeletonLoader/VSkeletonLoader.sass");
/* harmony import */ var _mixins_elevatable__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../mixins/elevatable */ "./node_modules/vuetify/lib/mixins/elevatable/index.js");
/* harmony import */ var _mixins_measurable__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../mixins/measurable */ "./node_modules/vuetify/lib/mixins/measurable/index.js");
/* harmony import */ var _mixins_themeable__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../mixins/themeable */ "./node_modules/vuetify/lib/mixins/themeable/index.js");
/* harmony import */ var _util_mixins__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../util/mixins */ "./node_modules/vuetify/lib/util/mixins.js");
/* harmony import */ var _util_helpers__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../../util/helpers */ "./node_modules/vuetify/lib/util/helpers.js");
// Styles
 // Mixins



 // Utilities



/* @vue/component */

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ((0,_util_mixins__WEBPACK_IMPORTED_MODULE_1__["default"])(_mixins_elevatable__WEBPACK_IMPORTED_MODULE_2__["default"], _mixins_measurable__WEBPACK_IMPORTED_MODULE_3__["default"], _mixins_themeable__WEBPACK_IMPORTED_MODULE_4__["default"]).extend({
  name: 'VSkeletonLoader',
  props: {
    boilerplate: Boolean,
    loading: Boolean,
    tile: Boolean,
    transition: String,
    type: String,
    types: {
      type: Object,
      default: () => ({})
    }
  },
  computed: {
    attrs() {
      if (!this.isLoading) return this.$attrs;
      return !this.boilerplate ? {
        'aria-busy': true,
        'aria-live': 'polite',
        role: 'alert',
        ...this.$attrs
      } : {};
    },

    classes() {
      return {
        'v-skeleton-loader--boilerplate': this.boilerplate,
        'v-skeleton-loader--is-loading': this.isLoading,
        'v-skeleton-loader--tile': this.tile,
        ...this.themeClasses,
        ...this.elevationClasses
      };
    },

    isLoading() {
      return !('default' in this.$scopedSlots) || this.loading;
    },

    rootTypes() {
      return {
        actions: 'button@2',
        article: 'heading, paragraph',
        avatar: 'avatar',
        button: 'button',
        card: 'image, card-heading',
        'card-avatar': 'image, list-item-avatar',
        'card-heading': 'heading',
        chip: 'chip',
        'date-picker': 'list-item, card-heading, divider, date-picker-options, date-picker-days, actions',
        'date-picker-options': 'text, avatar@2',
        'date-picker-days': 'avatar@28',
        heading: 'heading',
        image: 'image',
        'list-item': 'text',
        'list-item-avatar': 'avatar, text',
        'list-item-two-line': 'sentences',
        'list-item-avatar-two-line': 'avatar, sentences',
        'list-item-three-line': 'paragraph',
        'list-item-avatar-three-line': 'avatar, paragraph',
        paragraph: 'text@3',
        sentences: 'text@2',
        table: 'table-heading, table-thead, table-tbody, table-tfoot',
        'table-heading': 'heading, text',
        'table-thead': 'heading@6',
        'table-tbody': 'table-row-divider@6',
        'table-row-divider': 'table-row, divider',
        'table-row': 'table-cell@6',
        'table-cell': 'text',
        'table-tfoot': 'text@2, avatar@2',
        text: 'text',
        ...this.types
      };
    }

  },
  methods: {
    genBone(text, children) {
      return this.$createElement('div', {
        staticClass: `v-skeleton-loader__${text} v-skeleton-loader__bone`
      }, children);
    },

    genBones(bone) {
      // e.g. 'text@3'
      const [type, length] = bone.split('@');

      const generator = () => this.genStructure(type); // Generate a length array based upon
      // value after @ in the bone string


      return Array.from({
        length
      }).map(generator);
    },

    // Fix type when this is merged
    // https://github.com/microsoft/TypeScript/pull/33050
    genStructure(type) {
      let children = [];
      type = type || this.type || '';
      const bone = this.rootTypes[type] || ''; // End of recursion, do nothing

      /* eslint-disable-next-line no-empty, brace-style */

      if (type === bone) {} // Array of values - e.g. 'heading, paragraph, text@2'
      else if (type.indexOf(',') > -1) return this.mapBones(type); // Array of values - e.g. 'paragraph@4'
        else if (type.indexOf('@') > -1) return this.genBones(type); // Array of values - e.g. 'card@2'
          else if (bone.indexOf(',') > -1) children = this.mapBones(bone); // Array of values - e.g. 'list-item@2'
            else if (bone.indexOf('@') > -1) children = this.genBones(bone); // Single value - e.g. 'card-heading'
              else if (bone) children.push(this.genStructure(bone));

      return [this.genBone(type, children)];
    },

    genSkeleton() {
      const children = [];
      if (!this.isLoading) children.push((0,_util_helpers__WEBPACK_IMPORTED_MODULE_5__.getSlot)(this));else children.push(this.genStructure());
      /* istanbul ignore else */

      if (!this.transition) return children;
      /* istanbul ignore next */

      return this.$createElement('transition', {
        props: {
          name: this.transition
        },
        // Only show transition when
        // content has been loaded
        on: {
          afterEnter: this.resetStyles,
          beforeEnter: this.onBeforeEnter,
          beforeLeave: this.onBeforeLeave,
          leaveCancelled: this.resetStyles
        }
      }, children);
    },

    mapBones(bones) {
      // Remove spaces and return array of structures
      return bones.replace(/\s/g, '').split(',').map(this.genStructure);
    },

    onBeforeEnter(el) {
      this.resetStyles(el);
      if (!this.isLoading) return;
      el._initialStyle = {
        display: el.style.display,
        transition: el.style.transition
      };
      el.style.setProperty('transition', 'none', 'important');
    },

    onBeforeLeave(el) {
      el.style.setProperty('display', 'none', 'important');
    },

    resetStyles(el) {
      if (!el._initialStyle) return;
      el.style.display = el._initialStyle.display || '';
      el.style.transition = el._initialStyle.transition;
      delete el._initialStyle;
    }

  },

  render(h) {
    return h('div', {
      staticClass: 'v-skeleton-loader',
      attrs: this.attrs,
      on: this.$listeners,
      class: this.classes,
      style: this.isLoading ? this.measurableStyles : undefined
    }, [this.genSkeleton()]);
  }

}));
//# sourceMappingURL=VSkeletonLoader.js.map

/***/ })

}]);