document.addEventListener("DOMContentLoaded", function () {
    let selectedShipmentId = null;

    document.querySelectorAll('.delivered').forEach(row => {
        row.addEventListener('click', function () {
            selectedShipmentId = this.getAttribute('data-shipment-id-delivered');

            document.getElementById('modalShipmentNumberDelivered').textContent = selectedShipmentId;
            document.getElementById('modalTransactionNumberDelivered').textContent = this.getAttribute('data-transaction-id-delivered');
            document.getElementById('modalCustomerNameDelivered').textContent = this.getAttribute('data-customer-name-delivered');
            document.getElementById('modalShipmentDateStartDelivered').textContent = this.getAttribute('data-shipment-start-date-delivered');
            document.getElementById('modalShipmentDateEndDelivered').textContent = this.getAttribute('data-shipment-end-date-delivered');
            document.getElementById('modalAddressDelivered').textContent = this.getAttribute('data-address-delivered');
            document.getElementById('modalPaymentDelivered').textContent = this.getAttribute('data-payment-method-delivered');
            document.getElementById('modalRecipientNameDelivered').textContent = this.getAttribute('data-shipment-recipient-name-delivered');
            document.getElementById('modalTotalPriceDelivered').textContent = this.getAttribute('data-total-amount-delivered');
        });
    });
});
