document.addEventListener("DOMContentLoaded", function () {
    const rows = document.querySelectorAll(".processing")
    rows.forEach(row => {
        row.addEventListener("click", function () {
            document.getElementById("modalTransactionDateProcessing").textContent = row.dataset.transactiondateProcessing || '-';
            document.getElementById("modalCustomerProcessing").textContent = row.dataset.customerProcessing || '-';
            document.getElementById("modalAddressProcessing").textContent = row.dataset.addressProcessing || '-';
            document.getElementById("modalPaymentProcessing").textContent = row.dataset.paymentProcessing || '-';
            document.getElementById("modalTotalProcessing").textContent = row.dataset.totalProcessing || '-';
            
            const products = JSON.parse(row.dataset.productsProcessing || '[]');
            const tbody = document.querySelector("#modalProductsProcessing tbody");
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
