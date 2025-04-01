import React from "react";
import "../../css/app.css"

const Layout = ({ children }) => {
    return (
        <main className="main">
            <div>{children}</div>
        </main>
    );
};

export default Layout;
