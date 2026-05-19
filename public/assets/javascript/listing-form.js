/**
 * Listing Form - Image Upload Handler
 * Handles file selection, drag-and-drop, and preview for property images
 */

document.addEventListener('DOMContentLoaded', () => {
  const fileInput = document.getElementById('listingImages');
  const uploadZone = document.querySelector('[class*="border-dashed"]');
  const currentImagesContainer = document.querySelector('.mt-6.grid.gap-4');

  if (!fileInput) return;

  // ── File Input Change Handler ──────────────────────────────────────────────
  fileInput.addEventListener('change', (e) => {
    handleFiles(e.target.files);
  });

  // ── Drag and Drop Handlers ────────────────────────────────────────────────
  if (uploadZone) {
    uploadZone.addEventListener('dragover', (e) => {
      e.preventDefault();
      e.stopPropagation();
      uploadZone.classList.add('opacity-80', 'bg-secondary/5');
    });

    uploadZone.addEventListener('dragleave', (e) => {
      e.preventDefault();
      e.stopPropagation();
      uploadZone.classList.remove('opacity-80', 'bg-secondary/5');
    });

    uploadZone.addEventListener('drop', (e) => {
      e.preventDefault();
      e.stopPropagation();
      uploadZone.classList.remove('opacity-80', 'bg-secondary/5');
      
      const files = e.dataTransfer.files;
      if (files.length > 0) {
        fileInput.files = files;
        handleFiles(files);
      }
    });

    // Make the label clickable to open file picker
    uploadZone.addEventListener('click', () => {
      fileInput.click();
    });
  }

  // ── File Handling Function ───────────────────────────────────────────────
  function handleFiles(files) {
    if (files.length === 0) return;

    // Validate files
    const validFiles = Array.from(files).filter(file => {
      const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
      const maxSize = 10 * 1024 * 1024; // 10MB

      if (!validTypes.includes(file.type)) {
        showError(`${file.name} is not a valid image format. Allowed: JPG, PNG, WebP.`);
        return false;
      }

      if (file.size > maxSize) {
        showError(`${file.name} is too large. Maximum size: 10MB.`);
        return false;
      }

      return true;
    });

    if (validFiles.length === 0) return;

    // Update file input with only valid files
    const dataTransfer = new DataTransfer();
    validFiles.forEach(file => dataTransfer.items.add(file));
    fileInput.files = dataTransfer.files;

    // Show preview
    showPreview(validFiles);

    // Show success message
    showSuccess(`${validFiles.length} image${validFiles.length !== 1 ? 's' : ''} selected.`);
  }

  // ── Preview Function ─────────────────────────────────────────────────────
  function showPreview(files) {
    // Find or create preview container
    let previewContainer = document.querySelector('[data-upload-preview]');

    if (!previewContainer) {
      previewContainer = document.createElement('div');
      previewContainer.setAttribute('data-upload-preview', '');
      previewContainer.className = 'mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4';

      // Insert after the upload zone
      if (uploadZone) {
        uploadZone.parentNode.insertBefore(previewContainer, uploadZone.nextSibling);
      }
    }

    // Clear existing previews
    previewContainer.innerHTML = '';

    // Add preview for each file
    Array.from(files).forEach((file, index) => {
      const reader = new FileReader();

      reader.onload = (e) => {
        const previewItem = document.createElement('div');
        previewItem.className = 'relative overflow-hidden rounded-[1.25rem] border border-outline-variant/20 bg-surface-container-low shadow-soft';

        const img = document.createElement('img');
        img.src = e.target.result;
        img.alt = file.name;
        img.className = 'h-40 w-full object-cover';

        const label = document.createElement('div');
        label.className = 'flex items-center justify-between px-4 py-3 text-xs uppercase tracking-[0.18em] text-on-surface-variant';
        label.innerHTML = `<span>${index === 0 ? 'Primary' : 'Gallery'}</span><span>#${index + 1}</span>`;

        previewItem.appendChild(img);
        previewItem.appendChild(label);
        previewContainer.appendChild(previewItem);
      };

      reader.readAsDataURL(file);
    });
  }

  // ── Message Helpers ──────────────────────────────────────────────────────
  function showSuccess(message) {
    showMessage(message, 'success');
  }

  function showError(message) {
    showMessage(message, 'error');
  }

  function showMessage(message, type) {
    // Remove existing message
    const existingMessage = document.querySelector('[data-upload-message]');
    if (existingMessage) {
      existingMessage.remove();
    }

    const messageEl = document.createElement('div');
    messageEl.setAttribute('data-upload-message', '');
    messageEl.className = `mt-3 rounded-lg px-4 py-3 text-sm font-medium ${
      type === 'success' 
        ? 'bg-secondary/10 text-on-surface' 
        : 'bg-error/10 text-error'
    }`;
    messageEl.textContent = message;

    if (uploadZone) {
      uploadZone.parentNode.insertBefore(messageEl, uploadZone);
    }

    if (type === 'success') {
      setTimeout(() => messageEl.remove(), 4000);
    }
  }

  // ── Listing Toggle Group Handler ──────────────────────────────────────────
  const listingToggleGroups = document.querySelectorAll('[data-listing-toggle-group]');
  listingToggleGroups.forEach(group => {
    const buttons = group.querySelectorAll('[data-listing-toggle]');
    const targetId = group.getAttribute('data-target');
    const targetInput = targetId ? document.getElementById(targetId) : null;

    buttons.forEach(button => {
      button.addEventListener('click', () => {
        // Update button styles
        buttons.forEach(b => {
          if (b === button) {
            b.classList.remove('bg-surface-container-lowest', 'text-on-surface-variant');
            b.classList.add('bg-primary', 'text-white');
          } else {
            b.classList.remove('bg-primary', 'text-white');
            b.classList.add('bg-surface-container-lowest', 'text-on-surface-variant');
          }
        });

        // Update hidden input
        if (targetInput) {
          targetInput.value = button.getAttribute('data-listing-toggle');
        }
      });
    });
  });
});
