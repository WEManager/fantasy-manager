"use strict";
var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
var _a;
exports.__esModule = true;
require("./bootstrap");
require("../css/app.css");
var react_dom_1 = require("react-dom");
var inertia_react_1 = require("@inertiajs/inertia-react");
var progress_1 = require("@inertiajs/progress");
var inertia_helpers_1 = require("laravel-vite-plugin/inertia-helpers");
var appName = ((_a = window.document.getElementsByTagName('title')[0]) === null || _a === void 0 ? void 0 : _a.innerText) || 'Laravel';
inertia_react_1.createInertiaApp({
    title: function (title) { return title + " - " + appName; },
    resolve: function (name) {
        return inertia_helpers_1.resolvePageComponent("./Pages/" + name + ".tsx", import.meta.glob('./Pages/**/*.tsx'));
    },
    setup: function (_a) {
        var el = _a.el, App = _a.App, props = _a.props;
        return react_dom_1.render(React.createElement(App, __assign({}, props)), el);
    }
});
progress_1.InertiaProgress.init({
    delay: 0,
    color: 'orange',
    includeCSS: true,
    showSpinner: true
});
