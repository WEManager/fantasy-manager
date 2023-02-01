"use strict";
exports.__esModule = true;
var react_1 = require("react");
function Input(_a) {
    var _b = _a.type, type = _b === void 0 ? 'text' : _b, name = _a.name, value = _a.value, className = _a.className, autoComplete = _a.autoComplete, _c = _a.required, required = _c === void 0 ? false : _c, _d = _a.isFocused, isFocused = _d === void 0 ? false : _d, handleChange = _a.handleChange;
    var input = react_1.useRef(null);
    react_1.useEffect(function () {
        var _a;
        if (isFocused) {
            (_a = input.current) === null || _a === void 0 ? void 0 : _a.focus();
        }
    }, []);
    return (React.createElement("div", { className: "flex flex-col items-start" },
        React.createElement("input", { type: type, name: name, value: value, className: "border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm " + className, ref: input, autoComplete: autoComplete, required: required, onChange: function (e) { return handleChange(e); } })));
}
exports["default"] = Input;
