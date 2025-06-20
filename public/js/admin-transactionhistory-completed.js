document.addEventListener("DOMContentLoaded", function () {
    const rows = document.querySelectorAll(".completed")
    rows.forEach(row => {
        row.addEventListener("click", function () {
            document.getElementById("modalShipmentNumberCompleted").textContent = row.dataset.shipmentnumberCompleted || '-';
            document.getElementById("modalTransactionDateCompleted").textContent = row.dataset.transactiondateCompleted || '-';
            document.getElementById("modalShipmentStartDateCompleted").textContent = row.dataset.shipmentdatestartCompleted || '-';
            document.getElementById("modalShipmentEndDateCompleted").textContent = row.dataset.shipmentdateendCompleted || '-';
            document.getElementById("modalCustomerCompleted").textContent = row.dataset.customerCompleted || '-';
            document.getElementById("modalAddressCompleted").textContent = row.dataset.addressCompleted || '-';
            document.getElementById("modalPaymentCompleted").textContent = row.dataset.paymentCompleted || '-';
            document.getElementById("modalRecipientNameCompleted").textContent = row.dataset.shipmentrecipientCompleted || '-';
            document.getElementById("modalTotalCompleted").textContent = row.dataset.totalCompleted || '-';
            
            const products = JSON.parse(row.dataset.productsCompleted || '[]');
            const tbody = document.querySelector("#modalProductsCompleted tbody");
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
