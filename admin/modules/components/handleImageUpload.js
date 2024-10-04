function handleImageUpload(inputElement, componentName) {
    console.log(inputElement, componentName);
    if (inputElement.files && inputElement.files[0]) {
        const fileReader = new FileReader();

        fileReader.onload = function(e) {
            const previewId = 'imagePreview' + componentName;
            const cropBtnId = 'cropBtn' + componentName;
            const previewImage = document.getElementById(previewId);
            const cropBtn = document.getElementById(cropBtnId);

            if (previewImage) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
                previewImage.classList.remove('d-none');

                if (cropBtn) {
                    cropBtn.classList.remove('opacity-0');
                }

                // Initialize Cropper.js
                const cropper = new Cropper(previewImage, {
                    viewMode: 1,
                    autoCropArea: 0.5,
                    ready() {
                        console.log('Cropper is ready!');
                    }
                });

                // When the user clicks the crop button
                cropBtn.onclick = function(event) {
                    event.preventDefault(); // Prevent form submission

                    const canvas = cropper.getCroppedCanvas();
                    const croppedImageDataUrl = canvas.toDataURL('image/png');
                    console.log(croppedImageDataUrl);

                    // Set the base64 data into the hidden input field
                    const hiddenInput = document.getElementById('dataPassImg_' + componentName);
                    console.log(hiddenInput)
                    if (hiddenInput) {
                        hiddenInput.value = croppedImageDataUrl;
                    }

                    console.log('Cropped image data set in hidden input.');
                };
            }
        };

        fileReader.readAsDataURL(inputElement.files[0]);
    }
}
