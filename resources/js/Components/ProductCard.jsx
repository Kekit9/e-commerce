import EditProductForm from "./EditProductForm.jsx";

const ProductCard = ({
                         product,
                         currencyRates,
                         onEdit,
                         onDelete,
                         onUpdate,
                         isEditing,
                         csrfToken,
                         makers
                     }) => {

    const convertPrice = (price, currencyIso) => {
        if (!currencyRates || currencyIso === 'BYN') return price;

        const rate = currencyRates.find(r => r.currency_iso === currencyIso);
        if (!rate) return price;

        return (price / rate.buy_rate).toFixed(2);
    };

    const getCurrencySymbol = (currency) => {
        const symbols = {
            'USD': '$',
            'EUR': '€',
            'RUB': '₽',
            'BYN': 'Br'
        };
        return symbols[currency] || currency;
    };

    return (
        <div style={{
            border: "1px solid #ccc",
            borderRadius: "8px",
            padding: "20px",
            width: "100%",
            boxShadow: "0 4px 8px rgba(0,0,0,0.1)",
            position: "relative",
            marginBottom: "20px"
        }}>
            <div>
                <div style={{ display: "flex", flexDirection: "row", gap: "5px" }}>
                    <p>Product name:</p>
                    <p><strong>{product.name}</strong></p>
                </div>
                <div style={{ display: "flex", flexDirection: "row", gap: "5px" }}>
                    <p>Description:</p>
                    <p><strong>{product.description}</strong></p>
                </div>
                <div style={{ display: "flex", flexDirection: "row", gap: "5px" }}>
                    <p>Price:</p>
                    <div
                        className="product-price"
                        style={{display: "flex", flexDirection: "row", gap: "15px", alignItems: "center", fontWeight:"bold"}}
                    >
                        <div className="price-byn">
                            {product.price} {getCurrencySymbol('BYN')}
                        </div>

                        {currencyRates.length > 0 && (
                            <div
                                className="converted-prices"
                                style={{display: "flex", flexDirection: "row", gap: "15px"}}
                            >
                                {currencyRates.map(rate => (
                                    <div key={rate.currency_iso} className="converted-price">
                                        {convertPrice(product.price, rate.currency_iso)}
                                        {getCurrencySymbol(rate.currency_iso)}
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>
                </div>
                <div style={{ display: "flex", flexDirection: "row", gap: "5px" }}>
                    <p>Maker:</p>
                    <p><strong>
                        {product.maker ? product.maker.name : 'No maker assigned'}
                    </strong></p>
                </div>
                <div style={{ display: "flex", flexDirection: "row", gap: "5px" }}>
                    <p>Category:</p>
                    <p><strong>{product.category}</strong></p>
                </div>

                <div style={{ marginTop: "10px" }}>
                    <details style={{ cursor: "pointer" }}>
                        <summary>
                            Associated Services ({product.services?.length || 0}) •
                            Total: ${(parseFloat(product.price || 0) + (product.services?.reduce((sum, service) => sum + parseFloat(service.price || 0), 0) || 0)).toFixed(2)}
                        </summary>
                        {product.services?.length > 0 ? (
                            <div style={{ marginTop: "5px" }}>
                                <p style={{ marginBottom: "5px" }}>
                                    <strong>Base Product:</strong> ${parseFloat(product.price || 0).toFixed(2)}
                                </p>
                                <ul style={{
                                    paddingLeft: "20px",
                                    listStyleType: "none",
                                    marginBottom: "5px"
                                }}>
                                    {product.services.map(service => (
                                        <li key={service.id} style={{ marginBottom: "3px" }}>
                                            • {service.service_type} (+${parseFloat(service.price || 0).toFixed(2)}, in {service.duration} months)
                                        </li>
                                    ))}
                                </ul>
                                <p style={{ fontWeight: "bold", borderTop: "1px solid #eee", paddingTop: "5px" }}>
                                    Total: ${parseFloat(product.price || 0).toFixed(2)} (product) + ${product.services.reduce((sum, service) => sum + parseFloat(service.price || 0), 0).toFixed(2)} (services) =
                                    ${(parseFloat(product.price || 0) + product.services.reduce((sum, service) => sum + parseFloat(service.price || 0), 0)).toFixed(2)}
                                </p>
                            </div>
                        ) : (
                            <p style={{ marginTop: "5px", fontStyle: "italic" }}>
                                No services associated (Total: ${parseFloat(product.price || 0).toFixed(2)})
                            </p>
                        )}
                    </details>
                </div>

            </div>

            <div style={{
                display: "flex",
                justifyContent: "flex-end",
                gap: "10px",
                marginTop: "10px"
            }}>
                <button
                    onClick={() => onEdit(product)}
                    style={{
                        backgroundColor: "orange",
                        color: "white",
                        padding: "5px 10px",
                        border: "none",
                        borderRadius: "4px"
                    }}
                >
                    Edit
                </button>
                <button
                    onClick={() => onDelete(product.id)}
                    style={{
                        backgroundColor: "red",
                        color: "white",
                        padding: "5px 10px",
                        border: "none",
                        borderRadius: "4px"
                    }}
                >
                    Delete
                </button>
            </div>

            {isEditing && (
                <EditProductForm
                    product={product}
                    onUpdate={(updatedData) => onUpdate(product.id, updatedData)}
                    onCancel={() => onEdit(null)}
                    csrfToken={csrfToken}
                    makers={makers}
                />
            )}
        </div>
    );
};

export default ProductCard;
