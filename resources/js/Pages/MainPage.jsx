import React from 'react';
import { Link } from "@inertiajs/react";

const MyComponent = () => {

    return (
        <div style={{
            display: 'flex',
            flexDirection: 'column',
            gap: '30px',
            marginTop: '25%',
            alignItems: 'center',
        }}>Which list of instance do you want to look?
            <div style={{
                display: 'flex',
                gap: '30px',
            }}>
                <Link
                    href="/products"
                    style={{
                        display: "inline-block",
                        marginTop: "20px",
                        padding: "10px 20px",
                        backgroundColor: "grey",
                        color: "white",
                        textDecoration: "none",
                        borderRadius: "5px",
                    }}
                >
                    Go to Products Page
                </Link>
                <Link
                    href="/services"
                    style={{
                        display: "inline-block",
                        marginTop: "20px",
                        padding: "10px 20px",
                        backgroundColor: "grey",
                        color: "white",
                        textDecoration: "none",
                        borderRadius: "5px",
                    }}
                >
                    Go to Services Page
                </Link>
            </div>
        </div>
    );

};

export default MyComponent;
