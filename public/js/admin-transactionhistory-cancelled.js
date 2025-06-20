document.addEventListener("DOMContentLoaded", function () {
    const rows = document.querySelectorAll(".cancelled")
    rows.forEach(row => {
        row.addEventListener("click", function () {
            document.getElementById("modalShipmentNumberCancelled").textContent = row.dataset.shipmentnumberCancelled || '-';
            document.getElementById("modalTransactionDateCancelled").textContent = row.dataset.transactiondateCancelled || '-';
            document.getElementById("modalShipmentStartDateCancelled").textContent = row.dataset.shipmentdatestartCancelled || '-';
            document.getElementById("modalShipmentEndDateCancelled").textContent = row.dataset.shipmentdateendCancelled || '-';
            document.getElementById("modalCustomerCancelled").textContent = row.dataset.customerCancelled || '-';
            document.getElementById("modalAddressCancelled").textContent = row.dataset.addressCancelled || '-';
            document.getElementById("modalPaymentCancelled").textContent = row.dataset.paymentCancelled || '-';
            document.getElementById("modalTotalCancelled").textContent = row.dataset.totalCancelled || '-';
            
            const products = JSON.parse(row.dataset.productsCancelled || '[]');
            const tbody = document.querySelector("#modalProductsCancelled tbody");
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
