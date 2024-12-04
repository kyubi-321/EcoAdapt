document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".read-more").forEach(button => {
        button.addEventListener("click", () => {
            const serviceItem = button.closest(".service-item");
            const shortText = serviceItem.querySelector(".short-text");
            const fullText = serviceItem.querySelector(".full-text");

            if (button.textContent === "Read More") {
                shortText.classList.add("d-none");
                fullText.classList.remove("d-none");
                button.textContent = "Read Less";
            } else {
                shortText.classList.remove("d-none");
                fullText.classList.add("d-none");
                button.textContent = "Read More";
            }
        });
    });
});
