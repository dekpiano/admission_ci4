<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('styles') ?>
<style>
    /* High contrast inputs */
    .form-control, .form-select, textarea.form-control {
        background-color: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        color: #0f172a !important;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .form-control:focus, .form-select:focus, textarea.form-control:focus {
        border-color: var(--skj-pink, #ff6b8b) !important;
        box-shadow: 0 0 0 3.5px rgba(255, 107, 139, 0.18) !important;
        background-color: #ffffff !important;
    }

    .telegram-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
    }

    .step-badge {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .instruction-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        background: #ffffff;
        transition: all 0.2s ease;
    }
    .instruction-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                <i class="bx bxl-telegram fs-3 text-info"></i>
                จัดการการแจ้งเตือน Telegram (Telegram Notify)
            </h4>
            <p class="text-muted mb-0 small">กำหนดค่า Telegram Bot Token และ Chat ID เพื่อรับการแจ้งเตือนเมื่อมีนักเรียนสมัครใหม่</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- ==================== LEFT COLUMN: SETTINGS & TEST ==================== -->
        <div class="col-lg-7">
            
            <!-- Card 1: Configuration Form -->
            <div class="card border-0 rounded-4 shadow-sm bg-white mb-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="telegram-icon-box" style="background: rgba(2, 132, 199, 0.12); color: #0284c7;">
                            <i class="bx bx-cog"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">ตั้งค่า Telegram Bot</h5>
                            <small class="text-muted">ข้อมูลการเชื่อมต่อ Telegram API</small>
                        </div>
                    </div>
                    <span class="badge bg-label-info rounded-pill px-3 py-1 fw-bold">Telegram API</span>
                </div>
                <div class="card-body p-4">
                    <form id="telegramConfigForm">
                        <?= csrf_field() ?>

                        <!-- Bot Token Input -->
                        <div class="mb-3">
                            <label for="telegram_bot_token" class="form-label fw-bold text-dark small mb-1">
                                Telegram Bot Token <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bx bx-bot"></i></span>
                                <input type="text" class="form-control rounded-end-3 font-monospace border-start-0" 
                                    id="telegram_bot_token" name="telegram_bot_token" 
                                    value="<?= esc($config->telegram_bot_token ?? '') ?>" 
                                    placeholder="เช่น 123456789:ABCdefGhIJKlmNoPQRsTUVwxyZ" required>
                            </div>
                            <small class="text-muted d-block mt-1">
                                <i class="bx bx-info-circle me-1"></i> ได้รับจาก <code>@BotFather</code> ใน Telegram เมื่อสร้าง Bot
                            </small>
                        </div>

                        <!-- Chat ID Input -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label for="telegram_chat_id" class="form-label fw-bold text-dark small m-0">
                                    Chat ID หรือ Group ID <span class="text-danger">*</span>
                                </label>
                                <button type="button" class="btn btn-xs btn-outline-info rounded-pill px-3 py-1 shadow-sm" id="btnDetectChat" onclick="autoDetectChatId()">
                                    <i class="bx bx-search-alt me-1"></i> ตรวจหา Chat ID อัตโนมัติ
                                </button>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bx bx-chat"></i></span>
                                <input type="text" class="form-control rounded-end-3 font-monospace border-start-0" 
                                    id="telegram_chat_id" name="telegram_chat_id" 
                                    value="<?= esc($config->telegram_chat_id ?? '') ?>" 
                                    placeholder="เช่น -100123456789 (กลุ่ม) หรือ 123456789 (ส่วนตัว)" required>
                            </div>
                            <small class="text-muted d-block mt-1">
                                <i class="bx bx-info-circle me-1"></i> ไอดีของกลุ่มหรือผู้รับ (หากเป็นกลุ่ม Supergroup จะขึ้นต้นด้วย <code>-100</code>)
                            </small>
                        </div>

                        <!-- Switch: Main System Status -->
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-3" style="border: 1.5px solid #e2e8f0; background: #ffffff;">
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">เปิดใช้งานการแจ้งเตือน Telegram</h6>
                                <small class="text-muted">ส่งข้อความแจ้งเตือนผ่าน Telegram อัตโนมัติ</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input type="hidden" name="telegram_status" value="off">
                                <input class="form-check-input" type="checkbox" id="telegram_status" name="telegram_status" value="on" 
                                    <?= ($config->telegram_status ?? 'on') === 'on' ? 'checked' : '' ?> 
                                    style="width: 3rem; height: 1.5rem; cursor: pointer;">
                            </div>
                        </div>

                        <!-- Switch: New Applicant Notification -->
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-4" style="border: 1.5px solid #e2e8f0; background: #ffffff;">
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">แจ้งเตือนเมื่อมีผู้สมัครใหม่</h6>
                                <small class="text-muted">ส่งรายละเอียดนักเรียนที่สมัครเรียนออนไลน์เข้ามาในระบบทันที</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input type="hidden" name="telegram_notify_new_applicant" value="off">
                                <input class="form-check-input" type="checkbox" id="telegram_notify_new_applicant" name="telegram_notify_new_applicant" value="on" 
                                    <?= ($config->telegram_notify_new_applicant ?? 'on') === 'on' ? 'checked' : '' ?> 
                                    style="width: 3rem; height: 1.5rem; cursor: pointer;">
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-primary rounded-pill px-5 shadow-sm fw-bold" onclick="saveTelegramConfig()">
                                <i class="bx bx-save me-1"></i> บันทึกการตั้งค่า
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card 2: Test Message Sandbox -->
            <div class="card border-0 rounded-4 shadow-sm bg-white mb-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center gap-2">
                    <div class="telegram-icon-box" style="background: rgba(225, 29, 72, 0.12); color: #e11d48;">
                        <i class="bx bx-send"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">ทดสอบส่งข้อความ (Test Message)</h5>
                        <small class="text-muted">ส่งข้อความทดสอบไปยัง Telegram ทันที</small>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label for="test_message" class="form-label fw-bold text-dark small mb-1">ข้อความทดสอบ</label>
                        <textarea class="form-control rounded-3" id="test_message" rows="3" placeholder="ระบุข้อความที่ต้องการทดสอบส่ง..."></textarea>
                    </div>
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <small class="text-muted">
                            <i class="bx bx-info-circle me-1"></i> ระบบจะส่งข้อความไปยัง Bot Token และ Chat ID ที่ระบุด้านบน
                        </small>
                        <button type="button" class="btn btn-outline-primary rounded-pill px-4 shadow-sm" id="btnTestSend" onclick="sendTestMessage()">
                            <i class="bx bx-paper-plane me-1"></i> ส่งข้อความทดสอบ
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 3: Two-Way Webhook (Live Chat) -->
            <div class="card border-0 rounded-4 shadow-sm bg-white mb-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="telegram-icon-box" style="background: rgba(14, 165, 233, 0.12); color: #0ea5e9;">
                            <i class="bx bx-transfer-alt"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark">ตั้งค่า Telegram Webhook (แชทโต้ตอบ 2 ทาง)</h5>
                            <small class="text-muted">ให้ครู Reply ใน Telegram แล้วข้อความเด้งกลับหน้าเว็บนักเรียนทันที</small>
                        </div>
                    </div>
                    <span class="badge bg-label-primary rounded-pill px-3 py-1">2-Way Live Chat</span>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small mb-1">Webhook URL ปลายทาง</label>
                        <div class="input-group">
                            <input type="text" class="form-control font-monospace bg-light" id="webhook_url_input" value="<?= site_url('api/telegram/webhook') ?>" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="navigator.clipboard.writeText(document.getElementById('webhook_url_input').value); Swal.fire({toast:true,position:'top-end',icon:'success',title:'คัดลอก URL แล้ว',showConfirmButton:false,timer:1500});">
                                <i class="bx bx-copy"></i>
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1">
                            <i class="bx bx-shield-quarter me-1 text-success"></i> ระบบจะเชื่อมโยง Telegram Bot เข้ากับ URL นี้แบบอัตโนมัติ (จำเป็นต้องเป็น HTTPS เมื่อขึ้นเซิร์ฟเวอร์จริง)
                        </small>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm btn-sm" id="btnSetWebhook" onclick="setTelegramWebhook()">
                            <i class="bx bx-link me-1"></i> ผูก Webhook อัตโนมัติ
                        </button>
                        <button type="button" class="btn btn-outline-info rounded-pill px-3 shadow-sm btn-sm" id="btnCheckWebhook" onclick="checkWebhookInfo()">
                            <i class="bx bx-info-circle me-1"></i> ตรวจสอบสถานะ Webhook
                        </button>
                        <button type="button" class="btn btn-outline-danger rounded-pill px-3 shadow-sm btn-sm" id="btnDeleteWebhook" onclick="deleteTelegramWebhook()">
                            <i class="bx bx-trash me-1"></i> ยกเลิก Webhook
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <!-- ==================== RIGHT COLUMN: INSTRUCTIONS ==================== -->
        <div class="col-lg-5">
            <div class="card border-0 rounded-4 shadow-sm bg-white mb-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center gap-2">
                    <div class="telegram-icon-box" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                        <i class="bx bx-help-circle"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">ขั้นตอนการเชื่อมต่อ Telegram</h5>
                        <small class="text-muted">วิธีสร้าง Bot และหาค่า Chat ID ใน 4 ขั้นตอน</small>
                    </div>
                </div>
                <div class="card-body p-4">
                    
                    <!-- Step 1 -->
                    <div class="instruction-card mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="step-badge bg-primary text-white">1</span>
                            <h6 class="fw-bold text-dark mb-0">สร้าง Bot ด้วย @BotFather</h6>
                        </div>
                        <p class="small text-muted mb-2">
                            ค้นหา <code>@BotFather</code> ใน Telegram แล้วพิมพ์ <code>/newbot</code> เพื่อตั้งชื่อบอท
                        </p>
                        <span class="badge bg-label-info rounded-pill px-2 py-1">คัดลอก HTTP API Token มาใส่ในช่อง Bot Token</span>
                    </div>

                    <!-- Step 2 -->
                    <div class="instruction-card mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="step-badge bg-info text-white">2</span>
                            <h6 class="fw-bold text-dark mb-0">สร้างกลุ่ม Admin & ดึง Bot เข้ากลุ่ม</h6>
                        </div>
                        <p class="small text-muted mb-0">
                            สร้างกลุ่มใน Telegram สำหรับรับแจ้งเตือน แล้วเชิญ Bot ที่สร้างขึ้นเข้ามาเป็นสมาชิกกลุ่ม พร้อมตั้งค่าสิทธิ์เป็น <strong>Administrator</strong>
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="instruction-card mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="step-badge bg-warning text-white">3</span>
                            <h6 class="fw-bold text-dark mb-0">ค้นหา Chat ID ของกลุ่ม</h6>
                        </div>
                        <p class="small text-muted mb-2">
                            เชิญบอท <code>@userinfobot</code> หรือ <code>@RawDataBot</code> เข้ากลุ่มเพื่อดูไอดีกลุ่ม (จะขึ้นต้นด้วย <code>-100...</code>)
                        </p>
                        <span class="badge bg-label-primary rounded-pill px-2 py-1">นำค่า ID มาใส่ในช่อง Chat ID</span>
                    </div>

                    <!-- Step 4 -->
                    <div class="instruction-card">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="step-badge bg-success text-white">4</span>
                            <h6 class="fw-bold text-dark mb-0">กดทดสอบส่งข้อความ</h6>
                        </div>
                        <p class="small text-muted mb-0">
                            กดปุ่ม <strong>"ส่งข้อความทดสอบ"</strong> ในการ์ดฝั่งซ้าย หากตั้งค่าถูกต้อง ข้อความแจ้งเตือนจะเด้งเข้ากลุ่ม Telegram ทันที 🎉
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function saveTelegramConfig() {
        const formData = new FormData(document.getElementById('telegramConfigForm'));

        fetch('<?= site_url('skjadmin/telegram-notify/update') ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: 'success',
                    title: data.message
                });
            } else {
                Swal.fire('ข้อผิดพลาด', data.message || 'ไม่สามารถบันทึกข้อมูลได้', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์', 'error');
        });
    }

    function sendTestMessage() {
        const botToken = document.getElementById('telegram_bot_token').value;
        const chatId = document.getElementById('telegram_chat_id').value;
        const testMessage = document.getElementById('test_message').value;

        if (!botToken || !chatId) {
            Swal.fire('คำเตือน', 'กรุณาระบุ Bot Token และ Chat ID ก่อนทดสอบ', 'warning');
            return;
        }

        const btn = document.getElementById('btnTestSend');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> กำลังส่ง...';

        const params = new URLSearchParams();
        params.append('telegram_bot_token', botToken);
        params.append('telegram_chat_id', chatId);
        params.append('test_message', testMessage);

        fetch('<?= site_url('skjadmin/telegram-notify/test') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: params.toString()
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: data.message,
                    timer: 2500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    html: data.message
                });
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            console.error('Error:', error);
            Swal.fire('ข้อผิดพลาด', 'เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
        });
    }

    function autoDetectChatId() {
        const botToken = document.getElementById('telegram_bot_token').value;
        if (!botToken) {
            Swal.fire('คำเตือน', 'กรุณากรอก Telegram Bot Token ก่อนทำการตรวจหา Chat ID', 'warning');
            return;
        }

        const btn = document.getElementById('btnDetectChat');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> กำลังค้นหา...';

        const params = new URLSearchParams();
        params.append('telegram_bot_token', botToken);

        fetch('<?= site_url('skjadmin/telegram-notify/detect-chat') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: params.toString()
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;

            if (data.status === 'success' && data.chats && data.chats.length > 0) {
                if (data.chats.length === 1) {
                    const c = data.chats[0];
                    document.getElementById('telegram_chat_id').value = c.id;
                    Swal.fire({
                        icon: 'success',
                        title: 'พบห้องแชท!',
                        html: `<b>ชื่อห้อง:</b> ${c.title}<br><b>Chat ID:</b> <code>${c.id}</code><br><br>ระบบใส่ค่าให้อัตโนมัติเรียบร้อยแล้ว`,
                        confirmButtonText: 'ตกลง'
                    });
                } else {
                    let htmlList = '<p class="text-muted small">เลือกห้องแชทที่คุณต้องการใช้รับแจ้งเตือน:</p><div class="list-group text-start">';
                    data.chats.forEach(c => {
                        htmlList += `
                            <button type="button" class="list-group-item list-group-item-action p-3" onclick="selectChatId('${c.id}', '${c.title}')">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <h6 class="mb-1 fw-bold text-dark"><i class="bx bx-group text-primary me-1"></i> ${c.title}</h6>
                                    <span class="badge bg-label-info rounded-pill">${c.type}</span>
                                </div>
                                <code class="small text-primary">${c.id}</code>
                            </button>
                        `;
                    });
                    htmlList += '</div>';

                    Swal.fire({
                        title: 'เลือกห้องแชท Telegram',
                        html: htmlList,
                        showConfirmButton: false,
                        showCloseButton: true
                    });
                }
            } else if (data.status === 'empty') {
                Swal.fire({
                    icon: 'info',
                    title: 'ยังไม่พบห้องแชท',
                    html: data.message
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    html: data.message
                });
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            console.error('Error:', error);
            Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
        });
    }

    function selectChatId(chatId, title) {
        document.getElementById('telegram_chat_id').value = chatId;
        Swal.close();
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
        Toast.fire({
            icon: 'success',
            title: 'เลือก ' + title + ' (' + chatId + ')'
        });
    }

    function setTelegramWebhook() {
        const url = document.getElementById('webhook_url_input').value;
        const btn = document.getElementById('btnSetWebhook');
        const orig = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> กำลังผูก Webhook...';

        const formData = new FormData();
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        formData.append('webhook_url', url);

        fetch('<?= site_url('skjadmin/telegram-notify/set-webhook') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = orig;
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: data.message
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'ตั้งค่าไม่สำเร็จ',
                    html: data.message
                });
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = orig;
            Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
        });
    }

    function checkWebhookInfo() {
        const btn = document.getElementById('btnCheckWebhook');
        const orig = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> ตรวจสอบ...';

        const formData = new FormData();
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= site_url('skjadmin/telegram-notify/get-webhook') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = orig;
            if (data.status === 'success') {
                const info = data.info || {};
                const hasUrl = info.url ? `<code class="text-primary">${info.url}</code>` : '<span class="text-danger">ยังไม่ได้ตั้งค่า Webhook</span>';
                const pending = info.pending_update_count ?? 0;
                const lastErr = info.last_error_message ? `<div class="alert alert-danger mt-2 small p-2">${info.last_error_message}</div>` : '';

                Swal.fire({
                    icon: info.url ? 'success' : 'info',
                    title: 'สถานะ Telegram Webhook',
                    html: `
                        <div class="text-start small">
                            <p class="mb-1"><b>URL:</b> ${hasUrl}</p>
                            <p class="mb-1"><b>ข้อความรอประมวลผล:</b> ${pending} รายการ</p>
                            <p class="mb-0"><b>การเชื่อมต่อ SSL:</b> ${info.has_custom_certificate ? 'Custom' : 'Standard HTTPS'}</p>
                            ${lastErr}
                        </div>
                    `
                });
            } else {
                Swal.fire('ข้อผิดพลาด', data.message, 'error');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = orig;
            Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
        });
    }

    function deleteTelegramWebhook() {
        Swal.fire({
            title: 'ยืนยันการยกเลิก Webhook?',
            text: 'เมื่อยกเลิก การ Reply ใน Telegram จะไม่ถูกส่งกลับมาที่หน้าเว็บ',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ยืนยันยกเลิก',
            cancelButtonText: 'ยกเลิก'
        }).then(res => {
            if (res.isConfirmed) {
                const formData = new FormData();
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                fetch('<?= site_url('skjadmin/telegram-notify/delete-webhook') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire('สำเร็จ', data.message, 'success');
                    } else {
                        Swal.fire('ข้อผิดพลาด', data.message, 'error');
                    }
                });
            }
        });
    }
</script>
<?= $this->endSection() ?>
