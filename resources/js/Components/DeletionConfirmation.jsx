import { useEffect } from 'react';

const DeletionConfirmation = ({
                           isOpen,
                           title,
                           message,
                           onConfirm,
                           onCancel
                       }) => {
    useEffect(() => {
        if (isOpen) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'auto';
        }

        return () => {
            document.body.style.overflow = 'auto';
        };
    }, [isOpen]);

    if (!isOpen) return null;

    return (
        <div style={{
            position: 'fixed',
            top: 0,
            left: 0,
            right: 0,
            bottom: 0,
            backgroundColor: 'rgba(0,0,0,0.5)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            zIndex: 1000
        }}>
            <div style={{
                backgroundColor: 'white',
                padding: '20px',
                borderRadius: '8px',
                maxWidth: '400px',
                width: '100%'
            }}>
                <h3 style={{ marginTop: 0 }}>{title}</h3>
                <p>{message}</p>
                <div style={{
                    display: 'flex',
                    justifyContent: 'flex-end',
                    gap: '10px',
                    marginTop: '20px'
                }}>
                    <button
                        onClick={onCancel}
                        style={{
                            padding: '8px 16px',
                            border: '1px solid #ccc',
                            borderRadius: '4px',
                            background: 'white',
                            cursor: 'pointer'
                        }}
                    >
                        Cancel
                    </button>
                    <button
                        onClick={onConfirm}
                        style={{
                            padding: '8px 16px',
                            border: 'none',
                            borderRadius: '4px',
                            background: '#dc3545',
                            color: 'white',
                            cursor: 'pointer'
                        }}
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
    );
};

export default DeletionConfirmation;
