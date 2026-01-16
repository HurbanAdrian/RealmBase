<?php
/** @var \Framework\Core\App $this */
$link = $this->app->getLinkGenerator();
?>

<div class="container mt-4">
    <div class="web-content p-5 shadow-sm rounded border border-secondary bg-dark text-white">
        <div class="text-center mb-5">
            <h1 class="display-4 border-bottom d-inline-block pb-2 border-warning text-warning">Kontakt</h1>
            <p class="text-muted mt-3">Máte otázky alebo pripomienky k portálu RealmBase? Sme tu pre vás.</p>
        </div>

        <div class="row justify-content-center g-4">
            <div class="col-md-5">
                <div class="d-flex align-items-start mb-4">
                    <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                        <i class="bi bi-geo-alt-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 text-warning">Adresa</h5>
                        <p class="mb-0">Univerzitná 8215/1</p>
                        <p class="text-muted">010 26 Žilina, Slovensko</p>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-4">
                    <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                        <i class="bi bi-envelope-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 text-warning">E-mail</h5>
                        <p class="mb-0">
                            <a href="mailto:info@realmbase.sk" class="text-decoration-none text-white">info@realmbase.sk</a>
                        </p>
                        <p class="text-muted small">Odpovedáme do 24 hodín</p>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-4">
                    <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                        <i class="bi bi-clock-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 text-warning">Dostupnosť</h5>
                        <p class="mb-0">Pondelok - Piatok</p>
                        <p class="text-muted">09:00 - 16:00</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="shadow rounded overflow-hidden border border-secondary" style="height: 350px;">
                    <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2606.326372109676!2d18.75618457688005!3d49.20385967605991!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471459461d331905%3A0x7d6786689d71c60!2sFakulta%20riadenia%20a%20informatiky%20UNIZA!5e0!3m2!1ssk!2ssk!4v1700000000000!5m2!1ssk!2ssk"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>