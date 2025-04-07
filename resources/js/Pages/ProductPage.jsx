import { useState, useEffect } from "react";
import { getCsrfToken } from "../CSRF/csrfToken.jsx";
import ProductCard from "../Components/ProductCard";
import CreateProductForm from "../Components/CreateProductForm";
import DeletionConfirmation from "../Components/DeletionConfirmation.jsx";
import ExportButton from "../Components/ExportButton.jsx";

const ProductPage = () => {
    const [isAdmin, setIsAdmin] = useState(false);

    useEffect(() => {
        const role = localStorage.getItem('userRole');
        setIsAdmin(role === 'admin');
    }, []);
    const [confirmDialog, setConfirmDialog] = useState({
        isOpen: false,
        productId: null,
        title: '',
        message: ''
    });
    const [productData, setProductData] = useState([]);
    const [currencyRates, setCurrencyRates] = useState([]);
    const [pagination, setPagination] = useState({
        current_page: 1,
        last_page: 1,
        total: 0,
        per_page: 10
    });
    const [filters, setFilters] = useState({
        maker_id: '',
        service_id: ''
    });
    const [sort, setSort] = useState({
        sort_by: 'id',
        sort_direction: 'asc'
    });
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

    const fetchProducts = async (page = 1) => {
        try {
            setLoading(true);
            const queryParams = new URLSearchParams({
                ...filters,
                ...sort,
                page,
                per_page: pagination.per_page
            }).toString();

            const response = await fetch(`/products-list?${queryParams}`);
            if (!response.ok) {
                alert(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();

            const products = data.products?.data || [];
            const paginationData = data.products || {};
            const currency_rates = data.currency_rates || [];

            setProductData(products);
            setCurrencyRates(currency_rates);
            setPagination({
                current_page: paginationData.current_page || 1,
                last_page: paginationData.last_page || 1,
                total: paginationData.total || 0,
                per_page: paginationData.per_page || 10
            });

            const uniqueMakers = products.length
                ? [...new Map(products
                    .filter(p => p.maker)
                    .map(p => [p.maker.id, p.maker]))
                    .values()]
                : [];

            setMakers(uniqueMakers);

        } catch (err) {
            setError(err.message);
            console.error("Fetch error:", err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchProducts();
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
        fetchProducts(page);
    };

    const handlePerPageChange = (e) => {
        setPagination(prev => ({
            ...prev,
            per_page: Number(e.target.value)
        }));
    };

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
            fetchProducts(pagination.current_page);
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
            fetchProducts(pagination.current_page);
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
            fetchProducts(pagination.current_page);
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

            {isAdmin && (
                <ExportButton/>
            )}

            <div style={{ marginBottom: '20px' }}>
                <select
                    name="maker_id"
                    value={filters.maker_id}
                    onChange={handleFilterChange}
                    style={{
                        width: '250px',
                        overflowY: 'auto',
                        padding: '5px'
                    }}
                >
                    <option value="">All Makers</option>
                    {makers.map(maker => (
                        <option key={maker.id} value={maker.id}>
                            {maker.name}
                        </option>
                    ))}
                </select>
            </div>

            <div style={{ marginBottom: '20px' }}>
                <button onClick={() => handleSortChange('name')}>
                    Sort by Name {sort.sort_by === 'name' && (sort.sort_direction === 'asc' ? '↑' : '↓')}
                </button>
                <button onClick={() => handleSortChange('price')}>
                    Sort by Price {sort.sort_by === 'price' && (sort.sort_direction === 'asc' ? '↑' : '↓')}
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

            {isAdmin && (
                <CreateProductForm
                    newProduct={newProduct}
                    setNewProduct={setNewProduct}
                    onCreate={handleCreateProduct}
                    csrfToken={csrfToken}
                    makers={makers}
                />
            )}

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

export default ProductPage;
