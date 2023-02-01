"use strict";
exports.__esModule = true;
var inertia_react_1 = require("@inertiajs/inertia-react");
var ApplicationLogo_1 = require("@/components/ApplicationLogo");
function Guest(_a) {
    var children = _a.children;
    return (React.createElement("div", { className: "min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100" },
        React.createElement("div", null,
            React.createElement(inertia_react_1.Link, { href: "/" },
                React.createElement(ApplicationLogo_1["default"], { className: "w-20 h-20 fill-current text-gray-500" }))),
        React.createElement("div", { className: "w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg" }, children)));
}
exports["default"] = Guest;
