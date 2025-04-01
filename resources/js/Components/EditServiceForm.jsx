import { useState, useEffect } from "react";

const EditServiceForm = ({
                             service,
                             onUpdate,
                             onCancel,
                         }) => {
    const [editedService, setEditedService] = useState(service);

    useEffect(() => {
        setEditedService(service);
    }, [service]);

    const handleChange = (e) => {
        const { name, value } = e.target;
        const newValue = name === 'duration' ? parseInt(value) :
            name === 'price' ? parseFloat(value) :
                value;


        setEditedService(prev => ({
            ...prev,
            [name]: newValue
        }));
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        onUpdate(editedService);
    };

    return (
        <div
            key={`edit-${service.id}`}
            style={{
                display: "flex",
                gap: "10px",
                alignItems: "center",
                marginTop: "20px",
                padding: "10px",
                borderTop: "2px solid #ccc",
                borderRadius: "8px",
                backgroundColor: "#f9f9f9"
            }}
        >
            <h2>Edit Service</h2>
            <form onSubmit={handleSubmit} style={{ display: "flex", gap: "10px", alignItems: "center" }}>
                <input
                    type="text"
                    name="service_type"
                    value={editedService.service_type || ""}
                    onChange={handleChange}
                />
                <input
                    type="number"
                    name="duration"
                    value={editedService.duration}
                    onChange={handleChange}
                />
                <input
                    type="number"
                    name="price"
                    value={editedService.price}
                    onChange={handleChange}
                />
                <input
                    type="text"
                    name="terms"
                    value={editedService.terms || ""}
                    onChange={handleChange}
                />
                <button
                    type="submit"
                    style={{ backgroundColor: "green", color: "white", padding: "5px 10px", border: "none", borderRadius: "4px" }}
                >
                    Save
                </button>
                <button
                    type="button"
                    onClick={onCancel}
                    style={{ backgroundColor: "gray", color: "white", padding: "5px 10px", border: "none", borderRadius: "4px", marginLeft: "10px" }}
                >
                    Cancel
                </button>
            </form>
        </div>
    );
};

export default EditServiceForm;
