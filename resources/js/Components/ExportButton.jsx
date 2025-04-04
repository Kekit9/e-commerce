import { useState } from 'react';
import {getCsrfToken} from "../CSRF/csrfToken.jsx";

const ExportButton = () => {
    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState('');

    const csrfToken = getCsrfToken();
    if (!csrfToken) {
        alert("CSRF token not found!");
        return;
    }

    const handleExport = async () => {
        try {
            await fetch('/sanctum/csrf-cookie', {
                credentials: 'include'
            });

            const response = await fetch('/export-catalog', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'include'
            });

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                alert(`Invalid response: ${text.substring(0, 100)}...`);
            }

            const data = await response.json();
            console.log('Export success:', data);

        } catch (error) {
            console.error('Export failed:', error);
            alert(`Error: ${error.message}`);
        }
    };

    return (
        <div
            className="export-section"
            style={{ display: 'flex', marginBottom: '20px', justifySelf: 'flex-end' }}
        >
            <button
                onClick={handleExport}
                disabled={loading}
                className="btn btn-primary"
            >
                {loading ? (
                    <span>Exporting... <i className="fas fa-spinner fa-spin"></i></span>
                ) : (
                    <span><i className="fas fa-file-export"></i> Export Catalog</span>
                )}
            </button>

            {message && (
                <div className="alert alert-success mt-2">
                    {message}
                </div>
            )}

        </div>
    );
};

export default ExportButton;
