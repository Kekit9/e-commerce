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
    const [pagination, setPagination] = useState({
        current_page: 1,
        last_page: 1,
        total: 0,
        per_page: 10
    });
    const [filters, setFilters] = useState({
        service_type: '',
    });
    const [sort, setSort] = useState({
        sort_by: 'id',
        sort_direction: 'asc'
    });
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

    const fetchServices = async (page = 1) => {
        try {
            setLoading(true);
            const queryParams = new URLSearchParams({
                ...filters,
                ...sort,
                page,
                per_page: pagination.per_page
            }).toString();

            const response = await fetch(`/services-list?${queryParams}`);
            if (!response.ok) {
                alert("Failed to fetch services");
            }
            const servicesData = await response.json();

            setServiceData(servicesData.data);
            setPagination({
                current_page: servicesData.current_page,
                last_page: servicesData.last_page,
                total: servicesData.total,
                per_page: servicesData.per_page
            });

        } catch (err) {
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchServices();
    }, [filters, sort, pagination.per_page]);

    const handleFilterChange = (e) => {
        const { name, value } = e.target;
        setFilters(prev => ({
            ...prev,
            [name]: value
        }));
    };

    const handleSortChange = (sortBy) => {
        setSort(prev => ({
            sort_by: sortBy,
            sort_direction: prev.sort_by === sortBy
                ? prev.sort_direction === 'asc' ? 'desc' : 'asc'
                : 'asc'
        }));
    };

    const handlePageChange = (page) => {
        fetchServices(page);
    };

    const handlePerPageChange = (e) => {
        setPagination(prev => ({
            ...prev,
            per_page: Number(e.target.value)
        }));
    };

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
            fetchServices(pagination.current_page);
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
            fetchServices(pagination.current_page);
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
            fetchServices(pagination.current_page);
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

            <div style={{
                marginBottom: '20px',
                display: 'grid',
                gridTemplateColumns: 'repeat(auto-fill, minmax(200px, 1fr))',
                gap: '10px'
            }}>
                <div>
                    <select
                        name="service_type"
                        value={filters.service_type}
                        onChange={handleFilterChange}
                        style={{
                            width: '250px',
                            overflowY: 'auto',
                            padding: '5px'
                        }}
                    >
                        <option value="">All Service Types</option>
                        {[...new Set(serviceData.map(item => item.service_type))].map((type, index) => (
                            <option key={index} value={type}>
                                {type}
                            </option>
                        ))}
                    </select>
                </div>
            </div>

            <div style={{ marginBottom: '20px', display: 'flex', gap: '10px' }}>
                <span>Sort by: </span>
                <button onClick={() => handleSortChange('service_type')}>
                    Type {sort.sort_by === 'service_type' && (sort.sort_direction === 'asc' ? '↑' : '↓')}
                </button>
                <button onClick={() => handleSortChange('duration')}>
                    Duration {sort.sort_by === 'duration' && (sort.sort_direction === 'asc' ? '↑' : '↓')}
                </button>
                <button onClick={() => handleSortChange('price')}>
                    Price {sort.sort_by === 'price' && (sort.sort_direction === 'asc' ? '↑' : '↓')}
                </button>
            </div>

            <div style={{ marginBottom: '20px' }}>
                <select value={pagination.per_page} onChange={handlePerPageChange}>
                    <option value="5">5 per page</option>
                    <option value="10">10 per page</option>
                    <option value="20">20 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>


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

                <div style={{ marginTop: '20px', display: 'flex', justifyContent: 'center', gap: '10px' }}>
                    {pagination.current_page > 1 && (
                        <button onClick={() => handlePageChange(pagination.current_page - 1)}>
                            Previous
                        </button>
                    )}

                    {Array.from({ length: pagination.last_page }, (_, i) => i + 1).map(page => (
                        <button
                            key={page}
                            onClick={() => handlePageChange(page)}
                            disabled={page === pagination.current_page}
                            style={{ fontWeight: page === pagination.current_page ? 'bold' : 'normal' }}
                        >
                            {page}
                        </button>
                    ))}

                    {pagination.current_page < pagination.last_page && (
                        <button onClick={() => handlePageChange(pagination.current_page + 1)}>
                            Next
                        </button>
                    )}
                </div>
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
