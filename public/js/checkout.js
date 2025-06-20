$(document).ready(function() {
    $('.select-address').click(function() {
        let addressId = $(this).data('id');
        let addressText = $(this).text();
        $('#selectedAddress').val(addressId);
        $('#selectedAddressText').text(addressText);
    });

    $('#paymentMethod').change(function() {
        let selectedPayment = $(this).val();
        console.log("Selected Payment Method:", selectedPayment);
        $('#selectedPayment').val(selectedPayment);
    });

    $('#checkoutForm').submit(function(e) {
        e.preventDefault();

        let paymentMethod = $('#paymentMethod').val();
        let customerAddress = $('#selectedAddress').val();

        $('.error-message').text(''); 

        if (!paymentMethod) {
            $('#error_payment_method').text('Please select a payment method.');
        }
        if (!customerAddress) {
            $('#error_customer_address').text('Please select an address.');
        }

        if (!paymentMethod || !customerAddress) {
            return;
        }

        if (typeof handleInsufficientBalance === 'function') {
            let isEnough = handleInsufficientBalance(this);
            if (!isEnough) return;
        }

        this.submit();
    });
});
