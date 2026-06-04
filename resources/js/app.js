import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form[data-validate-form]');

    forms.forEach((form) => {
        const fields = getFormFields(form);

        fields.forEach((field) => {
            prepareFeedbackElement(field);
            // Menentukan event yang tepat untuk validasi, misalnya 'change' untuk file dan select
            const eventName = field.type === 'file' || field.tagName === 'SELECT'
                ? 'change' //untuk file dan select, karena nilai berubah saat memilih file atau opsi
                : 'input'; //untuk input teks, textarea, dan lainnya, validasi saat mengetik

            field.addEventListener(eventName, () => validateField(field)); 
            field.addEventListener('blur', () => validateField(field)); 
        });

        form.addEventListener('submit', (event) => {
            const isValid = validateForm(form);

            if (!isValid) {
                event.preventDefault();

                showFormAlert(
                    form,
                    'Periksa kembali form. Masih ada data yang belum benar atau belum lengkap.'
                );

                const firstInvalid = form.querySelector('.is-invalid');

                if (firstInvalid) {
                    firstInvalid.focus({ preventScroll: true });
                    firstInvalid.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center',
                    });
                }
            }
        });
    });
});

function getFormFields(form) {
    return Array.from(form.querySelectorAll('input, select, textarea')).filter((field) => {
        return field.type !== 'hidden' && !field.disabled && !field.readOnly;
    });
}

function prepareFeedbackElement(field) {
    if (field.dataset.noFeedback === 'true') return null;

    const existing = getFeedbackElement(field);
    if (existing) return existing;

    //membuat elemen pesan error menggunakan DOM, dengan tag <small> dan class .js-field-error
    const feedback = document.createElement('small');
    feedback.className = 'js-field-error';
    feedback.setAttribute('aria-live', 'polite');
    feedback.hidden = true;

    field.insertAdjacentElement('afterend', feedback);

    return feedback;
}

function getFeedbackElement(field) {
    const next = field.nextElementSibling;

    if (next && next.classList.contains('js-field-error')) {
        return next;
    }

    return null;
}

function getFieldLabel(field) {
    if (field.dataset.label) {
        return field.dataset.label;
    }

    if (field.id) {
        const label = document.querySelector(`label[for="${field.id}"]`);

        if (label) {
            return label.textContent.replace('*', '').trim();
        }
    }

    const parent = field.closest('.mf-field, .cf-field, div');
    const parentLabel = parent ? parent.querySelector('label') : null;

    if (parentLabel) {
        return parentLabel.textContent.replace('*', '').trim();
    }

    return field.name || 'Field ini';
}

function validateForm(form) {
    const fields = getFormFields(form);
    let valid = true;

    fields.forEach((field) => {
        if (!validateField(field)) {
            valid = false;
        }
    });

    return valid;
}

function validateField(field) {
    const message = getValidationMessage(field);

    if (message) {
        setInvalid(field, message);
        return false;
    }

    setValid(field);
    return true;
}

function getValidationMessage(field) {
    const label = getFieldLabel(field);
    const value = (field.value || '').trim();

    // Validasi wajib isi
    if (field.required) {
        if (field.type === 'file') {
            if (!field.files || field.files.length === 0) {
                return field.dataset.msgRequired || `${label} wajib diunggah.`;
            }
        } else if (value === '') {
            return field.dataset.msgRequired || `${label} wajib diisi.`;
        }
    }

    // Kalau field tidak wajib dan kosong, tidak divalidasi lanjut
    if (!field.required && value === '' && field.type !== 'file') {
        return '';
    }

    // Validasi email
    if (field.type === 'email' && value !== '') {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(value)) {
            return field.dataset.msgEmail || `${label} harus menggunakan format email yang benar.`;
        }

        if (field.dataset.gmail === 'true' && !value.toLowerCase().endsWith('@gmail.com')) {
            return field.dataset.msgGmail || `${label} wajib menggunakan alamat Gmail, contoh: nama@gmail.com.`;
        }
    }

    // Validasi minimal karakter
    if (field.minLength > 0 && value.length < field.minLength) {
        return field.dataset.msgMinlength || `${label} minimal ${field.minLength} karakter.`;
    }

    // Validasi maksimal karakter
    if (field.maxLength > 0 && field.maxLength < 524288 && value.length > field.maxLength) {
        return field.dataset.msgMaxlength || `${label} maksimal ${field.maxLength} karakter.`;
    }

    // Validasi angka
    if (field.type === 'number' && value !== '') {
        const numberValue = Number(value);

        if (Number.isNaN(numberValue)) {
            return field.dataset.msgNumber || `${label} harus berupa angka.`;
        }

        if (field.min !== '' && numberValue < Number(field.min)) {
            return field.dataset.msgMin || `${label} minimal ${field.min}.`;
        }

        if (field.max !== '' && numberValue > Number(field.max)) {
            return field.dataset.msgMax || `${label} tidak boleh melebihi ${field.max}.`;
        }
    }

    // Validasi pattern, misalnya nomor WhatsApp
    if (field.pattern && value !== '' && field.validity.patternMismatch) {
        return field.dataset.msgPattern || `${label} belum sesuai format yang diminta.`;
    }

    // Validasi konfirmasi password / field harus sama
    if (field.dataset.match) {
        const target = document.querySelector(field.dataset.match);

        if (target && value !== target.value) {
            return field.dataset.msgMatch || `${label} harus sama dengan ${getFieldLabel(target)}.`;
        }
    }

    // Validasi file gambar dan ukuran file
    if (field.type === 'file' && field.files && field.files.length > 0) {
        const file = field.files[0];
        const allowedImageTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];

        if (field.accept && field.accept.includes('image') && !allowedImageTypes.includes(file.type)) {
            return field.dataset.msgFileType || `${label} harus berupa gambar JPG, JPEG, PNG, atau WEBP.`;
        }

        const maxSizeMb = Number(field.dataset.maxSize || 2);
        const maxSizeBytes = maxSizeMb * 1024 * 1024;

        if (file.size > maxSizeBytes) {
            return field.dataset.msgFileSize || `${label} maksimal ${maxSizeMb}MB.`;
        }
    }

    return '';
}
// Fungsi untuk menandai field sebagai valid atau tidak valid, dan menampilkan pesan error jika ada
function setInvalid(field, message) {
    field.classList.add('is-invalid');
    field.classList.remove('is-valid');
    field.setAttribute('aria-invalid', 'true');

    const feedback = prepareFeedbackElement(field);

    if (feedback) {
        feedback.textContent = message;
        feedback.hidden = false;
    }
}

function setValid(field) {
    field.classList.remove('is-invalid');
    field.setAttribute('aria-invalid', 'false');

    const hasValue = field.type === 'file'
        ? field.files && field.files.length > 0
        : (field.value || '').trim() !== '';

    if (hasValue) {
        field.classList.add('is-valid');
    } else {
        field.classList.remove('is-valid');
    }

    const feedback = getFeedbackElement(field);

    if (feedback) {
        feedback.textContent = '';
        feedback.hidden = true;
    }
}

function showFormAlert(form, message) {
    let alert = form.querySelector('.js-form-alert');

    if (!alert) {
        alert = document.createElement('div');
        alert.className = 'js-form-alert';
        form.prepend(alert);
    }

    alert.textContent = message;
    alert.hidden = false;

    clearTimeout(alert._timer);

    alert._timer = setTimeout(() => {
        alert.hidden = true;
    }, 5000);
}