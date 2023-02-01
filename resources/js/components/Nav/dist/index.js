"use strict";
exports.__esModule = true;
var inertia_react_1 = require("@inertiajs/inertia-react");
var flowbite_react_1 = require("flowbite-react");
var ziggy_js_1 = require("ziggy-js");
var Nav = function () {
    var auth = inertia_react_1.usePage().props.auth;
    return (React.createElement(flowbite_react_1.Navbar, null,
        React.createElement(flowbite_react_1.Navbar.Brand, null,
            React.createElement("img", { src: "https://flowbite.com/docs/images/logo.svg", className: "mr-3 h-6 sm:h-9", alt: "Flowbite Logo" }),
            React.createElement("span", { className: "self-center whitespace-nowrap text-xl font-semibold dark:text-white" }, "WEManager")),
        React.createElement("div", { className: "flex md:order-2" },
            !(auth === null || auth === void 0 ? void 0 : auth.user) ? (React.createElement(inertia_react_1.Link, { href: ziggy_js_1["default"]('login') },
                React.createElement(flowbite_react_1.Button, null, "Entrar"))) : (React.createElement(flowbite_react_1.Dropdown, { arrowIcon: false, inline: true, label: React.createElement(flowbite_react_1.Avatar, { alt: "User settings", img: "https://flowbite.com/docs/images/people/profile-picture-5.jpg", rounded: true }) },
                React.createElement(flowbite_react_1.Dropdown.Header, null,
                    React.createElement("span", { className: "block text-sm" }, auth.user.name),
                    React.createElement("span", { className: "block truncate text-sm font-medium" }, auth.user.email)),
                React.createElement(flowbite_react_1.Dropdown.Item, null, "Dashboard"),
                React.createElement(flowbite_react_1.Dropdown.Item, null, "Settings"),
                React.createElement(flowbite_react_1.Dropdown.Item, null, "Earnings"),
                React.createElement(flowbite_react_1.Dropdown.Divider, null),
                React.createElement(flowbite_react_1.Dropdown.Item, null,
                    React.createElement(inertia_react_1.Link, { href: ziggy_js_1["default"]('logout'), method: "post", as: "button" }, "Sair")))),
            React.createElement(flowbite_react_1.Navbar.Toggle, null)),
        React.createElement(flowbite_react_1.Navbar.Collapse, null,
            React.createElement(flowbite_react_1.Navbar.Link, { href: "/navbars", active: true }, "Home"),
            React.createElement(flowbite_react_1.Navbar.Link, { href: "/navbars" }, "About"),
            React.createElement(flowbite_react_1.Navbar.Link, { href: "/navbars" }, "Services"),
            React.createElement(flowbite_react_1.Navbar.Link, { href: "/navbars" }, "Pricing"),
            React.createElement(flowbite_react_1.Navbar.Link, { href: "/navbars" }, "Contact"))));
};
exports["default"] = Nav;
