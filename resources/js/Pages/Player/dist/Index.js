"use strict";
exports.__esModule = true;
var inertia_react_1 = require("@inertiajs/inertia-react");
var flowbite_react_1 = require("flowbite-react");
var ziggy_js_1 = require("ziggy-js");
var inertia_1 = require("@inertiajs/inertia");
var Layout_1 = require("@/Layouts/Layout");
function PlayerIndex(_a) {
    var response = _a.response;
    console.log('🚀 ~ file: Index.tsx ~ line 14 ~ PlayerIndex ~ response', response);
    var players = response.data;
    var handlePageChange = function (pageNumber) {
        inertia_1.Inertia.get('/p', { page: pageNumber }, { replace: true });
    };
    return (React.createElement(Layout_1["default"], null,
        React.createElement(inertia_react_1.Head, { title: "Clubes" }),
        React.createElement("div", { className: "container mx-auto" }, !response.total ? (React.createElement("p", null, "vazio")) : (React.createElement(React.Fragment, null,
            React.createElement(flowbite_react_1.Table, null,
                React.createElement(flowbite_react_1.Table.Head, null,
                    React.createElement(flowbite_react_1.Table.HeadCell, null, "Nome")),
                React.createElement(flowbite_react_1.Table.Body, { className: "divide-y" }, players.map(function (player) { return (React.createElement(flowbite_react_1.Table.Row, { key: player.id, className: "bg-white dark:border-gray-700 dark:bg-gray-800 " },
                    React.createElement(inertia_react_1.Link, { href: ziggy_js_1["default"]('player.show', player) }, player.know_as))); }))),
            React.createElement("div", { className: "flex items-center justify-center text-center" },
                React.createElement(flowbite_react_1.Pagination, { currentPage: response.current_page, totalPages: response.last_page, onPageChange: handlePageChange })))))));
}
exports["default"] = PlayerIndex;
