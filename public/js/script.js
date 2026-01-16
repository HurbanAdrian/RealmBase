/**
 * REALMBASE - HLAVNÝ JAVASCRIPT
 * -----------------------------
 * Tento súbor obsahuje všetku logiku na strane klienta (prehliadača).
 * Rieši interaktivitu, AJAX volania a animácie.
 */

console.log("REALMBASE SCRIPT LOADED - FINAL VERSION WITH COMMENTS & VALIDATION");

document.addEventListener("DOMContentLoaded", () => {

    // ============================================================
    // 1. AUTOMATICKÉ SKRÝVANIE UPOZORNENÍ (Flash Messages)
    // ============================================================
    // Nájde všetky elementy s triedou .alert (napr. "Úspešne uložené")
    const alerts = document.querySelectorAll('.alert');

    if (alerts.length > 0) {
        // Počkáme 4 sekundy (4000 ms) kým si to užívateľ prečíta
        setTimeout(() => {
            alerts.forEach(alert => {
                // Pridáme CSS transition pre plynulé zmiznutie
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = "0"; // Spriehľadníme element

                // Po skončení animácie (0.5s) element úplne odstránime z HTML
                setTimeout(() => alert.remove(), 500);
            });
        }, 4000);
    }

    // ============================================================
    // 2. LIVE SEARCH FILTER (Okamžité vyhľadávanie)
    // ============================================================
    // Filtruje riadky tabuľky podľa textu zadaného do inputu
    const searchInput = document.getElementById("tableSearch");
    const tableRows = document.querySelectorAll("table tbody tr");

    if (searchInput) {
        searchInput.addEventListener("keyup", () => {
            const value = searchInput.value.toLowerCase(); // Prevod na malé písmená

            // Prejdeme každý riadok tabuľky
            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                // Ak text riadku obsahuje hľadaný výraz, zobrazíme ho, inak skryjeme
                row.style.display = rowText.includes(value) ? "" : "none";
            });
        });
    }

