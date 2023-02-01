"use strict";
exports.__esModule = true;
var inertia_react_1 = require("@inertiajs/inertia-react");
var flowbite_react_1 = require("flowbite-react");
var ziggy_js_1 = require("ziggy-js");
var Layout_1 = require("@/Layouts/Layout");
function TournamentShow(_a) {
    var _b;
    var tournament = _a.tournament;
    return (React.createElement(Layout_1["default"], { header: React.createElement("h2", { className: "font-semibold text-xl text-white leading-tight" }, tournament.name) },
        React.createElement(inertia_react_1.Head, { title: tournament.name }),
        React.createElement("section", { className: "container mx-auto" },
            React.createElement("div", { className: "mt-4" }, (_b = tournament === null || tournament === void 0 ? void 0 : tournament.groups) === null || _b === void 0 ? void 0 : _b.map(function (group) { return (React.createElement(flowbite_react_1.Table, { key: group.id },
                React.createElement(flowbite_react_1.Table.Head, null,
                    React.createElement(flowbite_react_1.Table.HeadCell, null, "Pos."),
                    React.createElement(flowbite_react_1.Table.HeadCell, null, "Clube"),
                    React.createElement(flowbite_react_1.Table.HeadCell, null, "Jogos"),
                    React.createElement(flowbite_react_1.Table.HeadCell, null, "Vit."),
                    React.createElement(flowbite_react_1.Table.HeadCell, null, "Emp."),
                    React.createElement(flowbite_react_1.Table.HeadCell, null, "Der."),
                    React.createElement(flowbite_react_1.Table.HeadCell, null, "GP"),
                    React.createElement(flowbite_react_1.Table.HeadCell, null, "GC"),
                    React.createElement(flowbite_react_1.Table.HeadCell, null, "SG"),
                    React.createElement(flowbite_react_1.Table.HeadCell, null, "Pontos")),
                React.createElement(flowbite_react_1.Table.Body, { className: "divide-y" }, group.standings.map(function (standing, index) {
                    var _a, _b;
                    return (React.createElement(flowbite_react_1.Table.Row, { key: standing.club_id, className: "bg-white dark:border-gray-700 dark:bg-gray-800 " + (((_a = tournament === null || tournament === void 0 ? void 0 : tournament.qualifications) === null || _a === void 0 ? void 0 : _a[index].status) !== 'ended'
                            ? ((_b = tournament === null || tournament === void 0 ? void 0 : tournament.qualifications) === null || _b === void 0 ? void 0 : _b[index].status) === 'relegated'
                                ? 'dark:bg-red-500'
                                : 'dark:bg-yellow-500'
                            : '') },
                        React.createElement(flowbite_react_1.Table.Cell, null, ++index),
                        React.createElement(flowbite_react_1.Table.Cell, { className: "whitespace-nowrap font-medium text-gray-900 dark:text-white" },
                            React.createElement(inertia_react_1.Link, { href: ziggy_js_1["default"]('club.show', standing.club) }, standing.club.name)),
                        React.createElement(flowbite_react_1.Table.Cell, null, standing.won + standing.tie + standing.lost),
                        React.createElement(flowbite_react_1.Table.Cell, null, standing.won),
                        React.createElement(flowbite_react_1.Table.Cell, null, standing.tie),
                        React.createElement(flowbite_react_1.Table.Cell, null, standing.lost),
                        React.createElement(flowbite_react_1.Table.Cell, null, standing.scored),
                        React.createElement(flowbite_react_1.Table.Cell, null, standing.conceded),
                        React.createElement(flowbite_react_1.Table.Cell, null, standing.scored - standing.conceded),
                        React.createElement(flowbite_react_1.Table.Cell, { className: "whitespace-nowrap font-medium text-gray-900 dark:text-white" }, standing.points)));
                })))); })))));
}
exports["default"] = TournamentShow;
