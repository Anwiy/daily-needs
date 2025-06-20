function previewImage(event) {
    const file = event.target.files[0];
    const reader = new FileReader(); 

    reader.onload = function() {
        const output = document.getElementById('profile-photo-preview');
        if (output) {
            output.src = reader.result; 
        } else {
            const placeholder = document.querySelector('.profile-photo-placeholder');
            const imgElement = document.createElement('img');
            imgElement.src = reader.result;
            imgElement.alt = "Profile Photo";
            imgElement.classList.add('profile-photo');
            placeholder.innerHTML = ''; 
            placeholder.appendChild(imgElement); 
        }
    };

    if (file) {
        reader.readAsDataURL(file);
    }
}

setTimeout(function() {
    const alert = document.getElementById('success-alert');
    if (alert) {
        alert.classList.add('hidden');
        setTimeout(function() {
            alert.style.display = 'none'; 
        }, 500); 
    }
}, 1500);

function hidePenIcon(iconId) {
    var icon = document.getElementById(iconId);
    icon.style.display = 'none';
}