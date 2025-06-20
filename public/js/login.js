document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = document.querySelector(".toggle-password");
    const password = document.getElementById('customer_password');

    togglePassword.addEventListener('click', function () {
        if (password.type === "password") {
            password.type = "text";
            this.classList.remove("bi-eye-slash"); 
            this.classList.add("bi-eye"); 
        } else {
            password.type = "password";
            this.classList.remove("bi-eye"); 
            this.classList.add("bi-eye-slash"); 
        }
    });
});