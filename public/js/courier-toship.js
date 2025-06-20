document.addEventListener("DOMContentLoaded", function () {
    let selectedTransactionId = null;

    document.querySelectorAll('.to-ship').forEach(row => {
        row.addEventListener('click', function () {
            selectedTransactionId = this.getAttribute('data-transaction-id');

            document.getElementById('modalTransactionDate').textContent = this.getAttribute('data-transaction-date');
            document.getElementById('modalCustomer').textContent = this.getAttribute('data-customer-name');
            document.getElementById('modalAddressToShip').textContent = this.getAttribute('data-customer-address');
            document.getElementById('modalPaymentToShip').textContent = this.getAttribute('data-payment-method');
            document.getElementById('modalTotal').textContent = this.getAttribute('data-total-amount');
        });
    });

    document.getElementById('confirmForm').addEventListener('submit', function (e) {
        if (!selectedTransactionId) {
            e.preventDefault();
            alert('No transaction selected!');
            return;
        }

        this.action = `/courier/delivery-order/to-ship/${selectedTransactionId}`;
    });
});
