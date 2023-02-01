"use strict";
exports.__esModule = true;
var inertia_react_1 = require("@inertiajs/inertia-react");
var flowbite_react_1 = require("flowbite-react");
var ziggy_js_1 = require("ziggy-js");
var inertia_1 = require("@inertiajs/inertia");
var Layout_1 = require("@/Layouts/Layout");
function ClubIndex(_a) {
    var response = _a.response;
    var clubs = response.data;
    var handlePageChange = function (pageNumber) {
        inertia_1.Inertia.get('/c', { page: pageNumber }, { replace: true });
    };
    return (React.createElement(Layout_1["default"], null,
        React.createElement(inertia_react_1.Head, { title: "Clubes" }),
        React.createElement("div", { className: "container mx-auto" },
            React.createElement("div", { className: "grid grid-cols-5 gap-3 mt-5" }, clubs.map(function (club) { return (React.createElement(inertia_react_1.Link, { href: ziggy_js_1["default"]('club.show', club), key: club.id, className: "rounded border border-gray-200 p-4 flex flex-col items-center", style: { backgroundColor: club.colors[0] } },
                React.createElement("h3", { className: "font-bold", style: { color: club.colors[1] } }, club.name))); })),
            React.createElement("div", { className: "flex items-center justify-center text-center" },
                React.createElement(flowbite_react_1.Pagination, { currentPage: response.current_page, totalPages: response.last_page, onPageChange: handlePageChange })))));
}
exports["default"] = ClubIndex;
