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
exports.__esModule = true;
var server_1 = require("react-dom/server");
var inertia_react_1 = require("@inertiajs/inertia-react");
var server_2 = require("@inertiajs/server");
var inertia_helpers_1 = require("laravel-vite-plugin/inertia-helpers");
// import route, { RouteParam, RouteParamsWithQueryOverload } from 'ziggy-js'
// import route from '../../vendor/tightenco/ziggy/dist/index.m'
var appName = 'WEManager Fantazy Manager';
server_2["default"](function (page) {
    return inertia_react_1.createInertiaApp({
        page: page,
        render: server_1.renderToString,
        title: function (title) { return title + " - " + appName; },
        resolve: function (name) {
            return inertia_helpers_1.resolvePageComponent("./Pages/" + name + ".jsx", import.meta.glob('./Pages/**/*.tsx'));
        },
        setup: function (_a) {
            // global.route = (name: string, params?: RouteParamsWithQueryOverload | RouteParam, absolute?: boolean) =>
            //   route(name, params, absolute, {
            //     ...(page.props.ziggy as any),
            //     location: new URL(page.props.ziggy.location),
            //   })
            var App = _a.App, props = _a.props;
            return React.createElement(App, __assign({}, props));
        }
    });
});
