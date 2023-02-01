"use strict";
exports.__esModule = true;
function Label(_a) {
    var forInput = _a.forInput, value = _a.value, className = _a.className, children = _a.children;
    return (React.createElement("label", { htmlFor: forInput, className: "block font-medium text-sm text-gray-700 " + className }, value ? value : children));
}
exports["default"] = Label;
