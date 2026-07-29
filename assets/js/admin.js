/**
 * Balaji Kitchenware - Admin Dashboard JS
 * Image Previews, Multi-photo Upload Handler, Delete Confirmations
 */

document.addEventListener('DOMContentLoaded', () => {
    
    // Single image preview
    const singleImageInput = document.getElementById('singleImageInput');
    const singleImagePreview = document.getElementById('singleImagePreview');

    if (singleImageInput && singleImagePreview) {
        singleImageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    singleImagePreview.src = e.target.result;
                    singleImagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Multiple Images Preview Handler for Product Form
    const multiImagesInput = document.getElementById('multiImagesInput');
    const multiPreviewContainer = document.getElementById('multiPreviewContainer');

    if (multiImagesInput && multiPreviewContainer) {
        multiImagesInput.addEventListener('change', function() {
            multiPreviewContainer.innerHTML = '';
            const files = Array.from(this.files);

            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgBox = document.createElement('div');
                        imgBox.style.cssText = 'position:relative; width:80px; height:80px; border-radius:8px; overflow:hidden; border:1px solid #E2E8F0;';
                        imgBox.innerHTML = `<img src="${e.target.result}" style="width:100%; height:100%; object-fit:cover;">`;
                        multiPreviewContainer.appendChild(imgBox);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    }

    // Confirm Delete Actions
    document.querySelectorAll('.btn-confirm-delete').forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

});
