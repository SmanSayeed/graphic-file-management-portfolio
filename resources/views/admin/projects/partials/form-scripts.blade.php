<script>
document.addEventListener('DOMContentLoaded', function () {
    // Try multiple selectors to find the form
    const form = document.querySelector('[data-project-form]') || 
                 document.querySelector('form[action*="projects"]') ||
                 document.querySelector('form[method="POST"][enctype*="multipart"]');
    
    if (!form) {
        console.error('Project form not found');
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
        // Try to find inputs both within form and globally
        const input = form.querySelector(`#${inputId}`) || document.getElementById(inputId);
        const preview = form.querySelector(`#${previewId}`) || document.getElementById(previewId);

        if (!input) {
            console.warn(`Preview binding failed: Input #${inputId} not found`);
            return;
        }
        
        if (!preview) {
            console.warn(`Preview binding failed: Preview #${previewId} not found`);
            return;
        }

        const placeholder = preview.innerHTML;

        input.addEventListener('change', function (e) {
            e.stopPropagation(); // Prevent event bubbling
            
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
                    preview.classList.add('has-file');
                };
                reader.onerror = function() {
                    console.error('Error reading image file');
                    preview.innerHTML = placeholder;
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
                preview.classList.add('has-file');
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
                preview.classList.add('has-file');
            }
        });
    };

    // Bind previews for all file inputs
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

    // Queue processing handler - use the form variable already defined above
    if (form) {
        form.addEventListener('submit', function(e) {
            // Check if form has file inputs with files that are actually selected
            const fileInputs = form.querySelectorAll('input[type="file"]');
            const hasFiles = Array.from(fileInputs).some(input => {
                return input.files && input.files.length > 0 && input.files[0].size > 0;
            });
            
            // Only intercept if files are actually being uploaded
            if (hasFiles) {
                e.preventDefault();
                e.stopPropagation();
                
                // Show loading modal
                const modal = new bootstrap.Modal(document.getElementById('queueProcessingModal'));
                const modalElement = document.getElementById('queueProcessingModal');
                modal.show();
                
                // Disable form submit button
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                }
                
                // Prepare form data
                const formData = new FormData(form);
                
                // Submit via AJAX
                fetch(form.action, {
                    method: form.method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.has_assets && data.job_id) {
                            // Start polling for job status
                            pollJobStatus(data.job_id, data.project_id, modal, modalElement);
                        } else {
                            // No assets to process, redirect immediately
                            window.location.href = '{{ route("admin.projects.index") }}';
                        }
                    } else {
                        // Handle error
                        updateModalError(modalElement, data.message || 'An error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    updateModalError(modalElement, 'An error occurred while submitting the form');
                });
            }
        });
    }

    function pollJobStatus(jobId, projectId, modal, modalElement) {
        const maxAttempts = 120; // 10 minutes max (120 * 5 seconds)
        let attempts = 0;
        const pollInterval = 5000; // 5 seconds
        
        const titleElement = document.getElementById('queueProcessingTitle');
        const messageElement = document.getElementById('queueProcessingMessage');
        const statusElement = document.getElementById('queueProcessingStatus');
        
        const poll = setInterval(() => {
            attempts++;
            
            // Update status message
            if (statusElement) {
                statusElement.textContent = `Checking status... (${attempts}/${maxAttempts})`;
            }
            
            // Check job status
            fetch(`/admin/queue-status/${jobId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'completed') {
                    clearInterval(poll);
                    updateModalSuccess(modalElement, 'Files uploaded successfully!');
                    setTimeout(() => {
                        modal.hide();
                        window.location.href = '{{ route("admin.projects.index") }}';
                    }, 2000);
                } else if (data.status === 'failed') {
                    clearInterval(poll);
                    updateModalError(modalElement, data.error || 'Upload failed. Please try again.');
                } else if (data.status === 'processing') {
                    if (titleElement) titleElement.textContent = 'Uploading Files...';
                    if (messageElement) messageElement.textContent = 'Files are being uploaded to storage. Please wait...';
                    if (statusElement) statusElement.textContent = 'Processing...';
                } else if (data.status === 'pending') {
                    if (titleElement) titleElement.textContent = 'Queued for Processing...';
                    if (messageElement) messageElement.textContent = 'Files are queued for upload. Waiting for queue worker...';
                    if (statusElement) statusElement.textContent = 'Pending...';
                }
                
                // Check if max attempts reached
                if (attempts >= maxAttempts) {
                    clearInterval(poll);
                    updateModalError(modalElement, 'Processing is taking longer than expected. Please check the queue monitor.');
                }
            })
            .catch(error => {
                console.error('Polling error:', error);
                // Continue polling on error (might be temporary)
                if (attempts >= maxAttempts) {
                    clearInterval(poll);
                    updateModalError(modalElement, 'Unable to check status. Please check manually.');
                }
            });
        }, pollInterval);
    }

    function updateModalSuccess(modalElement, message) {
        const titleElement = document.getElementById('queueProcessingTitle');
        const messageElement = document.getElementById('queueProcessingMessage');
        const statusElement = document.getElementById('queueProcessingStatus');
        const spinner = modalElement.querySelector('.spinner-border');
        
        if (spinner) {
            spinner.classList.remove('spinner-border');
            spinner.classList.add('text-success');
            spinner.innerHTML = '<i class="bi bi-check-circle" style="font-size: 3rem;"></i>';
        }
        
        if (titleElement) titleElement.textContent = 'Success!';
        if (messageElement) messageElement.textContent = message;
        if (messageElement) messageElement.classList.remove('text-muted');
        if (messageElement) messageElement.classList.add('text-success');
        if (statusElement) statusElement.textContent = '';
    }

    function updateModalError(modalElement, error) {
        const titleElement = document.getElementById('queueProcessingTitle');
        const messageElement = document.getElementById('queueProcessingMessage');
        const statusElement = document.getElementById('queueProcessingStatus');
        const spinner = modalElement.querySelector('.spinner-border');
        
        if (spinner) {
            spinner.classList.remove('spinner-border');
            spinner.classList.add('text-danger');
            spinner.innerHTML = '<i class="bi bi-x-circle" style="font-size: 3rem;"></i>';
        }
        
        if (titleElement) titleElement.textContent = 'Error';
        if (messageElement) messageElement.textContent = error;
        if (messageElement) messageElement.classList.remove('text-muted');
        if (messageElement) messageElement.classList.add('text-danger');
        if (statusElement) statusElement.textContent = '';
        
        // Re-enable submit button
        const form = document.querySelector('[data-project-form]');
        if (form) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = false;
            }
        }
    }
});
</script>

