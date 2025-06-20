document.addEventListener("DOMContentLoaded", function () {
    const rows = document.querySelectorAll(".out-for-delivery")
    rows.forEach(row => {
        row.addEventListener("click", function () {
            document.getElementById("modalShipmentNumberOutfordelivery").textContent = row.dataset.shipmentnumberOutfordelivery || '-';
            document.getElementById("modalTransactionDateOutfordelivery").textContent = row.dataset.transactiondateOutfordelivery || '-';
            document.getElementById("modalShipmentStartDateOutfordelivery").textContent = row.dataset.shipmentdatestartOutfordelivery || '-';
            document.getElementById("modalCustomerOutfordelivery").textContent = row.dataset.customerOutfordelivery || '-';
            document.getElementById("modalAddressOutfordelivery").textContent = row.dataset.addressOutfordelivery || '-';
            document.getElementById("modalPaymentOutfordelivery").textContent = row.dataset.paymentOutfordelivery || '-';
            document.getElementById("modalTotalOutfordelivery").textContent = row.dataset.totalOutfordelivery || '-';
            
            const products = JSON.parse(row.dataset.productsOutfordelivery || '[]');
            const tbody = document.querySelector("#modalProductsOutfordelivery tbody");
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
