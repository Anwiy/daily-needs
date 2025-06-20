function handleInsufficientBalance(form) {
    const balance = parseFloat($('#customerBalance').val());
    const totalPrice = parseFloat($('#totalPrice').val());
    const selectedPaymentName = $('#paymentMethod option:selected').data('name');

    if (selectedPaymentName === 'Application Balance' && balance < totalPrice) {
        $('#currentBalanceText').text('Rp ' + balance.toLocaleString('id-ID'));
        let modal = new bootstrap.Modal(document.getElementById('insufficientBalanceModal'));
        modal.show();
        return false;
    }

    return true;
}