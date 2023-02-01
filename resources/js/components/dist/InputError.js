"use strict";
exports.__esModule = true;
function InputError(_a) {
    var message = _a.message, _b = _a.className, className = _b === void 0 ? '' : _b;
    if (message)
        return React.createElement("p", { className: 'text-sm text-red-600 ' + className }, message);
    return React.createElement(React.Fragment, null);
}
exports["default"] = InputError;
