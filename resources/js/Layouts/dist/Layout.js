"use strict";
exports.__esModule = true;
var components_1 = require("@/components");
function Layout(_a) {
    var children = _a.children, header = _a.header;
    return (React.createElement(React.Fragment, null,
        React.createElement(components_1.Nav, null),
        header && (React.createElement("header", { className: "bg-white dark:bg-gray-700 shadow" },
            React.createElement("div", { className: "max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8" }, header))),
        React.createElement("main", null, children)));
}
exports["default"] = Layout;
