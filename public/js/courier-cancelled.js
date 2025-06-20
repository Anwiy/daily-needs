document.addEventListener("DOMContentLoaded", function () {
    let selectedShipmentId = null;

    document.querySelectorAll('.cancelled').forEach(row => {
        row.addEventListener('click', function () {
            selectedShipmentId = this.getAttribute('data-shipment-id-cancelled');

            document.getElementById('modalShipmentNumberCancelled').textContent = selectedShipmentId;
            document.getElementById('modalTransactionNumberCancelled').textContent = this.getAttribute('data-transaction-id-cancelled');
            document.getElementById('modalCustomerNameCancelled').textContent = this.getAttribute('data-customer-name-cancelled');
            document.getElementById('modalShipmentDateStartCancelled').textContent = this.getAttribute('data-shipment-start-date-cancelled');
            document.getElementById('modalShipmentDateEndCancelled').textContent = this.getAttribute('data-shipment-end-date-cancelled');
            document.getElementById('modalAddressCancelled').textContent = this.getAttribute('data-address-cancelled');
            document.getElementById('modalPaymentCancelled').textContent = this.getAttribute('data-payment-method-cancelled');
            document.getElementById('modalTotalPriceCancelled').textContent = this.getAttribute('data-total-amount-cancelled');
        });
    });
});
