const CreateProductForm = ({
                               newProduct,
                               setNewProduct,
                               onCreate,
                               makers
                           }) => {
    return (
        <div style={{
            display: "flex",
            gap: "10px",
            flexDirection: "row",
            alignItems: "center",
            marginBottom: "20px"
        }}>
            <h2>Create Product</h2>
            <input
                type="text"
                placeholder="Name"
                value={newProduct.name}
                onChange={(e) => setNewProduct({ ...newProduct, name: e.target.value })}
            />
            <input
                type="text"
                placeholder="Description"
                value={newProduct.description}
                onChange={(e) => setNewProduct({ ...newProduct, description: e.target.value })}
            />
            <input
                type="number"
                placeholder="Price"
                value={newProduct.price}
                onChange={(e) => setNewProduct({ ...newProduct, price: e.target.value })}
            />
            <select
                value={newProduct.maker_id}
                onChange={(e) => setNewProduct({ ...newProduct, maker_id: e.target.value })}
                style={{ width: "185px", height: "21px" }}
            >
                <option value="">Select Maker</option>
                {makers.map(maker => (
                    <option key={maker.id} value={maker.id}>
                        {maker.name}
                    </option>
                ))}
            </select>
            <input
                type="text"
                placeholder="Category"
                value={newProduct.category}
                onChange={(e) => setNewProduct({ ...newProduct, category: e.target.value })}
            />
            <button
                onClick={onCreate}
                style={{
                    backgroundColor: "green",
                    color: "white",
                    border: "none",
                    borderRadius: "4px",
                    padding: "5px 10px"
                }}
            >
                Create
            </button>
        </div>
    );
};

export default CreateProductForm;
