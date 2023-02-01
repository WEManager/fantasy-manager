"use strict";
exports.__esModule = true;
var inertia_react_1 = require("@inertiajs/inertia-react");
var ziggy_js_1 = require("ziggy-js");
var Layout_1 = require("@/Layouts/Layout");
function Home(_a) {
    var auth = _a.auth, clubs = _a.clubs;
    return (React.createElement(Layout_1["default"]
    // auth={auth}
    // errors={errors}
    // header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Home</h2>}
    , null,
        React.createElement(inertia_react_1.Head, { title: "Home" }),
        React.createElement("div", { className: "py-12" },
            React.createElement("div", { className: "max-w-7xl mx-auto sm:px-6 lg:px-8" },
                React.createElement("div", { className: "bg-white overflow-hidden shadow-sm sm:rounded-lg" },
                    React.createElement("div", { className: "p-6 bg-white border-b border-gray-200" },
                        React.createElement("h2", { className: "text-lg mb-4" }, "List of available clubs"),
                        React.createElement("div", { className: "grid grid-cols-4 gap-4" }, clubs.map(function (club) {
                            var _a, _b;
                            return (React.createElement("div", { className: "flex gap-1 items-center", key: club.id },
                                React.createElement("div", { className: "w-5 aspect-square rounded-full border-solid border-2", style: {
                                        backgroundColor: (_a = club.colors) === null || _a === void 0 ? void 0 : _a[0],
                                        borderColor: (_b = club.colors) === null || _b === void 0 ? void 0 : _b[1]
                                    } }),
                                React.createElement("img", { className: "w-3", src: "/images/vendor/flag-icon-css/flags/4x3/" + club.locale.toLowerCase() + ".svg", title: club.locale, alt: club.locale + " flag" }),
                                React.createElement("a", { href: ziggy_js_1["default"]('club.show', [club]) }, club.name),
                                auth.user && !auth.user.club ? (React.createElement(inertia_react_1.Link, { href: ziggy_js_1["default"]('apply_for_job', [club]), className: "text-gray-500 ml-auto underline text-sm" }, "Apply for job")) : (React.createElement("span", { className: "ml-auto" }, "No manager"))));
                        }))))))));
}
exports["default"] = Home;
