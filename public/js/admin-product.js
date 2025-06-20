function enableEdit(inputId) {
    const input = document.getElementById(inputId);
    input.removeAttribute('readonly');
    input.focus();

    const penIcon = input.nextElementSibling;
    if (penIcon && penIcon.classList.contains('edit-icon')) {
        penIcon.style.display = 'none';
    }
}

    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('preview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }



