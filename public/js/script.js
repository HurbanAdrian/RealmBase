console.log("SCRIPT SA NACITAL");

document.addEventListener("DOMContentLoaded", () => {
    console.log("Validácia beží — kontrolujem formulár");
    const form = document.querySelector("form");
    if (!form) return;

    form.addEventListener("submit", (e) => {
        let errors = [];

        // CATEGORY FORM
        const nameInput = document.querySelector("input[name='name']");
        if (nameInput) {
            const value = nameInput.value.trim();
            if (value.length < 3) {
                errors.push("Názov musí mať aspoň 3 znaky.");
            }
            if (value.length > 50) {
                errors.push("Názov môže mať najviac 50 znakov.");
            }
        }

        // POST FORM
        const titleInput = document.querySelector("input[name='title']");
        const contentInput = document.querySelector("textarea[name='content']");

        if (titleInput) {
            const title = titleInput.value.trim();
            if (title.length < 3) {
                errors.push("Nadpis musí mať aspoň 3 znaky.");
            }
            if (title.length > 200) {
                errors.push("Nadpis môže mať maximálne 200 znakov.");
            }
        }

        if (contentInput) {
            const content = contentInput.value.trim();
            if (content.length < 10) {
                errors.push("Obsah musí mať aspoň 10 znakov.");
            }
        }

        if (errors.length > 0) {
            e.preventDefault();         // ZABRÁNI odoslaniu formulára
            alert(errors.join("\n"));   // Zobrazí chyby
        }
    });
});
