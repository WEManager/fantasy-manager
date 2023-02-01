"use strict";
exports.__esModule = true;
function Button(_a) {
    var _b = _a.type, type = _b === void 0 ? 'submit' : _b, className = _a.className, _c = _a.processing, processing = _c === void 0 ? false : _c, children = _a.children;
    return (React.createElement("button", { type: type, className: "inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-gray-900 transition ease-in-out duration-150 " + (processing && 'opacity-25') + " " + className, disabled: processing }, children));
}
exports["default"] = Button;
