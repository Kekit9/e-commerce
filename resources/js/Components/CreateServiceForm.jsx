const CreateServiceForm = ({
                               newService,
                               setNewService,
                               onCreate,
                           }) => {
    const handleChange = (e) => {
        const { name, value } = e.target;
        setNewService(prev => ({
            ...prev,
            [name]: value
        }));
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        onCreate();
    };

    return (
        <div style={{
            display: "flex",
            gap: "10px",
            flexDirection: "row",
            alignItems: "center",
            marginBottom: "20px"
        }}>
            <h2>Create Service</h2>
            <form onSubmit={handleSubmit} style={{ display: "flex", gap: "10px", alignItems: "center" }}>
                <input
                    type="text"
                    name="service_type"
                    placeholder="Service Type"
                    value={newService.service_type}
                    onChange={handleChange}
                    required
                />
                <input
                    type="number"
                    max={12}
                    name="duration"
                    placeholder="Duration in months"
                    value={newService.duration}
                    onChange={handleChange}
                    required
                />
                <input
                    type="number"
                    name="price"
                    placeholder="Price"
                    value={newService.price}
                    onChange={handleChange}
                    required
                />
                <input
                    type="text"
                    name="terms"
                    placeholder="Terms"
                    value={newService.terms}
                    onChange={handleChange}
                />
                <button
                    type="submit"
                    style={{ backgroundColor: "green", color: "white", border: "none", borderRadius: "4px", padding: "5px 10px" }}
                >
                    Create
                </button>
            </form>
        </div>
    );
};

export default CreateServiceForm;
