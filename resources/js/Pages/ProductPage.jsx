import { useState, useEffect } from "react";
import { getCsrfToken } from "../CSRF/csrfToken.jsx";
import ProductCard from "../Components/ProductCard";
import CreateProductForm from "../Components/CreateProductForm";
import DeletionConfirmation from "../Components/DeletionConfirmation.jsx";

const ProductPage = () => {
    const [confirmDialog, setConfirmDialog] = useState({
        isOpen: false,
        productId: null,
        title: '',
        message: ''
    });
    const [productData, setProductData] = useState([]);
    const [currencyRates, setCurrencyRates] = useState([]);
    const [newProduct, setNewProduct] = useState({
        name: "",
        description: "",
        price: "",
        category: ""
    });
    const [makers, setMakers] = useState([]);
    const [editingProduct, setEditingProduct] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    const csrfToken = getCsrfToken();
    if (!csrfToken) {
        alert("CSRF token not found!");
        return;
    }

    useEffect(() => {
        const fetchProducts = async () => {
            try {
                const response = await fetch("/products-list");
                if (!response.ok) {
                    alert("Failed to fetch products");
                }
                const { products: productsData, currency_rates } = await response.json();

                setProductData(Array.isArray(productsData) ? productsData : []);

                setCurrencyRates(currency_rates || []);

                const uniqueMakers = Array.isArray(productsData)
                    ? [...new Map(productsData
                        .filter(p => p.maker)
                        .map(p => [p.maker.id, p.maker]))
                        .values()]
                    : [];

                setMakers(uniqueMakers);

            } catch (err) {
                setError(err.message);
            } finally {
                setLoading(false);
            }
        };
        fetchProducts();
    }, []);

    const handleCreateProduct = async () => {
        try {
            const response = await fetch("/product", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(newProduct),
            });

            if (!response.ok) {
                const errorData = await response.json();
                if (errorData.errors?.name) {
                    alert(errorData.errors.name[0]);
                } else {
                    alert(errorData.message || 'Failed to create product');
                }
                return;
            }

            const data = await response.json();
            setProductData([...productData, data]);
            setNewProduct({ name: "", description: "", price: "", category: "" });
        } catch (error) {
            console.error("Failed to create product:", error);
            alert(error.message);
        }
    };

    const handleUpdateProduct = async (productId, updatedData) => {
        try {
            const response = await fetch(`/product/${productId}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(updatedData),
            });

            if (!response.ok) {
                alert("Failed to update product");
            }

            const updatedProduct = await response.json();

            if (!updatedProduct.id) {
                updatedProduct.id = productId;
            }

            setProductData(productData.map(p =>
                p.id === productId ? updatedProduct : p
            ));
            setEditingProduct(null);
        } catch (error) {
            console.error("Failed to update product:", error);
            alert(error.message);
        }
    };

    const handleDeleteClick = (productId) => {
        setConfirmDialog({
            isOpen: true,
            productId,
            title: 'Confirm Deletion',
            message: 'Are you sure you want to delete this product? This action cannot be undone.'
        });
    };

    const handleDeleteConfirm = async () => {
        try {
            await handleDeleteProduct(confirmDialog.productId);
            setConfirmDialog({
                isOpen: false,
                productId: null,
                title: '',
                message: ''
            });
        } catch (error) {
            console.error('Deletion failed:', error);
            setConfirmDialog({
                isOpen: false,
                productId: null,
                title: '',
                message: ''
            });
        }
    };

    const handleDeleteCancel = () => {
        setConfirmDialog({
            isOpen: false,
            productId: null,
            title: '',
            message: ''
        });
    };

    const handleDeleteProduct = async (productId) => {
        try {
            const response = await fetch(`/product/${productId}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
            });

            if (!response.ok) {
                alert("Failed to delete product");
            }

            setProductData(productData.filter((product) => product.id !== productId));
        } catch (error) {
            console.error("Failed to delete product:", error);
            alert(error.message);
        }
    };

    if (loading) return <div>Loading...</div>;
    if (error) return <div>Error: {error}</div>;

    return (
        <div style={{ padding: "20px" }}>
            <h1>Products List</h1>

            <CreateProductForm
                newProduct={newProduct}
                setNewProduct={setNewProduct}
                onCreate={handleCreateProduct}
                csrfToken={csrfToken}
                makers={makers}
            />

            <div style={{
                display: "flex",
                flexWrap: "wrap",
                gap: "20px",
                flexDirection: "row",
                justifyContent: "space-evenly",
                marginTop: "20px",
            }}>
                {productData.map((product) => {
                    return (
                        <ProductCard
                            key={`product-${product.id}`}
                            product={product}
                            currencyRates={currencyRates}
                            onEdit={setEditingProduct}
                            onDelete={() => handleDeleteClick(product.id)}
                            onUpdate={handleUpdateProduct}
                            isEditing={editingProduct?.id === product.id}
                            csrfToken={csrfToken}
                            makers={makers}
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

export default ProductPage;
