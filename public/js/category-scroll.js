document.addEventListener("DOMContentLoaded", function () {
    const allLink = document.querySelector('.category-all');
    if (allLink) {
        allLink.addEventListener('click', function () {
            localStorage.setItem('scrollToProduct', 'true');
        });
    }
    const shouldScroll = localStorage.getItem('scrollToProduct');
    if (shouldScroll === 'true') {
        const target = document.getElementById("product-container");
        if (target) {
            setTimeout(() => {
                target.scrollIntoView({ behavior: "smooth" });
                localStorage.removeItem('scrollToProduct');
            }, 300);
        }
    }
    
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('category')) {
        const target = document.getElementById("product-container");
        if (target) {
            setTimeout(() => {
                target.scrollIntoView({ behavior: "smooth" });
            }, 300);
        }
    }
});