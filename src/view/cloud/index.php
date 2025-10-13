<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel CloudZ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <style>
        .upload-box {
            border: 2px dashed #2b579a;
            border-radius: 12px;
            width: 320px;
            height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: 0.3s;
        }

        .upload-box:hover {
            background-color: #f8f3ff;
        }

        .upload-box input[type="file"] {
            display: none;
        }

        .upload-icon {
            background-color: #2b579a52;
            border-radius: 50%;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .upload-icon svg {
            width: 28px;
            height: 28px;
            stroke: #2b579a;
            stroke-width: 2;
        }

        .upload-text {
            color: #2b579a;
            font-size: 16px;
            font-weight: 500;
        }

        .file-card {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 0.6rem 0.8rem;
        }

        .file-icon {
            font-size: 2rem;
            color: #2b579a;
        }

        .file-info {
            flex-grow: 1;
        }

        .file-name {
            font-weight: 500;
            margin: 0;
        }

        .file-size {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .file-actions {
            font-size: 0.9rem;
            margin-top: 0.4rem;
            color: #0d6efd;
        }

        .file-actions a {
            text-decoration: none;
            color: #0d6efd;
            margin-right: 6px;
        }

        .file-actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <nav class="navbar bg-dark border-body" data-bs-theme="dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="/cloudz-panel/assets/icons/thunderstorm_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg" alt="Bootstrap" width="50" height="60">
                CloudZ
            </a>
        </div>
    </nav>

    <div class="container mt-5">
        <form action="<?= PATH_CONTROLLER . "CloudController.php"; ?>">
            <div id="div-form" class="d-flex">
                <div id="div-account" class="w-50">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#aws-s3-tab-pane" type="button" role="tab" aria-controls="aws-s3-tab-pane" aria-selected="true">AWS S3</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#ftp-tab-pane" type="button" role="tab" aria-controls="ftp-tab-pane" aria-selected="false">FTP</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#google-drive-tab-panel" type="button" role="tab" aria-controls="google-drive-tab-panel" aria-selected="false">Google Drive</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active ms-2" id="aws-s3-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                            <div class="mb-3">
                                <label for="input-key" class="form-label">Key</label>
                                <input type="text" class="form-control" id="input-key" name="aws-s3-key" placeholder="(Obrigatório)">
                            </div>
                            <div class="mb-3">
                                <label for="input-secret-key" class="form-label">Secret Key</label>
                                <input type="text" class="form-control" id="input-secret-key" name="aws-s3-secret-key" placeholder="(Obrigatório)">
                            </div>
                            <div class="mb-3">
                                <label for="input-region" class="form-label">Região</label>
                                <input type="text" class="form-control" id="input-region" name="aws-s3-region" placeholder="(Obrigatório)">
                            </div>
                            <div class="mb-3">
                                <label for="input-bucket-name" class="form-label">Nome do Bucket</label>
                                <input type="text" class="form-control" id="input-bucket-name" name="aws-s3-bucket-name" placeholder="(Obrigatório)">
                            </div>

                            <input type="text" class="form-control d-none" id="input-type" name="service-type" value="s3">
                        </div>
                        <div class="tab-pane fade ms-2" id="ftp-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                            <div class="mb-3">
                                <label for="input-host" class="form-label">Host</label>
                                <input type="text" class="form-control" id="input-host" name="host" placeholder="(Obrigatório)">
                            </div>
                            <div class="mb-3">
                                <label for="input-password" class="form-label">Senha</label>
                                <input type="text" class="form-control" id="input-password" name="password" placeholder="(Obrigatório)">
                            </div>
                            <div class="mb-3">
                                <label for="input-port" class="form-label">Porta</label>
                                <input type="text" class="form-control" id="input-port" name="port" placeholder="(Obrigatório)">
                            </div>
                            <div class="d-flex">
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="input-passive" name="passive">
                                    <label class="form-check-label me-2" for="input-passive">É passivo?</label>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="input-use-ssh" name="use-ssh">
                                    <label class="form-check-label" for="input-use-ssh">Usa SSH?</label>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade ms-2" id="google-drive-tab-panel" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                            <div class="mb-3">
                                <label for="input-client-id" class="form-label">Id</label>
                                <input type="text" class="form-control" id="input-client-id" name="client-id" placeholder="(Obrigatório)">
                            </div>
                            <div class="mb-3">
                                <label for="input-client-secret" class="form-label">Secret</label>
                                <input type="text" class="form-control" id="input-client-secret" name="client-secret" placeholder="(Obrigatório)">
                            </div>
                            <div class="mb-3">
                                <label for="input-refresh-token" class="form-label">Refresh Token</label>
                                <input type="text" class="form-control" id="input-refresh-token" name="refresh-token" placeholder="(Obrigatório)">
                            </div>
                            <div class="mb-3">
                                <label for="input-folder-id" class="form-label">Id da Pasta</label>
                                <input type="text" class="form-control" id="input-folder-id" name="folder-id" placeholder="(Obrigatório)">
                            </div>

                            <input type="text" class="form-control d-none" id="input-type" name="service-type" value="GOOGLE-DRIVE">
                        </div>
                    </div>

                    <div id="div-path" class="mb-3">
                        <label for="input-bucket-name" class="form-label">Caminho na Nuvem</label>
                        <input type="text" class="form-control" id="input-path" name="path" placeholder="(Opcional)">
                    </div>
                    <div id="div-utility-path" class="mb-3 d-none">
                        <label for="input-root" class="form-label">Raiz</label>
                        <input type="text" class="form-control" id="input-root" name="root">
                        <label for="input-solution-name" class="form-label">Nome da solução</label>
                        <input type="text" class="form-control" id="input-solution-name" name="solution-name">
                        <label for="input-module" class="form-label">Modulo</label>
                        <input type="text" class="form-control" id="input-module" name="module">
                    </div>

                    <div class="d-flex">
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="checkbox-utility-path" name="utility-path">
                            <label class="form-check-label me-2" for="checkbox-utility-path">Usar uitilitário de caminho</label>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="input-encrypt-name" name="encrypt-name">
                            <label class="form-check-label me-2" for="input-encrypt-name">Encriptar nome arquivo</label>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="input-delete-after-upload" name="delete-after-upload">
                            <label class="form-check-label me-2" for="input-delete-after-upload">Deletar arquivo local</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
                <div id="attachments" class="w-50 mh-75 ms-5" style="border: 1px solid #ddd; border-radius: 12px; height: 34vw;">
                    <label class="upload-box w-100">
                        <div class="upload-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0l-4 4m4-4l4 4M4 16v4h16v-4" />
                            </svg>
                        </div>
                        <span class="upload-text">Upload File</span>
                        <input type="file" name="attachments" id="input-attachments" multiple>
                    </label>
                    <div id="attachments-view" class="mt-3">
                        <div class="file-card w-100">
                            <img src="/cloudz-panel/assets/icons/thunderstorm_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg" style="width: 10%;">
                            <div class="file-info">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="file-name mb-0">Reunião Anual de Vendas</p>
                                    </div>
                                </div>
                                <div class="file-actions">
                                    <a href="#">Baixar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
    <script src="/cloudz-panel/assets/js/app.js"></script>
</body>

</html>