// ============================================================
    // 3. VALIDÁCIA FORMULÁROV (Client-Side Validation)
    // ============================================================
    // Kontroluje dĺžku vstupov pre Články a Kategórie ešte pred odoslaním
    const form = document.querySelector("form");

    // Ignorujeme formulár pre komentáre (ten rieši AJAX nižšie)
    if (form && form.id !== "comment-form") {
        form.addEventListener("submit", (e) => {
            let errors = [];
            let errorInputs = [];

            // --- A) KONTROLA PRE KATEGÓRIE ---
            const nameInput = document.querySelector("input[name='name']");
            // OPRAVA: Musíme najprv nájsť element pre popis!
            const descInput = document.querySelector("[name='description']");

            // 1. Názov kategórie
            if (nameInput) {
                const value = nameInput.value.trim();
                if (value.length < 3) {
                    errors.push("Názov kategórie musí mať aspoň 3 znaky.");
                    errorInputs.push(nameInput);
                }
                // Nová kontrola: Max dĺžka pre kategóriu
                if (value.length > 50) {
                    errors.push("Názov kategórie je príliš dlhý (max 50 znakov).");
                    errorInputs.push(nameInput);
                }
            }

            // 2. Popis kategórie
            if (descInput) {
                const descValue = descInput.value.trim();
                if (descValue.length > 255) { // Zhoda s PHP
                    errors.push("Popis kategórie je príliš dlhý (max 255 znakov).");
                    errorInputs.push(descInput);
                }
            }

            // --- B) KONTROLA PRE ČLÁNKY (Title + Content) ---
            const titleInput = document.querySelector("input[name='title']");
            const contentInput = document.querySelector("textarea[name='content']");
            const categorySelect = document.querySelector("select[name='category_id']");

            // --- C) KONTROLA PRE REGISTRÁCIU ---
            const usernameInput = document.querySelector("input[name='username']");
            const emailInput = document.querySelector("input[name='email']");
            const passInput = document.querySelector("input[name='password']");
            const passVerifyInput = document.querySelector("input[name='password_verify']");

            // --- D) KONTROLA VEĽKOSTI OBRÁZKA (max 2MB) ---
            const imageInput = document.querySelector("input[name='image']");
            if (imageInput && imageInput.files.length > 0) {
                const fileSize = imageInput.files[0].size / 1024 / 1024; // Prevod na MB
                if (fileSize > 2) {
                    errors.push("Obrázok je príliš veľký (max 2MB).");
                    errorInputs.push(imageInput);
                }
            }

            // 1. Username
            if (usernameInput) {
                const val = usernameInput.value.trim();
                if (val.length < 3) {
                    errors.push("Meno musí mať aspoň 3 znaky.");
                    errorInputs.push(usernameInput);
                }
                if (val.length > 50) {
                    errors.push("Meno je príliš dlhé.");
                    errorInputs.push(usernameInput);
                }
            }

            // 2. Email (jednoduchý regex)
            if (emailInput) {
                const val = emailInput.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(val)) {
                    errors.push("Neplatný e-mail.");
                    errorInputs.push(emailInput);
                }
            }

            // 3. Heslá
            if (passInput && passVerifyInput) {
                if (passInput.value.length < 4) {
                    errors.push("Heslo musí mať aspoň 4 znaky.");
                    errorInputs.push(passInput);
                }
                if (passInput.value !== passVerifyInput.value) {
                    errors.push("Heslá sa nezhodujú.");
                    errorInputs.push(passVerifyInput);
                }
            }

            // 1. Nadpis
            if (titleInput) {
                const titleVal = titleInput.value.trim();
                if (titleVal.length < 3) {
                    errors.push("Nadpis musí mať aspoň 3 znaky.");
                    errorInputs.push(titleInput);
                }
                // Nová kontrola: Max dĺžka pre nadpis (zhoda s DB VARCHAR 255)
                if (titleVal.length > 255) {
                    errors.push("Nadpis je príliš dlhý (max 255 znakov).");
                    errorInputs.push(titleInput);
                }
            }

            // 2. Obsah
            if (contentInput) {
                const contentVal = contentInput.value.trim();
                if (contentVal.length < 10) {
                    errors.push("Obsah musí mať aspoň 10 znakov.");
                    errorInputs.push(contentInput);
                }
                // Nová kontrola: Max dĺžka pre článok (zhoda s PHP limitom)
                if (contentVal.length > 20000) {
                    errors.push("Obsah je príliš dlhý (max 20 000 znakov).");
                    errorInputs.push(contentInput);
                }
            }

            // 3. Kategória (musí byť vybratá)
            if (categorySelect) {
                if (categorySelect.value === "" || categorySelect.value === "0") {
                    errors.push("Musíte vybrať kategóriu.");
                    errorInputs.push(categorySelect);
                }
            }

            // Ak sme našli chyby, zastavíme odoslanie formulára
            if (errors.length > 0) {
                e.preventDefault(); // STOP
                alert(errors.join("\n")); // Vypíšeme chyby

                // Pridáme vizuálny efekt (červený rámik + zatrasenie)
                errorInputs.forEach(input => {
                    input.classList.add('is-invalid');
                    input.style.animation = "shake 0.3s"; // Spustenie CSS animácie
                    setTimeout(() => input.style.animation = "", 300); // Reset animácie

                    // Keď užívateľ začne písať, zrušíme červený rámik
                    input.addEventListener('input', function() {
                        this.classList.remove('is-invalid');
                    }, { once: true });
                });
            }
        });
    }

    // ============================================================
    // 4. AJAX KOMENTÁRE - PRIDÁVANIE (Create)
    // ============================================================
    // Odosiela nový komentár na server bez obnovenia stránky
    const commentForm = document.getElementById("comment-form");

    if (commentForm) {
        commentForm.addEventListener("submit", function (e) {
            e.preventDefault(); // Zabráni klasickému odoslaniu formulára

            // Rýchla validácia pred odoslaním requestu
            const contentInput = commentForm.querySelector("textarea[name='content']");
            const contentVal = contentInput.value.trim();

            if (contentVal.length < 3 || contentVal.length > 1000) {
                alert("Komentár musí mať dĺžku 3 až 1000 znakov.");
                contentInput.classList.add('is-invalid');
                contentInput.style.animation = "shake 0.3s";
                setTimeout(() => contentInput.style.animation = "", 300);
                return; // Neposielame nič na server
            } else {
                contentInput.classList.remove('is-invalid');
            }

            const formData = new FormData(commentForm); // Zozbiera dáta z inputov

            // Asynchrónna požiadavka na server (Controller: Comments -> add)
            fetch("?c=comments&a=add", {
                method: "POST",
                body: formData
            })
                .then(response => response.json()) // Očakávame JSON odpoveď
                .then(data => {
                    if (data.status === 'success') {
                        // 1. Vygenerujeme HTML pre nový komentár
                        // POZOR: Musí obsahovať správne ID a tlačidlá pre Edit/Delete!
                        const newCommentHtml = `
                    <div class="card mb-2 border-success" id="comment-${data.id}">
                        <div class="card-body py-2 d-flex justify-content-between align-items-start">
                            <div class="w-100">
                                <strong>${data.username}</strong>
                                <small class="text-muted ms-2">${data.created_at}</small>
                                
                                <p class="mb-0 mt-1 comment-text" id="comment-text-${data.id}">${data.content}</p>
                            </div>
                            
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-outline-warning btn-edit-comment" data-id="${data.id}">✏️</button>
                                <button class="btn btn-sm btn-outline-danger btn-delete-comment" data-id="${data.id}">×</button>
                            </div>
                        </div>
                    </div>`;

                        // 2. Vložíme nové HTML na koniec zoznamu
                        const list = document.getElementById("comments-list");
                        list.insertAdjacentHTML('beforeend', newCommentHtml);

                        // 3. Vyčistíme textové pole
                        commentForm.querySelector("textarea").value = "";

                        // 4. Zvýšime počítadlo komentárov
                        const countSpan = document.getElementById("comment-count");
                        if (countSpan) countSpan.innerText = parseInt(countSpan.innerText) + 1;
                    } else {
                        alert("Chyba: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("AJAX Error:", error);
                    alert("Nastala chyba pri komunikácii so serverom.");
                });
        });
    }

    // ============================================================
    // 5. UNIVERZÁLNA OBSLUHA UDALOSTÍ (Event Delegation) edit a delete pre comment
    // ============================================================
    // Používame "Event Delegation" na `document.body`.
    // Prečo? Lebo komentáre pribúdajú dynamicky a klasický `addEventListener`
    // by na ne nefungoval, ak boli pridané až po načítaní stránky.

    document.body.addEventListener("click", function (e) {

        // --- A) MAZANIE KOMENTÁRA (Delete) ---
        // Hľadáme najbližší element s triedou .btn-delete-comment
        const deleteBtn = e.target.closest(".btn-delete-comment");
        if (deleteBtn) {
            if (!confirm("Naozaj zmazať tento komentár?")) return;

            const commentId = deleteBtn.getAttribute("data-id");
            const formData = new FormData();
            formData.append("id", commentId);

            fetch("?c=comments&a=delete", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Odstránime element z DOM
                        const commentElement = document.getElementById("comment-" + commentId);
                        if (commentElement) commentElement.remove();

                        // Znížime počítadlo
                        const countSpan = document.getElementById("comment-count");
                        if (countSpan) countSpan.innerText = Math.max(0, parseInt(countSpan.innerText) - 1);
                    } else {
                        alert("Chyba: " + data.message);
                    }
                });
            return;
        }

        // --- B) EDITÁCIA - OTVORENIE FORMULÁRA (UI Change) ---
        const editBtn = e.target.closest(".btn-edit-comment");
        if (editBtn) {
            const id = editBtn.getAttribute("data-id");
            const textElem = document.getElementById("comment-text-" + id);

            if (!textElem) return; // Poistka ak element neexistuje

            const originalText = textElem.innerText; // Uložíme si pôvodný text
            const buttonsDiv = editBtn.parentElement;

            // 1. Skryjeme pôvodný text a tlačidlá
            textElem.style.display = "none";
            buttonsDiv.style.display = "none";

            // 2. Vytvoríme HTML pre editačný formulár (Textarea + Save/Cancel)
            const editForm = document.createElement("div");
            editForm.id = "edit-box-" + id;
            editForm.className = "mt-2";
            editForm.innerHTML = `
                <textarea class="form-control mb-2" rows="2" maxlength="1000">${originalText}</textarea>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-success btn-save-edit" data-id="${id}">Uložiť</button>
                    <button class="btn btn-sm btn-secondary btn-cancel-edit" data-id="${id}">Zrušiť</button>
                </div>
            `;

            // 3. Vložíme formulár do stránky
            textElem.parentNode.appendChild(editForm);
            return;
        }

        // --- C) EDITÁCIA - ZRUŠENIE (UI Revert) ---
        const cancelBtn = e.target.closest(".btn-cancel-edit");
        if (cancelBtn) {
            const id = cancelBtn.getAttribute("data-id");

            // Odstránime editačný box
            const editBox = document.getElementById("edit-box-" + id);
            if (editBox) editBox.remove();

            // Zobrazíme naspäť pôvodný text
            const textElem = document.getElementById("comment-text-" + id);
            if (textElem) textElem.style.display = "block";

            // Zobrazíme naspäť tlačidlá (Edit/Delete)
            const cardElement = document.getElementById("comment-" + id);
            if (cardElement) {
                const buttonsDiv = cardElement.querySelector(".d-flex.gap-1");
                if (buttonsDiv) buttonsDiv.style.display = "flex";
            }
            return;
        }

        // --- D) EDITÁCIA - ULOŽENIE (Update - AJAX) ---
        const saveBtn = e.target.closest(".btn-save-edit");
        if (saveBtn) {
            const id = saveBtn.getAttribute("data-id");
            const editBox = document.getElementById("edit-box-" + id);
            const newContent = editBox.querySelector("textarea").value.trim(); // Získame nový text

            // Rýchla validácia pri edite
            if (newContent.length < 3 || newContent.length > 1000) {
                alert("Komentár musí mať dĺžku 3 až 1000 znakov.");
                return;
            }

            // Pripravíme dáta pre server
            const formData = new FormData();
            formData.append("id", id);
            formData.append("content", newContent);

            // Pošleme na server
            fetch("?c=comments&a=edit", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // 1. Aktualizujeme text v HTML stránke (bez refreshu)
                        const textElem = document.getElementById("comment-text-" + id);
                        textElem.innerText = newContent;
                        textElem.style.display = "block";

                        // 2. Odstránime formulár
                        editBox.remove();

                        // 3. Vrátime tlačidlá
                        const cardElement = document.getElementById("comment-" + id);
                        const buttonsDiv = cardElement.querySelector(".d-flex.gap-1");
                        if (buttonsDiv) buttonsDiv.style.display = "flex";
                    } else {
                        alert("Chyba: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Edit Error:", error);
                    alert("Nepodarilo sa uložiť zmenu.");
                });
        }
    });
});

// ============================================================
// 6. CSS ANIMÁCIE (Injektované cez JS)
// ============================================================
// Vytvoríme štýl pre animáciu "shake" (trasenie pri chybe)
const styleSheet = document.createElement("style");
styleSheet.innerText = `
@keyframes shake {
  0% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  50% { transform: translateX(5px); }
  75% { transform: translateX(-5px); }
  100% { transform: translateX(0); }
}`;
document.head.appendChild(styleSheet);