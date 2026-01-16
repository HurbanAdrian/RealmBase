console.log("SCRIPT SA NACITAL");

document.addEventListener("DOMContentLoaded", () => {
    // ============================
    // 1. LIVE SEARCH FILTER (Tvoj pôvodný kód)
    // ============================
    const searchInput = document.getElementById("tableSearch");
    const tableRows = document.querySelectorAll("table tbody tr");

    if (searchInput) {
        searchInput.addEventListener("keyup", () => {
            const value = searchInput.value.toLowerCase();

            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(value) ? "" : "none";
            });
        });
    }

    // ============================
    // 2. VALIDÁCIA FORMULÁROV (Tvoj pôvodný kód)
    // ============================
    console.log("Validácia beží — kontrolujem formulár");
    const form = document.querySelector("form");

    // Pozor: Toto by mohlo kolidovať s formulárom komentárov, preto pridáme kontrolu ID
    // Validujeme len hlavné formuláre (nie ten ajaxový pre komentáre)
    if (form && form.id !== "comment-form") {
        form.addEventListener("submit", (e) => {
            let errors = [];

            // CATEGORY FORM
            const nameInput = document.querySelector("input[name='name']");
            if (nameInput) {
                const value = nameInput.value.trim();
                if (value.length < 3) errors.push("Názov musí mať aspoň 3 znaky.");
                if (value.length > 50) errors.push("Názov môže mať najviac 50 znakov.");
            }

            // POST FORM
            const titleInput = document.querySelector("input[name='title']");
            const contentInput = document.querySelector("textarea[name='content']");

            if (titleInput) {
                const title = titleInput.value.trim();
                if (title.length < 3) errors.push("Nadpis musí mať aspoň 3 znaky.");
                if (title.length > 200) errors.push("Nadpis môže mať maximálne 200 znakov.");
            }

            if (contentInput) {
                const content = contentInput.value.trim();
                if (content.length < 10) errors.push("Obsah musí mať aspoň 10 znakov.");
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert(errors.join("\n"));
            }
        });
    }

    // ============================
    // 3. AJAX KOMENTÁRE (Nový kód)
    // ============================
    const commentForm = document.getElementById("comment-form");

    if (commentForm) {
        commentForm.addEventListener("submit", function (e) {
            e.preventDefault(); // Žiadny refresh stránky!

            const formData = new FormData(commentForm);

            fetch("?c=comments&a=add", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Vytvorenie HTML pre nový komentár (TERAZ AJ S TLAČIDLOM ZMAZAŤ)
                        const newCommentHtml = `
                        <div class="card mb-2 border-success" id="comment-${data.id}">
                            <div class="card-body py-2 d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>${data.username}</strong>
                                    <small class="text-muted ms-2">${data.created_at}</small>
                                    <p class="mb-0 mt-1">${data.content}</p>
                                </div>
                                
                                <button class="btn btn-sm btn-outline-danger btn-delete-comment" 
                                        data-id="${data.id}">
                                    ×
                                </button>
                            </div>
                        </div>
                    `;

                        // Vloženie do zoznamu
                        const list = document.getElementById("comments-list");
                        list.insertAdjacentHTML('beforeend', newCommentHtml);

                        // Vyčistenie poľa
                        commentForm.querySelector("textarea").value = "";

                        // Aktualizácia počítadla
                        const countSpan = document.getElementById("comment-count");
                        if (countSpan) {
                            countSpan.innerText = parseInt(countSpan.innerText) + 1;
                        }
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

    // ============================
    // 4. MAZANIE KOMENTÁROV (AJAX)
    // ============================
    // Používame "Event Delegation", lebo komentáre môžu pribúdať dynamicky
    document.body.addEventListener("click", function (e) {

        // Zistíme, či bolo kliknuté na tlačidlo s triedou .btn-delete-comment
        if (e.target.classList.contains("btn-delete-comment")) {
            if (!confirm("Naozaj zmazať tento komentár?")) return;

            const button = e.target;
            const commentId = button.getAttribute("data-id");
            const formData = new FormData();
            formData.append("id", commentId);

            fetch("?c=comments&a=delete", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Odstránime HTML element komentára z DOM
                        const commentElement = document.getElementById("comment-" + commentId);
                        if (commentElement) {
                            commentElement.remove();
                        }

                        // Znížime počítadlo
                        const countSpan = document.getElementById("comment-count");
                        if (countSpan) {
                            countSpan.innerText = Math.max(0, parseInt(countSpan.innerText) - 1);
                        }
                    } else {
                        alert("Chyba: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Delete Error:", error);
                    alert("Nastala chyba pri mazaní.");
                });
        }
    });
});