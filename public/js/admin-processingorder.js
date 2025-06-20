document.addEventListener("DOMContentLoaded", function () {
    const rows = document.querySelectorAll(".pending")
    rows.forEach(row => {
        row.addEventListener("click", function () {
            document.getElementById("modalTransactionDatePending").textContent = row.dataset.transactiondate || '-';
            document.getElementById("modalCustomerPending").textContent = row.dataset.customer || '-';
            document.getElementById("modalAddressPending").textContent = row.dataset.address || '-';
            document.getElementById("modalPaymentPending").textContent = row.dataset.payment || '-';
            document.getElementById("modalTotalPending").textContent = row.dataset.total || '-'
            const products = JSON.parse(row.dataset.products || '[]');
            const tbody = document.querySelector("#modalProductsPending tbody");
            tbody.innerHTML = ''
            products.forEach(product => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${product.msproduct?.product_name || '-'}</td>
                    <td>Rp ${Number(product.unit_price_at_buy || 0).toLocaleString('id-ID')}</td>
                    <td>${product.quantity}</td>
                    <td>Rp ${Number(product.subtotal || 0).toLocaleString('id-ID')}</td>
                `;
                tbody.appendChild(tr);
            })
            const confirmForm = document.getElementById("confirmForm");
            const transactionId = row.querySelector('td:first-child').textContent.trim();
            confirmForm.action = `/admin/processing-order/confirm/${transactionId}`;
        });
    });
});