"use strict";
exports.__esModule = true;
var react_1 = require("react");
var inertia_react_1 = require("@inertiajs/inertia-react");
var ziggy_js_1 = require("ziggy-js");
var Button_1 = require("@/components/Button");
var Guest_1 = require("@/Layouts/Guest");
var Input_1 = require("@/Components/Input");
var InputError_1 = require("@/components/InputError");
var Label_1 = require("@/Components/Label");
function ConfirmPassword() {
    var _a = inertia_react_1.useForm({
        password: ''
    }), data = _a.data, setData = _a.setData, post = _a.post, processing = _a.processing, errors = _a.errors, reset = _a.reset;
    react_1.useEffect(function () {
        return function () {
            reset('password');
        };
    }, []);
    var onHandleChange = function (event) {
        setData(event.target.name, event.target.value);
    };
    var submit = function (event) {
        event.preventDefault();
        post(ziggy_js_1["default"]('password.confirm'));
    };
    return (React.createElement(Guest_1["default"], null,
        React.createElement(inertia_react_1.Head, { title: "Confirm Password" }),
        React.createElement("div", { className: "mb-4 text-sm text-gray-600" }, "This is a secure area of the application. Please confirm your password before continuing."),
        React.createElement("form", { onSubmit: submit },
            React.createElement("div", { className: "mt-4" },
                React.createElement(Label_1["default"], { forInput: "password", value: "Password" }),
                React.createElement(Input_1["default"], { type: "password", name: "password", value: data.password, className: "mt-1 block w-full", isFocused: true, handleChange: onHandleChange }),
                React.createElement(InputError_1["default"], { message: errors.password, className: "mt-2" })),
            React.createElement("div", { className: "flex items-center justify-end mt-4" },
                React.createElement(Button_1["default"], { className: "ml-4", processing: processing }, "Confirm")))));
}
exports["default"] = ConfirmPassword;
