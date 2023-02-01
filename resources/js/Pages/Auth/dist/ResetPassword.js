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
function ResetPassword(_a) {
    var token = _a.token, email = _a.email;
    var _b = inertia_react_1.useForm({
        token: token,
        email: email,
        password: '',
        password_confirmation: ''
    }), data = _b.data, setData = _b.setData, post = _b.post, processing = _b.processing, errors = _b.errors, reset = _b.reset;
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
        post(ziggy_js_1["default"]('password.update'));
    };
    return (React.createElement(Guest_1["default"], null,
        React.createElement(inertia_react_1.Head, { title: "Reset Password" }),
        React.createElement("form", { onSubmit: submit },
            React.createElement("div", null,
                React.createElement(Label_1["default"], { forInput: "email", value: "Email" }),
                React.createElement(Input_1["default"], { type: "email", name: "email", value: data.email, className: "mt-1 block w-full", autoComplete: "username", handleChange: onHandleChange }),
                React.createElement(InputError_1["default"], { message: errors.email, className: "mt-2" })),
            React.createElement("div", { className: "mt-4" },
                React.createElement(Label_1["default"], { forInput: "password", value: "Password" }),
                React.createElement(Input_1["default"], { type: "password", name: "password", value: data.password, className: "mt-1 block w-full", autoComplete: "new-password", isFocused: true, handleChange: onHandleChange }),
                React.createElement(InputError_1["default"], { message: errors.password, className: "mt-2" })),
            React.createElement("div", { className: "mt-4" },
                React.createElement(Label_1["default"], { forInput: "password_confirmation", value: "Confirm Password" }),
                React.createElement(Input_1["default"], { type: "password", name: "password_confirmation", value: data.password_confirmation, className: "mt-1 block w-full", autoComplete: "new-password", handleChange: onHandleChange }),
                React.createElement(InputError_1["default"], { message: errors.password_confirmation, className: "mt-2" })),
            React.createElement("div", { className: "flex items-center justify-end mt-4" },
                React.createElement(Button_1["default"], { className: "ml-4", processing: processing }, "Reset Password")))));
}
exports["default"] = ResetPassword;
