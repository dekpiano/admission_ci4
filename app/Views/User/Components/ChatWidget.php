<!-- ==================== SKJ ADMISSION LIVE CHAT WIDGET (MODERN LUXURY MESSENGER) ==================== -->
<style>
    :root {
        --skj-chat-pink: #ff6b8b;
        --skj-chat-blue: #56ccf2;
        --skj-chat-gradient: linear-gradient(135deg, #ff6b8b 0%, #56ccf2 100%);
        --skj-chat-user-bubble: linear-gradient(135deg, #ff6b8b 0%, #ff8ea7 100%);
    }

    /* Floating Chat Bubble Launcher */
    .skj-chat-launcher {
        position: fixed;
        bottom: 28px;
        right: 28px;
        z-index: 1040;
        width: 62px;
        height: 62px;
        border-radius: 50%;
        background: var(--skj-chat-gradient);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        box-shadow: 0 10px 28px rgba(255, 107, 139, 0.4), 0 4px 10px rgba(86, 204, 242, 0.25);
        cursor: pointer;
        transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        border: 2.5px solid rgba(255, 255, 255, 0.95);
        outline: none;
    }

    .skj-chat-launcher:hover {
        transform: scale(1.1) translateY(-3px);
        box-shadow: 0 14px 34px rgba(255, 107, 139, 0.55), 0 6px 14px rgba(86, 204, 242, 0.35);
        color: #ffffff;
    }

    .skj-chat-launcher:active {
        transform: scale(0.95);
    }

    /* Attention-grabbing greeting pill attached to launcher */
    .skj-chat-launcher-pill {
        position: absolute;
        right: 74px;
        top: 50%;
        transform: translateY(-50%);
        background: #ffffff;
        color: #0f172a;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 8px 16px;
        border-radius: 30px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
        border: 1px solid rgba(226, 232, 240, 0.9);
        white-space: nowrap;
        pointer-events: none;
        display: flex;
        align-items: center;
        gap: 8px;
        animation: floatPill 3s ease-in-out infinite;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    .skj-chat-launcher-pill::after {
        content: '';
        position: absolute;
        right: -6px;
        top: 50%;
        transform: translateY(-50%);
        border-width: 6px 0 6px 6px;
        border-style: solid;
        border-color: transparent transparent transparent #ffffff;
    }

    @keyframes floatPill {
        0%, 100% { transform: translateY(-50%) translateX(0); }
        50% { transform: translateY(-50%) translateX(-4px); }
    }

    .skj-chat-launcher .badge-unread {
        position: absolute;
        top: -4px;
        right: -4px;
        background: linear-gradient(135deg, #ff6b8b, #e04869);
        color: #ffffff;
        font-size: 11px;
        font-weight: 800;
        min-width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #ffffff;
        box-shadow: 0 3px 8px rgba(255, 107, 139, 0.5);
        animation: pulseBadge 1.5s infinite;
    }

    @keyframes pulseBadge {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.18); }
    }

    /* Floating Chat Window Box */
    .skj-chat-window {
        position: fixed;
        bottom: 102px;
        right: 28px;
        width: 390px;
        max-width: calc(100vw - 28px);
        height: 560px;
        max-height: calc(100dvh - 120px);
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 20px 50px -10px rgba(15, 23, 42, 0.25), 0 8px 20px -5px rgba(255, 107, 139, 0.18);
        border: 1px solid rgba(226, 232, 240, 0.95);
        z-index: 1045;
        display: none;
        flex-direction: column;
        overflow: hidden;
        animation: chatSlideUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        backdrop-filter: blur(10px);
    }

    @keyframes chatSlideUp {
        from { opacity: 0; transform: translateY(25px) scale(0.92); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Chat Header */
    .skj-chat-header {
        background: var(--skj-chat-gradient);
        color: #ffffff;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 15px rgba(255, 107, 139, 0.25);
        position: relative;
        overflow: hidden;
    }

    .skj-chat-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -30%;
        width: 160%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.22) 0%, transparent 60%);
        pointer-events: none;
    }

    .skj-chat-header .header-info {
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 1;
    }

    .skj-chat-header .header-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.25);
        border: 1.5px solid rgba(255, 255, 255, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        backdrop-filter: blur(6px);
        position: relative;
        flex-shrink: 0;
    }

    .skj-chat-header .header-avatar .online-radar {
        position: absolute;
        bottom: 0px;
        right: 0px;
        width: 11px;
        height: 11px;
        background-color: #22c55e;
        border: 2px solid #ffffff;
        border-radius: 50%;
    }

    .skj-chat-header .header-title {
        font-size: 0.98rem;
        font-weight: 700;
        line-height: 1.2;
        margin: 0;
        text-shadow: 0 1px 2px rgba(0,0,0,0.15);
    }

    .skj-chat-header .header-status {
        font-size: 0.74rem;
        opacity: 0.95;
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 2px;
    }

    .skj-chat-header .header-actions button {
        background: rgba(255, 255, 255, 0.25);
        border: none;
        color: #ffffff;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        backdrop-filter: blur(4px);
        z-index: 1;
    }

    .skj-chat-header .header-actions button:hover {
        background: rgba(255, 255, 255, 0.4);
        transform: rotate(90deg);
    }

    /* User Profile Setup Panel */
    .skj-chat-setup-panel {
        background: #f8fafc;
        padding: 10px 14px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.8rem;
    }
    .skj-chat-setup-panel .form-control {
        font-size: 0.8rem;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        padding: 4px 10px;
    }
    .skj-chat-setup-panel .form-control:focus {
        border-color: var(--skj-chat-pink);
        box-shadow: 0 0 0 2px rgba(255, 107, 139, 0.2);
    }

    /* Chat Messages Stream Area */
    .skj-chat-body {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        background-color: #f8fafc;
        background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
        background-size: 18px 18px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .skj-chat-body::-webkit-scrollbar {
        width: 4px;
    }
    .skj-chat-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    /* Welcome Banner in chat */
    .skj-chat-welcome {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 12px 14px;
        text-align: center;
        box-shadow: 0 3px 10px rgba(0,0,0,0.03);
        margin-bottom: 6px;
    }
    .skj-chat-welcome .welcome-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(255, 107, 139, 0.15), rgba(86, 204, 242, 0.15));
        color: #ff6b8b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 6px;
    }

    .skj-msg-row {
        display: flex;
        flex-direction: column;
        max-width: 82%;
    }

    .skj-msg-row.user {
        align-self: flex-end;
    }

    .skj-msg-row.admin {
        align-self: flex-start;
    }

    .skj-msg-row.system {
        align-self: center;
        max-width: 92%;
        text-align: center;
    }

    .skj-msg-bubble {
        padding: 10px 15px;
        border-radius: 18px;
        font-size: 0.9rem;
        line-height: 1.5;
        word-break: break-word;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
    }

    .skj-msg-bubble.user {
        background: var(--skj-chat-user-bubble);
        color: #ffffff;
        border-bottom-right-radius: 4px;
        box-shadow: 0 4px 14px rgba(255, 107, 139, 0.28);
    }

    .skj-msg-bubble.admin {
        background: #ffffff;
        color: #0f172a;
        border: 1px solid #e2e8f0;
        border-bottom-left-radius: 4px;
    }

    .skj-msg-bubble.system {
        background: #f0fdf4;
        color: #166534;
        font-size: 0.78rem;
        border-radius: 14px;
        border: 1px dashed #bbf7d0;
        padding: 6px 12px;
    }

    .skj-msg-time {
        font-size: 0.68rem;
        color: #94a3b8;
        margin-top: 3px;
    }

    .skj-msg-row.user .skj-msg-time {
        text-align: right;
    }

    /* Interactive Quick Question Chips */
    .skj-chat-quick-chips {
        display: flex;
        gap: 6px;
        overflow-x: auto;
        padding: 6px 12px 2px;
        background: #ffffff;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .skj-chat-quick-chips::-webkit-scrollbar {
        display: none;
    }

    .skj-quick-chip {
        font-size: 0.74rem;
        font-weight: 600;
        white-space: nowrap;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 4px 11px;
        cursor: pointer;
        color: #334155;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        flex-shrink: 0;
    }
    .skj-quick-chip:hover, .skj-quick-chip:active {
        background: #fff0f3;
        border-color: #ff8ea7;
        color: #e04869;
        transform: translateY(-1px);
    }

    /* Chat Footer & Input */
    .skj-chat-footer {
        padding: 10px 14px;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
    }

    .skj-chat-input-box {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border-radius: 30px;
        padding: 3px 5px 3px 15px;
        border: 1.5px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .skj-chat-input-box:focus-within {
        background: #ffffff;
        border-color: var(--skj-chat-pink);
        box-shadow: 0 0 0 3px rgba(255, 107, 139, 0.2);
    }

    .skj-chat-input-box input {
        flex: 1;
        border: none;
        background: transparent;
        font-size: 0.9rem;
        outline: none;
        color: #0f172a;
        padding: 6px 0;
        min-width: 0;
    }

    .skj-chat-input-box button {
        background: var(--skj-chat-user-bubble);
        border: none;
        color: #ffffff;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        flex-shrink: 0;
        box-shadow: 0 3px 8px rgba(255, 107, 139, 0.35);
    }

    .skj-chat-input-box button:hover {
        transform: scale(1.06);
        box-shadow: 0 4px 12px rgba(255, 107, 139, 0.5);
    }

    /* Mobile clearance above Universal Bottom Navigation Bar */
    @media (max-width: 768px) {
        .skj-chat-launcher {
            bottom: 86px; /* Clear Universal Bottom App Bar */
            right: 18px;
            width: 56px;
            height: 56px;
            font-size: 26px;
        }

        .skj-chat-launcher-pill {
            display: none; /* Hide floating text pill on mobile for clean UI */
        }

        .skj-chat-window {
            bottom: 148px;
            right: 12px;
            left: 12px;
            width: auto;
            max-width: none;
            height: min(490px, calc(100dvh - 170px));
            border-radius: 20px;
        }

        .skj-msg-row {
            max-width: 86%;
        }
    }
</style>

<!-- Floating Launcher Icon -->
<div class="skj-chat-launcher" id="skjChatLauncher" onclick="toggleChatWindow()" title="สอบถามข้อมูล / สนทนาสดกับเจ้าหน้าที่">
    <i class="bx bx-chat" id="chatLauncherIcon"></i>
    <span class="badge-unread" id="chatUnreadBadge" style="display: none;">0</span>
    <div class="skj-chat-launcher-pill" id="skjChatLauncherPill">
        <span>💬 สอบถามเจ้าหน้าที่สด</span>
    </div>
</div>

<!-- Floating Chat Window -->
<div class="skj-chat-window" id="skjChatWindow">
    <!-- Header -->
    <div class="skj-chat-header">
        <div class="header-info">
            <div class="header-avatar">
                <i class="bx bxs-user-voice"></i>
                <span class="online-radar" title="กำลังออนไลน์"></span>
            </div>
            <div>
                <h6 class="header-title">ศูนย์สนทนาสด SKJ</h6>
                <div class="header-status">
                    <i class="bx bx-check-circle text-white"></i> เจ้าหน้าที่พร้อมให้คำปรึกษา
                </div>
            </div>
        </div>
        <div class="header-actions">
            <button type="button" onclick="toggleChatWindow()" title="ปิดหน้าต่าง">
                <i class="bx bx-x fs-4"></i>
            </button>
        </div>
    </div>

    <!-- User Profile Form (Name & Tel) -->
    <div class="skj-chat-setup-panel" id="chatSetupPanel">
        <div class="d-flex align-items-center justify-content-between mb-1">
            <small class="text-muted fw-bold">
                <i class="bx bx-user me-1 text-primary"></i> ข้อมูลผู้ติดต่อ (เพื่อให้เจ้าหน้าที่ติดต่อกลับ)
            </small>
        </div>
        <div class="row g-1">
            <div class="col-7">
                <input type="text" class="form-control form-control-sm" id="chatInputName" placeholder="ชื่อ-นามสกุล..." maxlength="100" onchange="saveChatProfile()">
            </div>
            <div class="col-5">
                <input type="tel" class="form-control form-control-sm" id="chatInputTel" placeholder="เบอร์โทร..." maxlength="20" onchange="saveChatProfile()">
            </div>
        </div>
    </div>

    <!-- Messages Body -->
    <div class="skj-chat-body" id="skjChatBody">
        <!-- Welcome intro card -->
        <div class="skj-chat-welcome">
            <div class="welcome-icon">
                <i class="bx bx-conversation"></i>
            </div>
            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.88rem;">ยินดีต้อนรับสู่ระบบรับสมัครนักเรียน SKJ</h6>
            <p class="text-muted mb-0" style="font-size: 0.76rem; line-height: 1.4;">
                พิมพ์ข้อความสอบถามด้านล่าง หรือแตะหัวข้อคำถามยอดนิยมได้ทันที เจ้าหน้าที่จะตอบกลับโดยเร็วที่สุดครับ
            </p>
        </div>

        <div class="text-center p-2 text-muted small" id="chatLoadingIndicator">
            <div class="spinner-border spinner-border-sm text-primary mb-1"></div>
            <div>กำลังโหลดข้อความ...</div>
        </div>
    </div>

    <!-- Quick Question Chips -->
    <div class="skj-chat-quick-chips">
        <span class="skj-quick-chip" onclick="quickSendQuestion('เอกสารที่ต้องใช้ในการสมัครมีอะไรบ้างครับ?')">📄 เอกสารที่ใช้</span>
        <span class="skj-quick-chip" onclick="quickSendQuestion('สามารถตรวจสอบสถานะการสมัครได้ที่ไหนครับ?')">🔍 ตรวจสอบสถานะ</span>
        <span class="skj-quick-chip" onclick="quickSendQuestion('กำหนดการรับสมัครรอบทั่วไปวันไหนครับ?')">📅 กำหนดการ</span>
        <span class="skj-quick-chip" onclick="quickSendQuestion('ต้องการแก้ไขข้อมูลการสมัคร ต้องทำอย่างไรครับ?')">✏️ แก้ไขข้อมูล</span>
        <span class="skj-quick-chip" onclick="quickSendQuestion('ขอเบอร์โทรติดต่อฝ่ายรับสมัครโรงเรียนครับ')">📞 เบอร์ติดต่อ</span>
    </div>

    <!-- Input Footer -->
    <div class="skj-chat-footer">
        <form id="skjUserChatForm" class="ajax-form" data-ajax="true" onsubmit="sendUserChatMessage(event)">
            <div class="skj-chat-input-box">
                <input type="text" id="skjUserChatInput" placeholder="พิมพ์ข้อความสอบถามที่นี่..." autocomplete="off" required>
                <button type="submit" id="btnUserChatSend" class="no-disable" title="ส่งข้อความ">
                    <i class="bx bx-paper-plane"></i>
                </button>
            </div>
        </form>
        <div class="d-flex justify-content-between align-items-center px-1 mt-1">
            <small class="text-muted" style="font-size: 0.68rem;">
                <i class="bx bxl-telegram text-info"></i> ซิงค์แจ้งเตือนไปยังครูผู้ดูแลระบบทันที
            </small>
            <small class="text-muted" style="font-size: 0.68rem;">
                <i class="bx bx-lock-alt text-success"></i> ปลอดภัย
            </small>
        </div>
    </div>
</div>

<script>
(function() {
    let chatSessionToken = localStorage.getItem('skj_admission_chat_token') || '';
    let chatUserName = localStorage.getItem('skj_admission_chat_name') || '';
    let chatUserTel = localStorage.getItem('skj_admission_chat_tel') || '';
    let lastMessageId = 0;
    let isChatOpen = false;
    let pollChatTimer = null;
    let knownMessageIds = new Set();

    // Setup input fields with saved data
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('chatInputName');
        const telInput = document.getElementById('chatInputTel');
        if (nameInput && chatUserName) nameInput.value = chatUserName;
        if (telInput && chatUserTel) telInput.value = chatUserTel;

        // Auto init session in background
        initChatSession();

        // Background check every 8 seconds
        pollChatTimer = setInterval(function() {
            if (chatSessionToken) {
                pollNewMessages(true);
            }
        }, 8000);

        // Hide launcher pill after 7 seconds on desktop
        setTimeout(() => {
            const pill = document.getElementById('skjChatLauncherPill');
            if (pill) pill.style.opacity = '0';
        }, 7000);
    });

    window.saveChatProfile = function() {
        const nameVal = (document.getElementById('chatInputName').value || '').trim();
        const telVal = (document.getElementById('chatInputTel').value || '').trim();
        chatUserName = nameVal;
        chatUserTel = telVal;
        localStorage.setItem('skj_admission_chat_name', chatUserName);
        localStorage.setItem('skj_admission_chat_tel', chatUserTel);
    };

    window.toggleChatWindow = function() {
        const win = document.getElementById('skjChatWindow');
        const icon = document.getElementById('chatLauncherIcon');
        const unreadBadge = document.getElementById('chatUnreadBadge');
        const pill = document.getElementById('skjChatLauncherPill');

        if (win.style.display === 'flex') {
            win.style.display = 'none';
            icon.className = 'bx bx-chat';
            isChatOpen = false;
        } else {
            win.style.display = 'flex';
            icon.className = 'bx bx-chevron-down';
            unreadBadge.style.display = 'none';
            unreadBadge.innerText = '0';
            if (pill) pill.style.display = 'none';
            isChatOpen = true;

            // Scroll to bottom
            const body = document.getElementById('skjChatBody');
            setTimeout(() => { body.scrollTop = body.scrollHeight; }, 100);

            // Focus input
            document.getElementById('skjUserChatInput').focus();

            // Poll immediately
            pollNewMessages(false);
        }
    };

    window.quickSendQuestion = function(text) {
        const input = document.getElementById('skjUserChatInput');
        input.value = text;
        const form = document.getElementById('skjUserChatForm');
        if (form) {
            form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
        }
    };

    function initChatSession() {
        const formData = new FormData();
        formData.append('session_token', chatSessionToken);
        formData.append('user_name', chatUserName);
        formData.append('user_tel', chatUserTel);

        fetch('<?= site_url('api/chat/init') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            const loader = document.getElementById('chatLoadingIndicator');
            if (loader) loader.style.display = 'none';

            if (data.status === 'success' && data.session) {
                chatSessionToken = data.session.session_token;
                localStorage.setItem('skj_admission_chat_token', chatSessionToken);
                renderUserMessages(data.messages || [], false);
            }
        })
        .catch(err => {
            console.error('Error initializing chat:', err);
            const loader = document.getElementById('chatLoadingIndicator');
            if (loader) loader.style.display = 'none';
        });
    }

    window.sendUserChatMessage = function(e) {
        e.preventDefault();
        const input = document.getElementById('skjUserChatInput');
        const text = input.value.trim();
        if (!text) return;

        saveChatProfile();

        const btn = document.getElementById('btnUserChatSend');
        btn.disabled = true;

        // Optimistic UI: Display message immediately
        const tempMsg = {
            message_id: 'temp_' + Date.now(),
            sender_type: 'user',
            sender_name: chatUserName || 'ผู้ติดต่อ',
            message: text,
            created_at: new Date().toISOString()
        };
        appendSingleMessage(tempMsg);
        input.value = '';
        const body = document.getElementById('skjChatBody');
        body.scrollTop = body.scrollHeight;

        const formData = new FormData();
        formData.append('session_token', chatSessionToken);
        formData.append('message', text);
        formData.append('user_name', chatUserName);
        formData.append('user_tel', chatUserTel);

        fetch('<?= site_url('api/chat/send') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success' && data.message) {
                knownMessageIds.add(data.message.message_id);
                if (parseInt(data.message.message_id) > lastMessageId) {
                    lastMessageId = parseInt(data.message.message_id);
                }
            }
        })
        .catch(err => {
            console.error('Error sending message:', err);
        })
        .finally(() => {
            btn.disabled = false;
            input.focus();
        });
    };

    function pollNewMessages(silent = true) {
        if (!chatSessionToken) return;

        fetch(`<?= site_url('api/chat/messages') ?>?session_token=${chatSessionToken}&last_message_id=${lastMessageId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success' && data.messages && data.messages.length > 0) {
                let hasAdminMsg = false;
                data.messages.forEach(m => {
                    if (!knownMessageIds.has(m.message_id)) {
                        appendSingleMessage(m);
                        if (m.sender_type === 'admin') {
                            hasAdminMsg = true;
                        }
                    }
                });

                if (hasAdminMsg) {
                    playChatNotificationSound();
                    if (!isChatOpen) {
                        const badge = document.getElementById('chatUnreadBadge');
                        const count = parseInt(badge.innerText || '0') + 1;
                        badge.innerText = count;
                        badge.style.display = 'flex';
                    }
                }

                if (isChatOpen) {
                    const body = document.getElementById('skjChatBody');
                    body.scrollTop = body.scrollHeight;
                }
            }
        })
        .catch(err => console.error('Error polling chat:', err));
    }

    function renderUserMessages(messages, silent = false) {
        const body = document.getElementById('skjChatBody');
        
        // Preserve welcome card
        const welcome = body.querySelector('.skj-chat-welcome');
        body.innerHTML = '';
        if (welcome) body.appendChild(welcome);

        knownMessageIds.clear();

        messages.forEach(m => {
            appendSingleMessage(m);
        });

        if (!silent) {
            body.scrollTop = body.scrollHeight;
        }
    }

    function appendSingleMessage(m) {
        if (knownMessageIds.has(m.message_id)) return;
        knownMessageIds.add(m.message_id);

        if (parseInt(m.message_id) > lastMessageId) {
            lastMessageId = parseInt(m.message_id);
        }

        const body = document.getElementById('skjChatBody');
        const isUser = m.sender_type === 'user';
        const isAdmin = m.sender_type === 'admin';
        const isSystem = m.sender_type === 'system';

        let rowClass = 'user';
        if (isAdmin) rowClass = 'admin';
        if (isSystem) rowClass = 'system';

        const senderLabel = isAdmin ? (m.sender_name || 'เจ้าหน้าที่/ครูฝ่ายรับสมัคร') : '';
        const timeStr = formatWidgetTime(m.created_at);

        const row = document.createElement('div');
        row.className = `skj-msg-row ${rowClass}`;
        row.innerHTML = `
            ${isAdmin ? `<small class="text-muted mb-1" style="font-size: 0.72rem; font-weight:700;"><i class="bx bx-check-shield text-primary me-1"></i>${escapeChatHtml(senderLabel)}</small>` : ''}
            <div class="skj-msg-bubble ${rowClass}">
                ${escapeChatHtml(m.message).replace(/\\n/g, '<br>')}
            </div>
            ${!isSystem ? `<div class="skj-msg-time">${timeStr}</div>` : ''}
        `;

        body.appendChild(row);
    }

    function playChatNotificationSound() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.type = 'sine';
            osc.frequency.setValueAtTime(587.33, ctx.currentTime);
            osc.frequency.setValueAtTime(880, ctx.currentTime + 0.1);
            gain.gain.setValueAtTime(0.2, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.35);
            osc.start();
            osc.stop(ctx.currentTime + 0.35);
        } catch (e) {}
    }

    function formatWidgetTime(dateStr) {
        if (!dateStr) return '';
        const d = new Date(dateStr.replace(/-/g, '/'));
        if (isNaN(d.getTime())) return dateStr;
        const h = String(d.getHours()).padStart(2, '0');
        const m = String(d.getMinutes()).padStart(2, '0');
        return `${h}:${m} น.`;
    }

    function escapeChatHtml(str) {
        if (!str) return '';
        const p = document.createElement('p');
        p.textContent = str;
        return p.innerHTML;
    }
})();
</script>
<!-- ==================== END SKJ ADMISSION LIVE CHAT WIDGET ==================== -->

