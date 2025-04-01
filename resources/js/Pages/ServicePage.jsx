import { useState, useEffect } from "react";
import { getCsrfToken } from "../CSRF/csrfToken.jsx";
import ServiceCard from "../Components/ServiceCard";
import CreateServiceForm from "../Components/CreateServiceForm";
import DeletionConfirmation from "../Components/DeletionConfirmation.jsx";

const ServicePage = () => {
    const [confirmDialog, setConfirmDialog] = useState({
        isOpen: false,
        serviceId: null,
        title: '',
        message: ''
    });
    const [serviceData, setServiceData] = useState([]);
    const [newService, setNewService] = useState({
        service_type: "",
        duration: "",
        price: "",
        terms: ""
    });
    const [editingService, setEditingService] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    const csrfToken = getCsrfToken();
    if (!csrfToken) {
        alert("CSRF token not found!");
        return;
    }

    useEffect(() => {
        const fetchServices = async () => {
            try {
                const response = await fetch("/services-list");
                if (!response.ok) {
                    alert("Failed to fetch services");
                }
                const data = await response.json();
                setServiceData(data);
                setLoading(false);
            } catch (err) {
                setError(err.message);
                setLoading(false);
            }
        };
        fetchServices();
    }, []);

    const handleCreateService = async () => {
        try {
            const response = await fetch("/service", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(newService),
            });

            if (!response.ok) {
                const errorData = await response.json();
                if (errorData.errors?.service_type) {
                    alert(errorData.errors.service_type[0]);
                } else {
                    alert(errorData.message || 'Failed to create service');
                }
                return;
            }

            const data = await response.json();
            setServiceData([...serviceData, data]);
            setNewService({ service_type: "", duration: "", price: "", terms: "" });
        } catch (error) {
            console.error("Failed to create service:", error);
            alert(error.message);
        }
    };

    const handleUpdateService = async (serviceId, updatedData) => {
        try {
            const response = await fetch(`/service/${serviceId}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(updatedData),
            });

            if (!response.ok) {
                alert("Failed to update service");
            }

            const updatedService = await response.json();

            if (!updatedService.id) {
                updatedService.id = serviceId;
            }

            setServiceData(serviceData.map(s =>
                s.id === serviceId ? updatedService : s
            ));
            setEditingService(null);
        } catch (error) {
            console.error("Failed to update service:", error);
            alert(error.message);
        }
    };

    const handleDeleteClick = (serviceId) => {
        setConfirmDialog({
            isOpen: true,
            serviceId,
            title: 'Confirm Deletion',
            message: 'Are you sure you want to delete this service? This action cannot be undone.'
        });
    };

    const handleDeleteConfirm = async () => {
        try {
            await handleDeleteService(confirmDialog.serviceId);
            setConfirmDialog({
                isOpen: false,
                serviceId: null,
                title: '',
                message: ''
            });
        } catch (error) {
            console.error('Deletion failed:', error);
            setConfirmDialog({
                isOpen: false,
                serviceId: null,
                title: '',
                message: ''
            });
        }
    };

    const handleDeleteCancel = () => {
        setConfirmDialog({
            isOpen: false,
            serviceId: null,
            title: '',
            message: ''
        });
    };

    const handleDeleteService = async (serviceId) => {
        try {
            const response = await fetch(`/service/${serviceId}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
            });

            if (!response.ok) {
                alert("Failed to delete service");
            }

            setServiceData(serviceData.filter((service) => service.id !== serviceId));
        } catch (error) {
            console.error("Failed to delete service:", error);
            alert(error.message);
        }
    };

    if (loading) return <div>Loading...</div>;
    if (error) return <div>Error: {error}</div>;

    return (
        <div style={{ padding: "20px" }}>
            <h1>Services List</h1>

            <CreateServiceForm
                newService={newService}
                setNewService={setNewService}
                onCreate={handleCreateService}
                csrfToken={csrfToken}
            />

            <div style={{
                display: "flex",
                flexWrap: "wrap",
                gap: "20px",
                flexDirection: "row",
                justifyContent: "space-evenly",
                marginTop: "20px",
            }}>
                {serviceData.map((service) => {
                    return (
                        <ServiceCard
                            key={`service-${service.id}`}
                            service={service}
                            onEdit={setEditingService}
                            onDelete={() => handleDeleteClick(service.id)}
                            onUpdate={handleUpdateService}
                            isEditing={editingService?.id === service.id}
                            csrfToken={csrfToken}
                        />
                    );
                })}
                <DeletionConfirmation
                    isOpen={confirmDialog.isOpen}
                    title={confirmDialog.title}
                    message={confirmDialog.message}
                    onConfirm={handleDeleteConfirm}
                    onCancel={handleDeleteCancel}
                />
            </div>
        </div>
    );
};

export default ServicePage;
