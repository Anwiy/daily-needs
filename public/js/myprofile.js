function previewImage(event) {
    const file = event.target.files[0]; // Get the selected file
    const reader = new FileReader(); // Create a FileReader instance

    reader.onload = function() {
        const output = document.getElementById('profile-photo-preview');
        // If the preview element exists, update its source
        if (output) {
            output.src = reader.result; // Set the result (image preview) to the img src
        } else {
            // If no img is present (i.e., it's an icon placeholder), replace it with a new image element
            const placeholder = document.querySelector('.profile-photo-placeholder');
            const imgElement = document.createElement('img');
            imgElement.src = reader.result;
            imgElement.alt = "Profile Photo";
            imgElement.classList.add('profile-photo');
            placeholder.innerHTML = ''; // Clear the icon
            placeholder.appendChild(imgElement); // Append the preview image
        }
    };

    if (file) {
        reader.readAsDataURL(file); // Read the file as DataURL for image preview
    }
}