"use strict";
exports.__esModule = true;
var inertia_react_1 = require("@inertiajs/inertia-react");
var ziggy_js_1 = require("ziggy-js");
var Button_1 = require("@/components/Button");
var Guest_1 = require("@/Layouts/Guest");
var Input_1 = require("@/Components/Input");
var InputError_1 = require("@/components/InputError");
function ForgotPassword(_a) {
    var status = _a.status;
    var _b = inertia_react_1.useForm({
        email: ''
    }), data = _b.data, setData = _b.setData, post = _b.post, processing = _b.processing, errors = _b.errors;
    var onHandleChange = function (event) {
        setData(event.target.name, event.target.value);
    };
    var submit = function (event) {
        event.preventDefault();
        post(ziggy_js_1["default"]('password.email'));
    };
    return (React.createElement(Guest_1["default"], null,
        React.createElement(React.Fragment, null,
            React.createElement(inertia_react_1.Head, { title: "Forgot Password" }),
            React.createElement("div", { className: "mb-4 text-sm text-gray-500 leading-normal" }, "Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one."),
            status && React.createElement("div", { className: "mb-4 font-medium text-sm text-green-600" }, status),
            React.createElement("form", { onSubmit: submit },
                React.createElement(Input_1["default"], { type: "text", name: "email", value: data.email, className: "mt-1 block w-full", isFocused: true, handleChange: onHandleChange }),
                React.createElement(InputError_1["default"], { message: errors.email, className: "mt-2" }),
                React.createElement("div", { className: "flex items-center justify-end mt-4" },
                    React.createElement(Button_1["default"], { className: "ml-4", processing: processing }, "Email Password Reset Link"))))));
}
exports["default"] = ForgotPassword;
