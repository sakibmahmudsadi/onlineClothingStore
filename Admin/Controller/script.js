const form = document.getElementById("productForm");

const category = document.getElementById("category");

const image = document.getElementById("image");

const formMessage =
    document.getElementById("formMessage");


const categories = [
    "men",
    "woman",
    "child"
];


function setError(input, errorId, message) {

    const error =
        document.getElementById(errorId);

    error.textContent = message;

    input.classList.toggle(
        "invalid",
        !!message
    );

    input.classList.toggle(
        "valid",
        !message && input.value !== ""
    );
}


function clearAllErrors() {

    document
        .querySelectorAll(".error")
        .forEach(function (el) {

            el.textContent = "";

        });


    document
        .querySelectorAll(
            "input, select, textarea"
        )
        .forEach(function (el) {

            el.classList.remove(
                "invalid",
                "valid"
            );

        });

}


function validateName() {

    const input =
        document.getElementById("name");

    const value =
        input.value.trim();


    if (!value) {

        setError(
            input,
            "nameError",
            "Product name is required."
        );

        return false;
    }


    if (value.length < 2) {

        setError(
            input,
            "nameError",
            "Name must be at least 2 characters."
        );

        return false;
    }


    if (value.length > 100) {

        setError(
            input,
            "nameError",
            "Name must not exceed 100 characters."
        );

        return false;
    }


    setError(
        input,
        "nameError",
        ""
    );

    return true;
}


function validateDescription() {

    const input =
        document.getElementById(
            "description"
        );

    const value =
        input.value.trim();


    if (!value) {

        setError(
            input,
            "descriptionError",
            "Description is required."
        );

        return false;
    }


    if (value.length < 5) {

        setError(
            input,
            "descriptionError",
            "Description must be at least 5 characters."
        );

        return false;
    }


    if (value.length > 1000) {

        setError(
            input,
            "descriptionError",
            "Description must not exceed 1000 characters."
        );

        return false;
    }


    setError(
        input,
        "descriptionError",
        ""
    );

    return true;
}


function validateSizeChart() {

    const input =
        document.getElementById(
            "size_chart"
        );

    const value =
        input.value.trim();


    if (!value) {

        setError(
            input,
            "sizeChartError",
            ""
        );

        return true;
    }


    if (
        value.startsWith("{") ||
        value.startsWith("[")
    ) {

        try {

            JSON.parse(value);

        } catch (error) {

            setError(
                input,
                "sizeChartError",
                "Size chart contains invalid JSON."
            );

            return false;
        }
    }


    setError(
        input,
        "sizeChartError",
        ""
    );

    return true;
}


function validatePrice() {

    const input =
        document.getElementById("price");

    const value =
        Number(input.value);


    if (
        input.value === "" ||
        !Number.isFinite(value) ||
        value <= 0
    ) {

        setError(
            input,
            "priceError",
            "Price must be greater than 0."
        );

        return false;
    }

    if (input.value > 999.99) {

        setError(
            input,
            "priceError",
            "Price must be less than or equal to 999.99."
        );

        return false;
    }


    setError(
        input,
        "priceError",
        ""
    );

    return true;
}


function validateStock() {

    const input =
        document.getElementById("stock");

    const value =
        Number(input.value);


    if (
        input.value === "" ||
        !Number.isInteger(value) ||
        value < 0
    ) {

        setError(
            input,
            "stockError",
            "Stock must be a whole number 0 or greater."
        );

        return false;
    }


    setError(
        input,
        "stockError",
        ""
    );

    return true;
}


function validateCategory() {

    if (!category.value) {

        setError(
            category,
            "categoryError",
            "Please select a category."
        );

        return false;
    }


    if (
        !categories.includes(category.value)
    ) {

        setError(
            category,
            "categoryError",
            "Invalid category."
        );

        return false;
    }


    setError(
        category,
        "categoryError",
        ""
    );

    return true;
}


function validateImage() {

    if (!image.files.length) {

        setError(
            image,
            "imageError",
            "Product image is required."
        );

        return false;
    }


    const file =
        image.files[0];


    const allowedTypes = [
        "image/jpeg",
        "image/png"
    ];


    const allowedExtensions = [
        "jpg",
        "jpeg",
        "png"
    ];


    const extension =
        file.name
            .split(".")
            .pop()
            .toLowerCase();


    const maxSize =
        2 * 1024 * 1024;



    if (
        !allowedTypes.includes(file.type)
    ) {

        setError(
            image,
            "imageError",
            "Only JPEG and PNG images are allowed."
        );

        return false;
    }



    if (
        !allowedExtensions.includes(extension)
    ) {

        setError(
            image,
            "imageError",
            "Only .jpg, .jpeg and .png files are allowed."
        );

        return false;
    }



    if (file.size > maxSize) {

        setError(
            image,
            "imageError",
            "Image must be 2MB or smaller."
        );

        return false;
    }


    setError(
        image,
        "imageError",
        ""
    );

    return true;
}

document
    .getElementById("name")
    .addEventListener(
        "input",
        validateName
    );


document
    .getElementById("description")
    .addEventListener(
        "input",
        validateDescription
    );


document
    .getElementById("size_chart")
    .addEventListener(
        "input",
        validateSizeChart
    );


document
    .getElementById("price")
    .addEventListener(
        "input",
        validatePrice
    );


document
    .getElementById("stock")
    .addEventListener(
        "input",
        validateStock
    );


category.addEventListener(
    "change",
    validateCategory
);


image.addEventListener(
    "change",
    validateImage
);


form.addEventListener(
    "submit",
    function (event) {

        clearAllErrors();


        const validName =
            validateName();

        const validDescription =
            validateDescription();

        const validSizeChart =
            validateSizeChart();

        const validPrice =
            validatePrice();

        const validStock =
            validateStock();

        const validCategory =
            validateCategory();

        const validImage =
            validateImage();


        const valid =
            validName &&
            validDescription &&
            validSizeChart &&
            validPrice &&
            validStock &&
            validCategory &&
            validImage;


        if (!valid) {

            event.preventDefault();


            formMessage.textContent =
                "Please correct the errors before submitting.";


            formMessage.className =
                "form-message error-message";


            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });

            return;
        }

        formMessage.textContent = "";

        formMessage.className =
            "form-message";

    }
);


const urlParams =
    new URLSearchParams(
        window.location.search
    );


const success =
    urlParams.get("success");


const message =
    urlParams.get("message");


if (
    success === "true" &&
    message
) {

    formMessage.textContent =
        message;

    formMessage.className =
        "form-message success-message";

}