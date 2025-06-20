document.addEventListener('DOMContentLoaded', function () {
    const rows = document.querySelectorAll('.in-progress');
    const codModalEl = document.getElementById('codConfirmModal');
    const detailModalEl = document.getElementById('inProgressStatusModal');

    const codModal = new bootstrap.Modal(codModalEl);
    const detailModal = new bootstrap.Modal(detailModalEl);

    const hiddenShipmentId = document.getElementById('hiddenShipmentId');
    const hiddenTransactionId = document.getElementById('hiddenTransactionId');
    const recipientNameInput = document.getElementById('recipientName');
    const confirmForm = document.getElementById('inProgressConfirmForm');
    const recipientError = document.getElementById('recipientError');

    let lastSelectedShipmentId = null;

    function resetModal() {
        recipientNameInput.classList.remove('is-invalid');
        if (recipientError) recipientError.textContent = '';

        if (lastSelectedShipmentId === null || recipientNameInput.value === '') {
            recipientNameInput.value = '';
        }
    }

    if (confirmForm) {
        confirmForm.addEventListener('submit', function (e) {
            e.preventDefault();
            recipientNameInput.classList.remove('is-invalid');
            if (recipientError) recipientError.textContent = '';

            const shipmentId = hiddenShipmentId.value;
            const formData = new FormData(confirmForm);

            fetch(`/courier/delivery-order/in-progress/${shipmentId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async response => {   
                if (!response.ok) {
                    const data = await response.json();
                    if (data.errors && data.errors.shipment_recipient_name) {
                        recipientNameInput.classList.add('is-invalid');
                        if (recipientError) {
                            recipientError.textContent = data.errors.shipment_recipient_name[0];
                        }
                    }
                } 
                else {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('AJAX error:', error);
            });
        });
    }

    rows.forEach(row => {
        row.addEventListener('click', () => {
            const {
                shipmentId,
                transactionId,
                shipmentDate,
                address,
                paymentMethod,
                totalPrice,
            } = row.dataset;

            if (shipmentId !== lastSelectedShipmentId) {
                resetModal();
            }

            document.getElementById('modalShipmentNumber').textContent = shipmentId;
            document.getElementById('modalTransactionNumber').textContent = transactionId;
            document.getElementById('modalShipmentDateStart').textContent = shipmentDate;
            document.getElementById('modalAddressInProgress').textContent = address;
            document.getElementById('modalPaymentInProgress').textContent = paymentMethod;
            document.getElementById('cod-total-price').textContent = `Rp${parseInt(totalPrice).toLocaleString('id-ID')}`;

            hiddenShipmentId.value = shipmentId;
            hiddenTransactionId.value = transactionId;

            confirmForm.setAttribute('action', `/courier/delivery-order/in-progress/${shipmentId}`);

            if (paymentMethod === 'Cash on Delivery') {
                codModal.show();

                document.getElementById('cod-no').onclick = function () {
                    fetch(`/courier/delivery-order/in-progress/cancel/${hiddenShipmentId.value}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error("Gagal cancel pengiriman.");
                        codModal.hide();
                        location.reload();
                    })
                    .catch(error => {
                        console.error('Cancel Error:', error);
                    });
                };
                

                document.getElementById('cod-yes').onclick = function () {
                    codModal.hide();
                    setTimeout(() => {
                        detailModal.show();
                    }, 200);
                };
            } 
            else {
                detailModal.show();
            }
        });
    });
});
