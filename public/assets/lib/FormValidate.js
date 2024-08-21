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
    }

    // Method untuk memvalidasi form berdasarkan rules yang diberikan
    validate() {
        this.errors = {}; // reset errors
        this.clearErrors(); // Hapus kelas error sebelumnya
        for (const field in this.rules) {
            const fieldElement = this.form.querySelector(`[name="${field}"]`);
            if (!fieldElement) continue;

            const fieldRules = this.rules[field];
            for (const rule in fieldRules) {
                const ruleValue = fieldRules[rule];
                if (!this[rule](fieldElement, ruleValue)) {
                    if (!this.errors[field]) {
                        this.errors[field] = [];
                    }
                    this.errors[field].push(this.messages[field][rule]);
                    fieldElement.classList.add(this.errorClass); // Tambahkan kelas error
                }
            }
        }
        return Object.keys(this.errors).length === 0;
    }

    // Method untuk menampilkan error// Method untuk menampilkan error
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
                    `[name="${field}"]`
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

    // Method validasi untuk required
    required(fieldElement) {
        return fieldElement.value.trim() !== "";
    }

    // Method validasi untuk minimal panjang karakter
    minlength(fieldElement, length) {
        return fieldElement.value.trim().length >= length;
    }

    // Method validasi untuk tipe data date
    date(fieldElement) {
        const datePattern = /^\d{4}-\d{2}-\d{2}$/; // Format YYYY-MM-DD
        return datePattern.test(fieldElement.value);
    }

    // Method validasi untuk angka saja
    digits(fieldElement) {
        const digitPattern = /^\d+$/;
        return digitPattern.test(fieldElement.value);
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
