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
function Register() {
    var _a = inertia_react_1.useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
    }), data = _a.data, setData = _a.setData, post = _a.post, processing = _a.processing, errors = _a.errors, reset = _a.reset;
    react_1.useEffect(function () {
        return function () {
            reset('password', 'password_confirmation');
        };
    }, []);
    var onHandleChange = function (event) {
        setData(event.target.name, event.target.value);
    };
    var submit = function (event) {
        event.preventDefault();
        post(ziggy_js_1["default"]('register'));
    };
    return (React.createElement(Guest_1["default"], null,
        React.createElement(inertia_react_1.Head, { title: "Register" }),
        React.createElement("form", { onSubmit: submit },
            React.createElement("div", null,
                React.createElement(Label_1["default"], { forInput: "name", value: "Name" }),
                React.createElement(Input_1["default"], { type: "text", name: "name", value: data.name, className: "mt-1 block w-full", autoComplete: "name", isFocused: true, handleChange: onHandleChange, required: true }),
                React.createElement(InputError_1["default"], { message: errors.name, className: "mt-2" })),
            React.createElement("div", { className: "mt-4" },
                React.createElement(Label_1["default"], { forInput: "email", value: "Email" }),
                React.createElement(Input_1["default"], { type: "email", name: "email", value: data.email, className: "mt-1 block w-full", autoComplete: "username", handleChange: onHandleChange, required: true }),
                React.createElement(InputError_1["default"], { message: errors.email, className: "mt-2" })),
            React.createElement("div", { className: "mt-4" },
                React.createElement(Label_1["default"], { forInput: "password", value: "Password" }),
                React.createElement(Input_1["default"], { type: "password", name: "password", value: data.password, className: "mt-1 block w-full", autoComplete: "new-password", handleChange: onHandleChange, required: true }),
                React.createElement(InputError_1["default"], { message: errors.password, className: "mt-2" })),
            React.createElement("div", { className: "mt-4" },
                React.createElement(Label_1["default"], { forInput: "password_confirmation", value: "Confirm Password" }),
                React.createElement(Input_1["default"], { type: "password", name: "password_confirmation", value: data.password_confirmation, className: "mt-1 block w-full", handleChange: onHandleChange, required: true }),
                React.createElement(InputError_1["default"], { message: errors.password_confirmation, className: "mt-2" })),
            React.createElement("div", { className: "flex items-center justify-end mt-4" },
                React.createElement(inertia_react_1.Link, { href: ziggy_js_1["default"]('login'), className: "underline text-sm text-gray-600 hover:text-gray-900" }, "Already registered?"),
                React.createElement(Button_1["default"], { className: "ml-4", processing: processing }, "Register")))));
}
exports["default"] = Register;
