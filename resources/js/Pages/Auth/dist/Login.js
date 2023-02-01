"use strict";
exports.__esModule = true;
var react_1 = require("react");
var inertia_react_1 = require("@inertiajs/inertia-react");
var ziggy_js_1 = require("ziggy-js");
var Button_1 = require("@/components/Button");
var Checkbox_1 = require("@/components/Checkbox");
var Guest_1 = require("@/Layouts/Guest");
var Input_1 = require("@/components/Input");
var InputError_1 = require("@/components/InputError");
var Label_1 = require("@/components/Label");
function Login(_a) {
    var status = _a.status, canResetPassword = _a.canResetPassword;
    var _b = inertia_react_1.useForm({
        email: '',
        password: '',
        remember: ''
    }), data = _b.data, setData = _b.setData, post = _b.post, processing = _b.processing, errors = _b.errors, reset = _b.reset;
    react_1.useEffect(function () {
        return function () {
            reset('password');
        };
    }, []);
    var onHandleChange = function (event) {
        setData(event.target.name, event.target.type === 'checkbox' ? String(event.target.checked) : event.target.value);
    };
    var submit = function (event) {
        event.preventDefault();
        post(ziggy_js_1["default"]('login'));
    };
    return (React.createElement(Guest_1["default"], null,
        React.createElement(inertia_react_1.Head, { title: "Log in" }),
        status && React.createElement("div", { className: "mb-4 font-medium text-sm text-green-600" }, status),
        React.createElement("form", { onSubmit: submit },
            React.createElement("div", null,
                React.createElement(Label_1["default"], { forInput: "email", value: "Email" }),
                React.createElement(Input_1["default"], { type: "text", name: "email", value: data.email, className: "mt-1 block w-full", autoComplete: "username", handleChange: onHandleChange, isFocused: true }),
                React.createElement(InputError_1["default"], { message: errors.email, className: "mt-2" })),
            React.createElement("div", { className: "mt-4" },
                React.createElement(Label_1["default"], { forInput: "password", value: "Password" }),
                React.createElement(Input_1["default"], { type: "password", name: "password", value: data.password, className: "mt-1 block w-full", autoComplete: "current-password", handleChange: onHandleChange }),
                React.createElement(InputError_1["default"], { message: errors.password, className: "mt-2" })),
            React.createElement("div", { className: "block mt-4" },
                React.createElement(Label_1["default"], { forInput: "remember", className: "flex items-center" },
                    React.createElement(Checkbox_1["default"], { name: "remember", value: data.remember, handleChange: onHandleChange }),
                    React.createElement("span", { className: "ml-2 text-sm text-gray-600" }, "Remember me"))),
            React.createElement("div", { className: "flex items-center justify-end mt-4" },
                canResetPassword && (React.createElement(inertia_react_1.Link, { href: ziggy_js_1["default"]('password.request'), className: "underline text-sm text-gray-600 hover:text-gray-900" }, "Forgot your password?")),
                React.createElement(Button_1["default"], { className: "ml-4", processing: processing }, "Log in")))));
}
exports["default"] = Login;
