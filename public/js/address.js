$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function fetchAddressList() {
        $.ajax({
            url: '/address',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                let addressList = $('#address-list');
                addressList.empty();
    
                if (response.addresses.length === 0) {
                    addressList.append('<p>No address available.</p>');
                    return;
                }

                addressList.append(`
                    <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        Add New Address
                    </button>
                `);
    
                response.addresses.forEach(address => {
                    addressList.append(`
                        <div class="card p-3 mb-2">
                            <button class="btn btn-warning btn-sm edit-address"
                                data-id="${address.customer_address_id}"
                                data-name="${address.customer_address_name}"
                                data-street="${address.customer_address_street}"
                                data-district="${address.customer_address_district}"
                                data-city="${address.customer_address_regency_city}"
                                data-province="${address.customer_address_province}"
                                data-country="${address.customer_address_country}"
                                data-postal="${address.customer_address_postal_code}"
                                data-bs-toggle="modal" data-bs-target="#updateAddressModal">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form class="delete-address" action="/address/delete/${address.customer_address_id}" method="POST">
                                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                <input type="hidden" name="_method" value="DELETE"> 
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                            <strong>${address.customer_address_name}</strong>
                            <p>${address.customer_address_street}, ${address.customer_address_district}, ${address.customer_address_regency_city}, ${address.customer_address_province}, ${address.customer_address_country}, ${address.customer_address_postal_code}</p>
                            <button class="btn btn-success btn-sm select-address" data-id="${address.customer_address_id}">
                                Select
                            </button>
                        </div>
                    `);
                });
    
                $('#addressModal').modal('show');
                if (callback) callback();
            }
        });
    }
    

    $('#saveAddress').click(function (e) {
        e.preventDefault();

        let form = $('#addressForm');
        let url = $(this).data('url');
        let formData = form.serialize();

        $('.error-message').text('');

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('.notification').html(
                        `<div class="alert alert-success">${response.message}</div>`
                    );

                    form[0].reset();

                    fetchAddressList();

                    $('#addAddressModal').modal('hide');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    
                    $.each(errors, function (field, messages) {
                        let inputField = $(`[name="${field}"]`);
                        let errorMessage = messages[0];
                        inputField.next('.error-message').text(errorMessage);
                    });
                }
            }
        });
    });

    $(document).on("click", ".edit-address", function() {
        let id = $(this).data("id");
        let name = $(this).data("name");
        let street = $(this).data("street");
        let district = $(this).data("district");
        let city = $(this).data("city");
        let province = $(this).data("province");
        let country = $(this).data("country");
        let postal = $(this).data("postal");

        $("#update_address_id").val(id);
        $("#update_address_name").val(name);
        $("#update_street").val(street);
        $("#update_postal_code").val(postal);
        $("#update_district").val(district);
        $("#update_regency_city").val(city);
        $("#update_province").val(province);
        $("#update_country").val(country);

        $("#updateAddressModal").modal("show");
    });

    $('#updateAddress').click(function(e) {
        e.preventDefault();

        let id = $("#update_address_id").val(); 
        let form = $('#updateAddressForm');
        let formData = form.serialize();
        let url = `/address/update/${id}`;

        $('.error-message').text('');

        $.ajax({
            type: 'PUT',
            url: url,
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('.notification').html(
                        `<div class="alert alert-success">${response.message}</div>`
                    );

                    $('#updateAddressModal').modal('hide');

                    fetchAddressList(function() {
                        $('#addressModal').modal('show');
                    });
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    
                    $.each(errors, function (field, messages) {
                        let inputField = $(`[name="${field}"]`);
                        let errorMessage = messages[0];
                        inputField.next('.error-message').text(errorMessage);
                    });
                }
            }
        });
    });

    $(document).on('submit', '.delete-address', function(e) {
        e.preventDefault(); 
    
        let form = $(this); 
        let url = form.attr('action');
        let card = form.closest('.card'); 
    
        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    card.remove();
                    $('.notification').html(
                        `<div class="alert alert-success">${response.message}</div>`
                    );
                } 
                else {
                    alert(response.error);
                }
            },
            error: function(xhr) {
                alert('Error removing address. Please try again.');
            }
        });
    });

    $(document).on('click', '.select-address', function () {

        let card = $(this).closest('.card');
        let addressId = $(this).data('id');
        let addressName = card.find('strong').text();
        let fullAddress = card.find('p').text();
    
        let selectedFullAddress = `<span style="font-weight: 600;">${addressName}</span><br>${fullAddress}`;
        $('#selectedAddress').val(addressId);
        $('#selectedAddressText').html(selectedFullAddress);
        $('#addressModal').modal('hide');
    });
});