<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('[data-project-form]');
    if (!form) {
        return;
    }

    const projectTypeSelect = form.querySelector('#projectType');
    const fileTypeSelect = form.querySelector('#fileType');
    const priceField = form.querySelector('#priceField');
    const imageSection = form.querySelector('#imageFileSection');
    const videoSection = form.querySelector('#videoFileSection');
    const sourceSection = form.querySelector('#sourceFileSection');
    const thumbnailSection = form.querySelector('#thumbnailSection');
    const thumbnailInput = form.querySelector('#thumbnailImage');
    const thumbnailRequired = form.querySelector('#thumbnailRequired');
    const thumbnailHint = form.querySelector('#thumbnailHint');
    const hasExistingThumbnail = thumbnailInput && thumbnailInput.dataset.hasExisting === 'true';

    const togglePriceField = () => {
        if (!projectTypeSelect || !priceField) {
            return;
        }
        priceField.style.display = projectTypeSelect.value === 'paid' ? 'block' : 'none';
    };

    const toggleFileSections = () => {
        if (!fileTypeSelect) {
            return;
        }

        const value = fileTypeSelect.value;

        if (imageSection) {
            imageSection.style.display = value === 'image' ? 'block' : 'none';
        }

        if (videoSection) {
            videoSection.style.display = value === 'video' ? 'block' : 'none';
        }

        if (sourceSection) {
            sourceSection.style.display = 'block';
        }

        if (thumbnailInput) {
            if (value === 'image') {
                thumbnailInput.required = !hasExistingThumbnail;
                if (thumbnailRequired) {
                    thumbnailRequired.style.display = hasExistingThumbnail ? 'none' : 'inline';
                }
                if (thumbnailHint) {
                    thumbnailHint.textContent = hasExistingThumbnail
                        ? 'Existing thumbnail will be kept unless you upload a new one.'
                        : 'Required for image projects';
                }
            } else if (value === 'video') {
                thumbnailInput.required = false;
                if (thumbnailRequired) {
                    thumbnailRequired.style.display = 'none';
                }
                if (thumbnailHint) {
                    thumbnailHint.textContent = 'Optional - used as fallback if video link fails';
                }
            } else {
                thumbnailInput.required = false;
                if (thumbnailRequired) {
                    thumbnailRequired.style.display = 'none';
                }
            }

            if (thumbnailSection) {
                thumbnailSection.style.display = 'block';
            }
        }
    };

    const bindPreview = (inputId, previewId, options = {}) => {
        const input = form.querySelector(`#${inputId}`);
        const preview = form.querySelector(`#${previewId}`);

        if (!input || !preview) {
            return;
        }

        const placeholder = preview.innerHTML;

        input.addEventListener('change', function () {
            if (!this.files || !this.files.length) {
                preview.innerHTML = placeholder;
                preview.classList.remove('has-file');
                return;
            }

            const file = this.files[0];
            const fileName = file.name;
            const fileSize = file.size ? `${Math.round(file.size / 1024)} KB` : '';

            if (options.type === 'image') {
                const reader = new FileReader();
                reader.onload = function (event) {
                    preview.innerHTML = `<img src="${event.target.result}" alt="${fileName}" style="width: 100%; height: 100%; object-fit: cover;">`;
                };
                reader.readAsDataURL(file);
            } else if (options.type === 'video') {
                preview.innerHTML = `
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <i class="bi bi-file-earmark-play text-danger me-3" style="font-size: 28px;"></i>
                        <div class="text-start">
                            <p class="mb-0 fw-semibold">${fileName}</p>
                            <small class="text-muted">${fileSize}</small>
                        </div>
                    </div>
                `;
            } else {
                preview.innerHTML = `
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <i class="bi bi-file-earmark-zip text-success me-3" style="font-size: 28px;"></i>
                        <div class="text-start">
                            <p class="mb-0 fw-semibold">${fileName}</p>
                            <small class="text-muted">${fileSize}</small>
                        </div>
                    </div>
                `;
            }

            preview.classList.add('has-file');
        });
    };

    bindPreview('thumbnailImage', 'thumbnailPreview', { type: 'image' });
    bindPreview('imageFile', 'imagePreview', { type: 'image' });
    bindPreview('videoFile', 'videoPreview', { type: 'video' });
    bindPreview('sourceFile', 'sourcePreview', { type: 'file' });

    if (projectTypeSelect) {
        projectTypeSelect.addEventListener('change', togglePriceField);
    }

    if (fileTypeSelect) {
        fileTypeSelect.addEventListener('change', toggleFileSections);
    }

    togglePriceField();
    toggleFileSections();
});
</script>

