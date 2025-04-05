import React, {useState} from "react";
import Layout from "../Components/Layout.jsx";
import user_icon from "../Assets/person.png"
import password_icon from "../Assets/password.png"
import email_icon from "../Assets/email.png"
import "../../css/LoginSignup.css"
import {getCsrfToken} from "../CSRF/csrfToken.jsx";
import {router} from "@inertiajs/react";

const LoginSignupPage = () => {
    const [action, setAction] = useState("Sign Up");
    const [formData, setFormData] = useState({
        name: "",
        email: "",
        password: "",
    });

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setFormData((prevState) => ({
            ...prevState,
            [name]: value,
        }));
    };

    const handleSubmit = async (e, action) => {
        e.preventDefault();

        const csrfToken = getCsrfToken();
        if (!csrfToken) {
            alert("CSRF token not found!");
            return;
        }

        if (action === "Sign Up") {
            try {
                const response = await fetch("/registration", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify(formData),
                });

                const data = await response.json();

                if (response.ok) {
                    alert(data.message);
                    setAction(data.action);
                } else {
                    alert("Registration failed: " + data.message);
                }

            } catch (error) {
                alert("An error occurred during registration.");
            }
        } else if (action === "Login") {
            try {
                const loginData = {
                    email: formData.email,
                    password: formData.password,
                };
                const response = await fetch("/authorization", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    credentials: 'include',
                    body: JSON.stringify(loginData),
                })

                const data = await response.json();
                localStorage.setItem('userRole', data.user.role);
                localStorage.setItem('authToken', data.token);

                if (response.ok) {
                    if (data.redirect) {
                        router.visit(data.redirect);
                    }
                } else {
                    alert("Login failed: " + data.message);
                }

            } catch (error) {
                alert("An error occurred during login.");
            }
        }

    };

    const handleActionButtonClick = (e) => {
        e.preventDefault();
        if (action === "Sign Up") {
            handleSubmit(e, 'Sign Up');
        } else if (action === "Login") {
            handleSubmit(e, 'Login');
        }
    };

    return (
        <Layout>
            <div className="container">
                <div className="header">
                    <div className="text">{action}</div>
                    <div className="underline"></div>
                </div>
                <form onSubmit={handleSubmit}>
                    <div className="inputs" onSubmit={handleSubmit}>
                        {action === "Login" ? <div></div> : <div className="input">
                            <img src={user_icon} alt=""/>
                            <input
                                type="text"
                                placeholder="Name"
                                name="name"
                                value={formData.name}
                                onChange={handleInputChange}
                                required
                            />
                        </div>}

                        <div className="input">
                            <img src={email_icon} alt=""/>
                            <input
                                type="email"
                                placeholder="Email address"
                                name="email"
                                value={formData.email}
                                onChange={handleInputChange}
                                required
                            />
                        </div>
                        <div className="input">
                            <img src={password_icon} alt=""/>
                            <input
                                type="password"
                                placeholder="Password"
                                name="password"
                                value={formData.password}
                                onChange={handleInputChange}
                                required
                            />
                        </div>
                    </div>
                </form>
                <div className="submit-container">
                    <div
                        className={action === "Login" ? "submit gray" : "submit"}
                        onClick={() => setAction("Sign Up")}
                    >
                        Sign Up
                    </div>
                    <div
                        className={action === "Sign Up" ? "submit gray" : "submit"}
                        onClick={() => setAction("Login")}
                    >
                        Login
                    </div>
                </div>
                <div
                    className="submit"
                    onClick={handleActionButtonClick}
                >
                    {action === "Sign Up" ? "Register" : "Entrance"}
                </div>
            </div>
        </Layout>
    );
};

export default LoginSignupPage;
