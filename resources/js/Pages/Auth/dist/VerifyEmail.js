"use strict";
exports.__esModule = true;
var inertia_react_1 = require("@inertiajs/inertia-react");
var ziggy_js_1 = require("ziggy-js");
var Button_1 = require("@/components/Button");
var Guest_1 = require("@/Layouts/Guest");
function VerifyEmail(_a) {
    var status = _a.status;
    var _b = inertia_react_1.useForm(), post = _b.post, processing = _b.processing;
    var submit = function (event) {
        event.preventDefault();
        post(ziggy_js_1["default"]('verification.send'));
    };
    return (React.createElement(Guest_1["default"], null,
        React.createElement(inertia_react_1.Head, { title: "Email Verification" }),
        React.createElement("div", { className: "mb-4 text-sm text-gray-600" }, "Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another."),
        status === 'verification-link-sent' && (React.createElement("div", { className: "mb-4 font-medium text-sm text-green-600" }, "A new verification link has been sent to the email address you provided during registration.")),
        React.createElement("form", { onSubmit: submit },
            React.createElement("div", { className: "mt-4 flex items-center justify-between" },
                React.createElement(Button_1["default"], { processing: processing }, "Resend Verification Email"),
                React.createElement(inertia_react_1.Link, { href: ziggy_js_1["default"]('logout'), method: "post", as: "button", className: "underline text-sm text-gray-600 hover:text-gray-900" }, "Log Out")))));
}
exports["default"] = VerifyEmail;
