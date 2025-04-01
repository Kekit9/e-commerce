import { useState, useEffect } from 'react';

const EditProductForm = ({
                             product,
                             onUpdate,
                             onCancel,
                             makers
                         }) => {
    const [editedProduct, setEditedProduct] = useState({
        ...product,
        maker_id: product.maker?.id || ''
    });

    useEffect(() => {
        setEditedProduct({
            ...product,
            maker_id: product.maker?.id || ''
        });
    }, [product]);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setEditedProduct(prev => ({
            ...prev,
            [name]: name === 'price' ? parseFloat(value) : value
        }));
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        onUpdate({
            ...editedProduct,
            maker_id: editedProduct.maker_id
        });
    };

    return (
        <div style={{
            marginTop: "20px",
            padding: "20px",
            borderTop: "2px solid #ccc",
            backgroundColor: "#f9f9f9"
        }}>
            <h2>Edit Product</h2>
            <form onSubmit={handleSubmit} style={{ display: "flex", gap: "10px", alignItems: "center" }}>
                <input
                    type="text"
                    name="name"
                    value={editedProduct.name || ""}
                    onChange={handleChange}
                />
                <input
                    type="text"
                    name="description"
                    value={editedProduct.description || ""}
                    onChange={handleChange}
                />
                <input
                    type="number"
                    name="price"
                    value={editedProduct.price || ""}
                    onChange={handleChange}
                    step="0.01"
                />
                <select
                    name="maker_id"
                    value={editedProduct.maker_id || ""}
                    onChange={handleChange}
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
                    name="category"
                    value={editedProduct.category || ""}
                    onChange={handleChange}
                />
                <button
                    type="submit"
                    style={{
                        backgroundColor: "green",
                        color: "white",
                        padding: "5px 10px",
                        border: "none",
                        borderRadius: "4px"
                    }}
                >
                    Save
                </button>
                <button
                    type="button"
                    onClick={onCancel}
                    style={{
                        backgroundColor: "gray",
                        color: "white",
                        padding: "5px 10px",
                        border: "none",
                        borderRadius: "4px"
                    }}
                >
                    Cancel
                </button>
            </form>
        </div>
    );
};

export default EditProductForm;
