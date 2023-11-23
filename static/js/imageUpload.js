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
