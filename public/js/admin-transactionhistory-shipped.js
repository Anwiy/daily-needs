document.addEventListener("DOMContentLoaded", function () {
    const rows = document.querySelectorAll(".shipped")
    rows.forEach(row => {
        row.addEventListener("click", function () {
            document.getElementById("modalShipmentNumberShipped").textContent = row.dataset.shipmentnumberShipped || '-';
            document.getElementById("modalTransactionDateShipped").textContent = row.dataset.transactiondateShipped || '-';
            document.getElementById("modalShipmentStartDateShipped").textContent = row.dataset.shipmentdatestartShipped || '-';
            document.getElementById("modalShipmentEndDateShipped").textContent = row.dataset.shipmentdateendShipped || '-';
            document.getElementById("modalCustomerShipped").textContent = row.dataset.customerShipped || '-';
            document.getElementById("modalAddressShipped").textContent = row.dataset.addressShipped || '-';
            document.getElementById("modalPaymentShipped").textContent = row.dataset.paymentShipped || '-';
            document.getElementById("modalRecipientNameShipped").textContent = row.dataset.shipmentrecipientShipped || '-';
            document.getElementById("modalTotalShipped").textContent = row.dataset.totalShipped || '-';
            
            const products = JSON.parse(row.dataset.productsShipped || '[]');
            const tbody = document.querySelector("#modalProductsShipped tbody");
            tbody.innerHTML = '';
            products.forEach(product => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${product.msproduct?.product_name || '-'}</td>
                    <td>Rp ${Number(product.unit_price_at_buy || 0).toLocaleString('id-ID')}</td>
                    <td>${product.quantity}</td>
                    <td>Rp ${Number(product.subtotal || 0).toLocaleString('id-ID')}</td>
                `;
                tbody.appendChild(tr);
            });
        });
    });
});
