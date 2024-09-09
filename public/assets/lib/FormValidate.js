class ValidateForm {
    constructor(formElement, options = {}) {
        if (!formElement) {
            throw new Error("Form element is required");
        }
        this.form = formElement;
        this.errors = {};
        this.rules = options.rules || {};
        this.messages = options.messages || {};
        this.submitHandler =
            options.submitHandler ||
            function (form) {
                form.submit();
            };
        this.errorClass = options.errorClass || "error"; // Default class name for errors
        this.initDigitOnlyFields();
        this.dynamicRules = options.dynamicRules || {};
        this.updateRules();
    }

    updateRules() {
        for (const field in this.dynamicRules) {
            const rulesConfig = this.dynamicRules[field];
            if (rulesConfig[this.modeForm] !== undefined) {
                // Update or remove rules based on the current mode
                for (const rule in rulesConfig[this.modeForm]) {
                    if (rulesConfig[this.modeForm][rule] === false) {
                        // Remove the rule if set to false
                        delete this.rules[field][rule];
                    } else {
                        // Add or update the rule
                        this.rules[field][rule] = rulesConfig[this.modeForm][rule];
                    }
                }
            }
        }
    }

    // Method untuk memvalidasi form berdasarkan rules yang diberikan
    validate() {
        this.errors = {}; // reset errors
        this.clearErrors(); // Hapus kelas error sebelumnya
        for (const field in this.rules) {
            const fieldElements = this.form.querySelectorAll(`[name="${field}"], [name="${field}[]"]`);
            if (fieldElements.length === 0) continue;

            const fieldRules = this.rules[field];
            for (const rule in fieldRules) {
                const ruleValue = fieldRules[rule];
                if (!this[rule](fieldElements, ruleValue)) {
                    if (!this.errors[field]) {
                        this.errors[field] = [];
                    }
                    this.errors[field].push(this.messages[field][rule]);
                    fieldElements.forEach(fieldElement => {
                        fieldElement.classList.add(this.errorClass); // Tambahkan kelas error
                    });
                }
            }
        }
        return Object.keys(this.errors).length === 0;
    }

    // Method untuk menampilkan error
    showErrors() {
        for (const field in this.errors) {
            const errorMessages = this.errors[field];
            let errorContainer = this.form.querySelector(`#${field}-error`);

            // Jika errorContainer belum ada, buat secara dinamis
            if (!errorContainer) {
                errorContainer = document.createElement("div");
                errorContainer.id = `${field}-error`;
                errorContainer.className = `invalid-feedback is-invalid-${field}`;
                const fieldElement = this.form.querySelector(
                    `[name="${field}"], [name="${field}[]"]`
                );
                fieldElement.insertAdjacentElement("afterend", errorContainer);
            }

            // Tampilkan pesan error
            errorContainer.innerHTML = errorMessages.join("<br>");
        }
    }

    // Method untuk menghapus kelas error dari semua input
    clearErrors() {
        const elements = this.form.querySelectorAll(`.${this.errorClass}`);
        elements.forEach((element) => {
            element.classList.remove(this.errorClass);
        });

        // Bersihkan pesan error dari elemen-elemen invalid-feedback
        const errorContainers = this.form.querySelectorAll(".invalid-feedback");
        errorContainers.forEach((container) => {
            container.innerHTML = "";
        });
    }

    // Method untuk menjalankan submitHandler jika validasi sukses
    handleSubmit(event) {
        event.preventDefault();
        if (this.validate()) {
            this.submitHandler(this.form);
        } else {
            this.showErrors();
        }
    }

    // Method validasi untuk required (checkbox array handled here)
    required(fieldElements) {
        if (fieldElements[0].type === "checkbox") {
            return Array.from(fieldElements).some((checkbox) => checkbox.checked);
        }
        if (fieldElements[0].type === "radio") {
            return Array.from(fieldElements).some((radio) => radio.checked);
        }
        if (fieldElements[0].type === "file") {
            return fieldElements[0].files.length > 0;
        }
        return fieldElements[0].value.trim() !== "";
    }

    email(fieldElements) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(fieldElements[0].value);
    }

    // Method validasi untuk minimal panjang karakter
    minlength(fieldElements, length) {
        return fieldElements[0].value.trim().length >= length;
    }

    // Method validasi untuk tipe data date
    date(fieldElements) {
        const datePattern = /^\d{4}-\d{2}-\d{2}$/; // Format YYYY-MM-DD
        return datePattern.test(fieldElements[0].value);
    }

    // Method validasi untuk angka saja
    digits(fieldElements) {
        const digitPattern = /^\d+$/;
        return digitPattern.test(fieldElements[0].value);
    }

    // Method validasi untuk input type file (optional file type validation)
    file(fieldElements, allowedTypes = []) {
        if (fieldElements[0].files.length === 0) {
            return false; // No file selected
        }
        if (allowedTypes.length > 0) {
            const fileType = fieldElements[0].files[0].type;
            return allowedTypes.includes(fileType);
        }
        return true; // If no specific file types are set, just check that a file is selected
    }

    // Method untuk inisialisasi input hanya angka
    initDigitOnlyFields() {
        for (const field in this.rules) {
            if (this.rules[field].digits) {
                const fieldElement = this.form.querySelector(
                    `[name="${field}"]`
                );
                if (fieldElement) {
                    fieldElement.addEventListener("input", (event) => {
                        event.target.value = event.target.value.replace(
                            /[^\d]/g,
                            ""
                        );
                    });
                }
            }
        }
    }

    // Method untuk inisialisasi form event
    init() {
        this.form.addEventListener("submit", (e) => this.handleSubmit(e));
    }
}
