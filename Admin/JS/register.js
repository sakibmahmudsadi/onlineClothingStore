const form = document.getElementById("registerForm");
const messageBox = document.getElementById("message");

const fields = {
    name: document.getElementById("name"),
    email: document.getElementById("email"),
    phone: document.getElementById("phone"),
    address: document.getElementById("address"),
    password: document.getElementById("password"),
    confirm_password: document.getElementById("confirm_password")
};

function setFieldError(key, msg) {
    const input = fields[key];
    const errorEl = document.getElementById(key + "Error");

    if (errorEl) {
        errorEl.textContent = msg || "";
        errorEl.classList.toggle("show", !!msg);
    }
    if (input) {
        input.classList.toggle("invalid", !!msg);
        input.classList.toggle("valid", !msg && input.value.trim() !== "");
    }
}

// Each validator returns an error string, or "" when the field is valid.
const validators = {
    name(value) {
        if (!value) return "Full name is required.";
        if (value.length < 2) return "Name must be at least 2 characters.";
        if (value.length > 100) return "Name must be under 100 characters.";
        return "";
    },
    email(value) {
        if (!value) return "Email is required.";
        const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!pattern.test(value)) return "Enter a valid email address.";
        return "";
    },
    phone(value) {
        if (!value) return ""; // optional
        const pattern = /^\+?[0-9\s-]{7,15}$/;
        if (!pattern.test(value)) return "Enter a valid phone number.";
        return "";
    },
    address(value) {
        if (value.length > 255) return "Address must be under 255 characters.";
        return "";
    },
    password(value) {
        if (!value) return "Password is required.";
        if (value.length < 8) return "Password must be at least 8 characters.";
        return "";
    },
    confirm_password(value) {
        if (!value) return "Please confirm your password.";
        if (value !== fields.password.value) return "Passwords do not match.";
        return "";
    }
};

function validateField(key) {
    const value = fields[key].value.trim();
    const error = validators[key](key === "password" ? fields[key].value : value);
    setFieldError(key, error);
    return error === "";
}

function validateAll() {
    let valid = true;
    for (const key of Object.keys(fields)) {
        if (!validateField(key)) valid = false;
    }
    return valid;
}

// Real-time validation as the user types, plus re-check confirm_password
// whenever the password itself changes.
Object.keys(fields).forEach((key) => {
    fields[key].addEventListener("input", () => validateField(key));
    fields[key].addEventListener("blur", () => validateField(key));
});
fields.password.addEventListener("input", () => {
    if (fields.confirm_password.value) validateField("confirm_password");
});

form.addEventListener("submit", async function (e) {
    e.preventDefault();

    messageBox.textContent = "";
    messageBox.className = "message";

    if (!validateAll()) {
        const firstInvalid = form.querySelector(".invalid");
        if (firstInvalid) firstInvalid.focus();
        return;
    }

    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = "Creating account...";

    try {
        const response = await fetch("../controller/register_controller.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                name: fields.name.value.trim(),
                email: fields.email.value.trim(),
                phone: fields.phone.value.trim(),
                address: fields.address.value.trim(),
                password: fields.password.value,
                confirm_password: fields.confirm_password.value
            })
        });

        const data = await response.json();

        if (!data.success) {
            // Surface a duplicate-email error against the email field itself,
            // any other server-side error goes in the general message box.
            if (/email/i.test(data.message)) {
                setFieldError("email", data.message);
            } else {
                messageBox.textContent = data.message;
                messageBox.classList.add("error");
            }
            return;
        }

        messageBox.textContent = data.message;
        messageBox.classList.add("success");
        form.reset();
        setTimeout(() => {
            window.location.href = "login.html";
        }, 1500);
    } catch (err) {
        messageBox.textContent = "Unable to reach the server. Please try again.";
        messageBox.classList.add("error");
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = "Register";
    }
});
