<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$userId = getCurrentUserId();
$error = '';
$success = '';

$database = new Database();
$db = $database->connect();

$query = "SELECT * FROM company WHERE comID = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $userId);
$stmt->execute();
$company = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="mn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Файлууд — Оруулах</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Mongolian&family=IBM+Plex+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/upload.css">
</head>

<body>
    <div class="card">
        <div class="card-header">
            <div class="header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <line x1="12" y1="18" x2="12" y2="12" />
                    <line x1="9" y1="15" x2="15" y2="15" />
                </svg>
            </div>

            <h2><?= htmlspecialchars($company['comName'] ?? '') ?>, <?= htmlspecialchars($company['RD'] ?? '') ?></h2>
            <span>КОМПАНИЙН БУСАД НЭМЭЛТ МАТЕРИАЛ БАЙРШУУЛАХ</span>
        </div>

        <form id="mainForm" method="POST" enctype="multipart/form-data">
            <div class="grid">

                <!-- Өөрийн зураг -->
                <div class="field-group" id="group-avatar">
                    <label class="field-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                        </svg>
                        Өөрийн зураг
                    </label>
                    <div class="drop-zone" id="dz-avatar" ondragover="handleDragOver(event, 'avatar')" ondragleave="handleDragLeave('avatar')" ondrop="handleDrop(event, 'avatar')">
                        <input type="file" name="avatar" id="file-avatar" accept="image/*" onchange="handleFileChange(this, 'avatar')">
                        <div class="drop-icon" id="icon-avatar">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <rect x="3" y="3" width="18" height="18" rx="3" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" />
                            </svg>
                        </div>
                        <div class="drop-text">
                            <div class="main-text" id="label-avatar">Файл сонгох эсвэл чирж тавих</div>
                            <div class="sub-text">PNG, JPG, GIF — 5MB хүртэл</div>
                        </div>
                    </div>
                    <div class="progress-wrap" id="prog-avatar">
                        <div class="progress-header">
                            <span class="progress-label">Байршуулж байна...</span>
                            <span class="progress-pct" id="pct-avatar">0%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-bar" id="bar-avatar"></div>
                        </div>
                    </div>
                    <div class="preview-area" id="prev-avatar">
                        <div class="preview-img-wrap">
                            <img id="img-avatar" src="" alt="Өөрийн зураг">
                            <span class="preview-badge" id="size-avatar"></span>
                        </div>
                        <div class="upload-status" id="status-avatar"></div>
                        <button type="button" class="btn-remove" id="rm-avatar" onclick="removeFile('avatar')">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                            Арилгах
                        </button>
                    </div>
                </div>

                <!-- Компанийн лого -->
                <div class="field-group" id="group-logo">
                    <label class="field-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="7" width="20" height="14" rx="2" />
                            <path d="M16 7V5a2 2 0 0 0-4 0v2" />
                        </svg>
                        Компанийн лого
                        <span class="required">*</span>
                    </label>
                    <div class="drop-zone" id="dz-logo" ondragover="handleDragOver(event, 'logo')" ondragleave="handleDragLeave('logo')" ondrop="handleDrop(event, 'logo')">
                        <input type="file" name="logo" id="file-logo" accept="image/*" onchange="handleFileChange(this, 'logo')" required>
                        <div class="drop-icon" id="icon-logo">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <rect x="3" y="3" width="18" height="18" rx="3" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" />
                            </svg>
                        </div>
                        <div class="drop-text">
                            <div class="main-text" id="label-logo">Файл сонгох эсвэл чирж тавих</div>
                            <div class="sub-text">PNG, JPG, SVG — 5MB хүртэл</div>
                        </div>
                    </div>
                    <div class="progress-wrap" id="prog-logo">
                        <div class="progress-header">
                            <span class="progress-label">Байршуулж байна...</span>
                            <span class="progress-pct" id="pct-logo">0%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-bar" id="bar-logo"></div>
                        </div>
                    </div>
                    <div class="preview-area" id="prev-logo">
                        <div class="preview-img-wrap">
                            <img id="img-logo" src="" alt="Компанийн лого">
                            <span class="preview-badge" id="size-logo"></span>
                        </div>
                        <div class="upload-status" id="status-logo"></div>
                        <button type="button" class="btn-remove" id="rm-logo" onclick="removeFile('logo')">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                            Арилгах
                        </button>
                    </div>
                </div>

                <!-- Гарын үсэг -->
                <div class="field-group" id="group-signature">
                    <label class="field-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z" />
                        </svg>
                        Гарын үсэг
                        <span class="required">*</span>
                    </label>
                    <div class="drop-zone" id="dz-signature" ondragover="handleDragOver(event, 'signature')" ondragleave="handleDragLeave('signature')" ondrop="handleDrop(event, 'signature')">
                        <input type="file" name="signature" id="file-signature" accept="image/*" onchange="handleFileChange(this, 'signature')" required>
                        <div class="drop-icon" id="icon-signature">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <rect x="3" y="3" width="18" height="18" rx="3" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" />
                            </svg>
                        </div>
                        <div class="drop-text">
                            <div class="main-text" id="label-signature">Файл сонгох эсвэл чирж тавих</div>
                            <div class="sub-text">PNG, JPG — 5MB хүртэл</div>
                        </div>
                    </div>
                    <div class="progress-wrap" id="prog-signature">
                        <div class="progress-header">
                            <span class="progress-label">Байршуулж байна...</span>
                            <span class="progress-pct" id="pct-signature">0%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-bar" id="bar-signature"></div>
                        </div>
                    </div>
                    <div class="preview-area" id="prev-signature">
                        <div class="preview-img-wrap">
                            <img id="img-signature" src="" alt="Гарын үсэг">
                            <span class="preview-badge" id="size-signature"></span>
                        </div>
                        <div class="upload-status" id="status-signature"></div>
                        <button type="button" class="btn-remove" id="rm-signature" onclick="removeFile('signature')">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                            Арилгах
                        </button>
                    </div>
                </div>

                <!-- Тамга -->
                <div class="field-group" id="group-stamp">
                    <label class="field-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                        Тамга
                        <span class="required">*</span>
                    </label>
                    <div class="drop-zone" id="dz-stamp" ondragover="handleDragOver(event, 'stamp')" ondragleave="handleDragLeave('stamp')" ondrop="handleDrop(event, 'stamp')">
                        <input type="file" name="stamp" id="file-stamp" accept="image/*" onchange="handleFileChange(this, 'stamp')" required>
                        <div class="drop-icon" id="icon-stamp">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <rect x="3" y="3" width="18" height="18" rx="3" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" />
                            </svg>
                        </div>
                        <div class="drop-text">
                            <div class="main-text" id="label-stamp">Файл сонгох эсвэл чирж тавих</div>
                            <div class="sub-text">PNG, JPG — 5MB хүртэл</div>
                        </div>
                    </div>
                    <div class="progress-wrap" id="prog-stamp">
                        <div class="progress-header">
                            <span class="progress-label">Байршуулж байна...</span>
                            <span class="progress-pct" id="pct-stamp">0%</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-bar" id="bar-stamp"></div>
                        </div>
                    </div>
                    <div class="preview-area" id="prev-stamp">
                        <div class="preview-img-wrap">
                            <img id="img-stamp" src="" alt="Тамга">
                            <span class="preview-badge" id="size-stamp"></span>
                        </div>
                        <div class="upload-status" id="status-stamp"></div>
                        <button type="button" class="btn-remove" id="rm-stamp" onclick="removeFile('stamp')">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                            Арилгах
                        </button>
                    </div>
                </div>

            </div><!-- .grid -->

            <div class="form-footer">
                <button type="button" class="btn btn-secondary">Буцах</button>
                <button type="submit" class="btn btn-primary">Хадгалах</button>
            </div>
        </form>
    </div>

    <script>
        const USER_ID = <?= (int)($userId ?? 0) ?>;
        // ─── Drag & Drop ──────────────────────────────────────────
        function handleDragOver(e, id) {
            e.preventDefault();
            document.getElementById('dz-' + id).classList.add('dragover');
        }

        function handleDragLeave(id) {
            document.getElementById('dz-' + id).classList.remove('dragover');
        }

        function handleDrop(e, id) {
            e.preventDefault();
            handleDragLeave(id);
            const dt = e.dataTransfer;
            if (dt.files.length) {
                const input = document.getElementById('file-' + id);
                // Create a new DataTransfer to assign dropped file
                const transfer = new DataTransfer();
                transfer.items.add(dt.files[0]);
                input.files = transfer.files;
                handleFileChange(input, id);
            }
        }

        // ─── Format bytes ─────────────────────────────────────────
        function formatBytes(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
        }

        // ─── File selected → preview + AJAX upload ────────────────
        function handleFileChange(input, id) {
            const file = input.files[0];
            if (!file) return;

            // Validate type client-side
            if (!file.type.startsWith('image/')) {
                alert('Зөвхөн зураг файл оруулна уу.');
                return;
            }

            // Show filename in drop zone
            const dz = document.getElementById('dz-' + id);
            dz.classList.add('has-file');
            document.getElementById('label-' + id).textContent = file.name;

            // Show preview immediately via FileReader
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('img-' + id).src = e.target.result;
                document.getElementById('size-' + id).textContent = formatBytes(file.size);
                const prevArea = document.getElementById('prev-' + id);
                prevArea.classList.add('visible');
                document.getElementById('rm-' + id).classList.add('visible');
            };
            reader.readAsDataURL(file);

            // AJAX upload with progress
            uploadFile(file, id);
        }

        // ─── AJAX Upload ──────────────────────────────────────────
        // ─── AJAX Upload — засварласан хувилбар ──────────────────
        function uploadFile(file, id) {
            const progWrap = document.getElementById('prog-' + id);
            const bar = document.getElementById('bar-' + id);
            const pct = document.getElementById('pct-' + id);
            const status = document.getElementById('status-' + id);

            progWrap.classList.add('visible');
            bar.style.width = '0%';
            pct.textContent = '0%';
            status.textContent = '';
            status.className = 'upload-status';

            const formData = new FormData();
            formData.append('file', file);
            formData.append('field', id);
            formData.append('userId', USER_ID); // ← PHP-аас дамжуулсан утга (доорыг үз)

            const xhr = new XMLHttpRequest();

            // ✅ ЗАСВАР: тусдаа handler файл руу илгээнэ
            xhr.open('POST', 'upload.php');

            // AJAX хүсэлт гэдгийг мэдэгдэх header
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    bar.style.width = percent + '%';
                    pct.textContent = percent + '%';
                }
            });

            xhr.addEventListener('load', function() {
                try {
                    const res = JSON.parse(xhr.responseText);
                    if (res.success) {
                        bar.style.width = '100%';
                        pct.textContent = '100%';
                        status.textContent = '✓ Амжилттай байршлаа — ' + res.name;
                        status.classList.add('success');
                        setTimeout(() => {
                            progWrap.classList.remove('visible');
                        }, 2200);
                    } else {
                        showUploadError(id, res.message || 'Алдаа гарлаа.');
                    }
                } catch {
                    // JSON биш хариу ирвэл (HTML эсвэл PHP алдаа) консол дээр харуулна
                    console.error('Серверийн хариу:', xhr.responseText);
                    showUploadError(id, 'Серверийн хариу буруу байна. Консол шалгана уу.');
                }
            });

            xhr.addEventListener('error', function() {
                showUploadError(id, 'Холболтын алдаа. Дахин оролдоно уу.');
            });

            xhr.send(formData);
        }

        function showUploadError(id, msg) {
            const progWrap = document.getElementById('prog-' + id);
            const status = document.getElementById('status-' + id);
            const bar = document.getElementById('bar-' + id);
            bar.style.background = '#e74c3c';
            status.textContent = '✕ ' + msg;
            status.classList.add('error');
            setTimeout(() => {
                progWrap.classList.remove('visible');
            }, 3000);
        }

        // ─── Remove file ──────────────────────────────────────────
        function removeFile(id) {
            document.getElementById('file-' + id).value = '';
            document.getElementById('img-' + id).src = '';
            document.getElementById('dz-' + id).classList.remove('has-file');
            document.getElementById('label-' + id).textContent = 'Файл сонгох эсвэл чирж тавих';
            document.getElementById('prev-' + id).classList.remove('visible');
            document.getElementById('prog-' + id).classList.remove('visible');
            document.getElementById('rm-' + id).classList.remove('visible');
            document.getElementById('status-' + id).textContent = '';
            document.getElementById('bar-' + id).style.width = '0%';
            document.getElementById('bar-' + id).style.background = '';
        }
    </script>
</body>

</html>