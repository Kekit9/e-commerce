import EditServiceForm from './EditServiceForm';
import {useEffect, useState} from "react";

const ServiceCard = ({
                         service,
                         onEdit,
                         onDelete,
                         onUpdate,
                         isEditing,
                         csrfToken
                     }) => {
    const [isAdmin, setIsAdmin] = useState(false);

    useEffect(() => {
        const role = localStorage.getItem('userRole');
        setIsAdmin(role === 'admin');
    }, []);
    return (
        <div
            style={{
                border: "1px solid #ccc",
                borderRadius: "8px",
                padding: "20px",
                width: "100%",
                boxShadow: "0 4px 8px rgba(0,0,0,0.1)",
                position: "relative",
                marginBottom: "20px"
            }}
        >
            <div style={{ display: "flex", flexDirection: "row", gap: "5px" }}>
                <p>Service type:</p>
                <p><strong>{service.service_type}</strong></p>
            </div>
            <div style={{ display: "flex", flexDirection: "row", gap: "5px" }}>
                <p>Duration in months:</p>
                <p><strong>{service.duration}</strong></p>
            </div>
            <div style={{ display: "flex", flexDirection: "row", gap: "5px", alignContent: "center" }}>
                <p>Service terms:</p>
                <strong><p>{service.terms}</p></strong>
            </div>
            <div style={{ display: "flex", flexDirection: "row", gap: "5px", alignContent: "center" }}>
                <p>Price:</p>
                <strong><p>{service.price} Br</p></strong>
            </div>

            {isAdmin && (
                <div style={{ display: "flex", justifyContent: "flex-end", gap: "10px", marginTop: "10px" }}>
                    <button
                        key={`edit-btn-${service.id}`}
                        onClick={() => onEdit(service)}
                        style={{ backgroundColor: "orange", color: "white", padding: "5px 10px", border: "none", borderRadius: "4px" }}
                    >
                        Edit
                    </button>
                    <button
                        key={`delete-btn-${service.id}`}
                        onClick={() => onDelete(service.id)}
                        style={{ backgroundColor: "red", color: "white", padding: "5px 10px", border: "none", borderRadius: "4px" }}
                    >
                        Delete
                    </button>
                </div>
            )}

            {isEditing && (
                <EditServiceForm
                    service={service}
                    onUpdate={(updatedData) => onUpdate(service.id, updatedData)}
                    onCancel={() => onEdit(null)}
                    csrfToken={csrfToken}
                />
            )}
        </div>
    );
};

export default ServiceCard;
