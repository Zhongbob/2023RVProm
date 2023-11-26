const imageUpload = nominationPopup.querySelector('#image-upload');
const imagePreview = nominationPopup.querySelector('.image-preview');
const imageText = nominationPopup.querySelector('.image-upload__text');
 function handleFile(file){
    imageText.innerText = file.name;
    const reader = new FileReader();
    reader.onload = function(e){
        imagePreview.src = e.target.result;
    }
    reader.readAsDataURL(file);
 }
    imageUpload.addEventListener('change', function(){
        handleFile(this.files[0]);
    });

async function compressImage(file, maxWidth, maxHeight) {
    return new Promise((resolve, reject) => {
        // Create an image object
        const img = new Image();
        img.src = URL.createObjectURL(file);

        img.onload = () => {
            // Create a canvas element
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            // Calculate the new dimensions
            let width = img.width;
            let height = img.height;

            if (width > height) {
                if (width > maxWidth) {
                    height *= maxWidth / width;
                    width = maxWidth;
                }
            } else {
                if (height > maxHeight) {
                    width *= maxHeight / height;
                    height = maxHeight;
                }
            }

            // Set canvas dimensions
            canvas.width = width;
            canvas.height = height;

            // Draw the image on canvas
            ctx.drawImage(img, 0, 0, width, height);

            // Get the compressed image
            canvas.toBlob((blob) => {
                resolve(blob);
            }, 'image/jpeg');
        };

        img.onerror = reject;
    });
}

