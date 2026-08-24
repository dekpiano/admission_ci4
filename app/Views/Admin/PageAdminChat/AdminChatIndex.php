<?= $this->extend('Admin/layout/AdminLayout') ?>

<?= $this->section('styles') ?>
<style>
    /* ==========================================================
       SKJ ADMISSION LIVE CHAT DASHBOARD - COMPACT PRO UI
       ========================================================== */
    
    :root {
        --skj-pink: #ff6b8b;
        --skj-pink-dark: #e04869;
        --skj-blue: #56ccf2;
        --skj-blue-dark: #2f80ed;
        --chat-pink-gradient: linear-gradient(135deg, #ff6b8b 0%, #e04869 100%);
        --chat-blue-gradient: linear-gradient(135deg, #56ccf2 0%, #2f80ed 100%);
        --chat-card-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.08), 0 4px 10px -2px rgba(15, 23, 42, 0.04);
        --chat-border: #edf2f7;
    }

    /* Full-Height Layout Frame (No bulky wasted headers) */
    .chat-layout-card {
        height: calc(100vh - 120px);
        min-height: 580px;
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid var(--chat-border);
        box-shadow: var(--chat-card-shadow);
        display: flex;
        overflow: hidden;
        position: relative;
        margin-top: 4px;
    }

    /* ==================== LEFT SIDEBAR: SESSION LIST ==================== */
    .chat-sidebar {
        width: 380px;
        border-right: 1px solid var(--chat-border);
        display: flex;
        flex-direction: column;
        background: #ffffff;
        flex-shrink: 0;
        z-index: 10;
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s ease;
    }

    .sidebar-header {
        padding: 14px 16px;
        background: #ffffff;
        border-bottom: 1px solid var(--chat-border);
    }

    .chat-brand-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: var(--chat-pink-gradient);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        box-shadow: 0 3px 8px rgba(255, 107, 139, 0.35);
        color: #ffffff;
    }

    .search-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
        margin-top: 10px;
    }

    .search-input-wrap .search-icon {
        position: absolute;
        left: 12px;
        color: #94a3b8;
        font-size: 1.1rem;
        pointer-events: none;
    }

    .search-input-wrap input {
        width: 100%;
        padding: 8px 12px 8px 36px;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        font-size: 0.84rem;
        color: #1e293b;
        outline: none;
        transition: all 0.2s ease;
    }

    .search-input-wrap input:focus {
        background: #ffffff;
        border-color: var(--skj-pink);
        box-shadow: 0 0 0 3px rgba(255, 107, 139, 0.15);
    }

    .chat-filter-tabs {
        display: flex;
        gap: 4px;
        background: #f1f5f9;
        padding: 3px;
        border-radius: 12px;
        margin-top: 10px;
        overflow-x: auto;
    }

    .chat-filter-btn {
        flex: 1;
        border: none;
        background: transparent;
        font-size: 0.74rem;
        font-weight: 700;
        padding: 6px 4px;
        border-radius: 9px;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        white-space: nowrap;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    .chat-filter-btn .badge-count {
        background: rgba(100, 116, 139, 0.12);
        padding: 1px 6px;
        border-radius: 10px;
        font-size: 0.68rem;
    }

    .chat-filter-btn.active {
        background: #ffffff;
        color: #0f172a;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.06);
    }

    .chat-filter-btn.active .badge-count {
        background: rgba(255, 107, 139, 0.15);
        color: var(--skj-pink-dark);
    }

    .chat-list-scroll {
        flex: 1;
        overflow-y: auto;
        padding: 10px 12px;
        display: flex;
        flex-direction: column;
        gap: 7px;
        background: #f8fafc;
    }

    .chat-list-scroll::-webkit-scrollbar {
        width: 5px;
    }
    .chat-list-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    /* Session Card Item */
    .session-card {
        padding: 11px 13px;
        border-radius: 14px;
        background: #ffffff;
        border: 1px solid #edf2f7;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .session-card:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        transform: translateY(-1.5px);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
    }

    .session-card.active {
        background: #fff8f9;
        border-color: #fecdd3;
        box-shadow: 0 4px 14px rgba(255, 107, 139, 0.14);
    }
    
    .session-card.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 14%;
        bottom: 14%;
        width: 4px;
        background: var(--skj-pink);
        border-radius: 0 4px 4px 0;
    }

    .session-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.95rem;
        color: #ffffff;
        flex-shrink: 0;
        position: relative;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 2px solid #ffffff;
    }

    .online-pulse {
        position: absolute;
        bottom: 0px;
        right: 0px;
        width: 11px;
        height: 11px;
        background-color: #22c55e;
        border: 2px solid #ffffff;
        border-radius: 50%;
        box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2);
    }

    .session-name {
        font-size: 0.9rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.3;
    }

    .session-snippet {
        font-size: 0.78rem;
        color: #64748b;
        line-height: 1.4;
    }

    .session-card.active .session-name {
        color: var(--skj-pink-dark);
    }

    .unread-pill-badge {
        background: linear-gradient(135deg, #ff6b8b, #e04869);
        color: #ffffff;
        font-size: 0.7rem;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 20px;
        box-shadow: 0 2px 5px rgba(255, 107, 139, 0.35);
    }

    /* ==================== RIGHT CANVAS: CHAT ROOM ==================== */
    .chat-canvas {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #ffffff;
        position: relative;
        min-width: 0;
    }

    .room-header {
        padding: 12px 18px;
        background: #ffffff;
        border-bottom: 1px solid var(--chat-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 5;
        gap: 10px;
    }

    .room-header-profile {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .room-messages-container {
        flex: 1;
        overflow-y: auto;
        padding: 18px 20px;
        background-color: #f8fafc;
        background-image: radial-gradient(#e2e8f0 1.2px, transparent 1.2px);
        background-size: 20px 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .room-messages-container::-webkit-scrollbar {
        width: 5px;
    }
    .room-messages-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    /* Date Separator Pill */
    .chat-date-separator {
        align-self: center;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 4px 14px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #64748b;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
        margin: 4px 0;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    /* Message Bubbles */
    .msg-group {
        display: flex;
        flex-direction: column;
        max-width: 72%;
        position: relative;
        animation: msgFadeIn 0.2s ease-out;
    }

    @keyframes msgFadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .msg-group.user {
        align-self: flex-start;
    }

    .msg-group.admin {
        align-self: flex-end;
    }

    .msg-group.system {
        align-self: center;
        max-width: 88%;
        text-align: center;
    }

    .msg-bubble {
        padding: 10px 16px;
        border-radius: 18px;
        font-size: 0.91rem;
        line-height: 1.55;
        word-break: break-word;
        box-shadow: 0 1px 6px rgba(15, 23, 42, 0.03);
    }

    .msg-group.user .msg-bubble {
        background: #ffffff;
        border: 1.5px solid #edf2f7;
        color: #0f172a;
        border-bottom-left-radius: 4px;
    }

    .msg-group.admin .msg-bubble {
        background: var(--chat-pink-gradient);
        color: #ffffff;
        border-bottom-right-radius: 4px;
        box-shadow: 0 4px 14px rgba(255, 107, 139, 0.25);
    }

    .msg-group.system .msg-bubble {
        background: #f0fdf4;
        color: #166534;
        border: 1px dashed #bbf7d0;
        border-radius: 14px;
        font-size: 0.8rem;
        padding: 6px 14px;
    }

    .msg-meta-row {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.7rem;
        color: #94a3b8;
        margin-top: 3px;
    }
    .msg-group.admin .msg-meta-row {
        justify-content: flex-end;
    }

    /* Bottom Input & Quick Replies Capsule */
    .room-footer {
        padding: 10px 16px;
        background: #ffffff;
        border-top: 1px solid var(--chat-border);
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .quick-pill-container {
        display: flex;
        gap: 5px;
        overflow-x: auto;
        padding-bottom: 2px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .quick-pill-container::-webkit-scrollbar {
        display: none;
    }

    .quick-pill {
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
    .quick-pill:hover, .quick-pill:active {
        background: #fff0f3;
        border-color: #ff8ea7;
        color: var(--skj-pink-dark);
        transform: translateY(-1px);
    }

    .input-capsule {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 30px;
        padding: 3px 5px 3px 15px;
        transition: all 0.2s ease;
    }
    .input-capsule:focus-within {
        background: #ffffff;
        border-color: var(--skj-pink);
        box-shadow: 0 0 0 3px rgba(255, 107, 139, 0.15);
    }

    .input-capsule input {
        flex: 1;
        border: none;
        background: transparent;
        font-size: 0.92rem;
        color: #0f172a;
        outline: none;
        padding: 6px 0;
        min-width: 0;
    }

    .btn-send-gradient {
        background: var(--chat-pink-gradient);
        color: #ffffff;
        border: none;
        border-radius: 50px;
        padding: 7px 18px;
        font-weight: 700;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        box-shadow: 0 3px 10px rgba(255, 107, 139, 0.3);
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    .btn-send-gradient:hover {
        transform: scale(1.03);
        box-shadow: 0 5px 15px rgba(255, 107, 139, 0.4);
        color: #ffffff;
    }

    /* Back Button on Mobile */
    .btn-chat-back {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--chat-border);
        background: #f8fafc;
        color: #334155;
        cursor: pointer;
        padding: 0;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }
    .btn-chat-back:hover, .btn-chat-back:active {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* Empty Canvas Placeholder */
    .empty-chat-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #64748b;
        padding: 30px 20px;
        text-align: center;
    }

    .empty-chat-icon {
        width: 72px;
        height: 72px;
        border-radius: 22px;
        background: linear-gradient(135deg, rgba(255, 107, 139, 0.12), rgba(86, 204, 242, 0.12));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        color: var(--skj-pink);
        margin-bottom: 16px;
        box-shadow: 0 8px 20px rgba(255, 107, 139, 0.12);
    }

    .empty-feature-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-top: 20px;
        max-width: 520px;
        width: 100%;
    }

    .empty-feature-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px 8px;
        text-align: center;
        font-size: 0.78rem;
        color: #475569;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .empty-feature-item i {
        font-size: 1.3rem;
        display: block;
        margin-bottom: 4px;
        color: var(--skj-pink);
    }

    /* ==========================================================
       RESPONSIVE DESIGN FOR SMARTPHONES & TABLETS (Mobile First)
       ========================================================== */
    @media (max-width: 991.98px) {
        .chat-layout-card {
            height: calc(100vh - 100px);
            min-height: 480px;
            border-radius: 16px;
        }

        .chat-sidebar {
            width: 320px;
        }

        .empty-feature-cards {
            grid-template-columns: 1fr;
            max-width: 280px;
        }
    }

    @media (max-width: 767.98px) {
        .chat-layout-card {
            height: calc(100dvh - 100px);
            min-height: calc(100vh - 100px);
            border-radius: 14px;
            margin-top: 0;
            margin-bottom: 5px;
        }

        /* Mobile View Toggle: When chat is closed vs opened */
        .chat-sidebar {
            width: 100%;
            border-right: none;
            display: flex;
        }

        .chat-canvas {
            display: none;
            width: 100%;
        }

        /* When a room is active on mobile */
        .chat-layout-card.chat-room-active .chat-sidebar {
            display: none !important;
        }

        .chat-layout-card.chat-room-active .chat-canvas {
            display: flex !important;
        }

        /* Enable Back Button on Mobile */
        .btn-chat-back {
            display: inline-flex;
        }

        /* Room Header adjustments */
        .room-header {
            padding: 9px 12px;
        }
        .session-avatar {
            width: 36px;
            height: 36px;
            font-size: 0.88rem;
        }
        #currentUserName {
            font-size: 0.9rem !important;
            max-width: 130px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        #currentUserTel, #currentUserTokenBadge {
            font-size: 0.68rem !important;
        }

        .room-header-actions .btn {
            padding: 4px 8px !important;
            font-size: 0.72rem !important;
        }

        /* Message Bubbles & Container on Mobile */
        .room-messages-container {
            padding: 10px 10px;
            gap: 8px;
        }

        .msg-group {
            max-width: 86%;
        }

        .msg-bubble {
            padding: 8px 12px;
            font-size: 0.86rem;
            border-radius: 14px;
        }

        /* Footer & Input on Mobile */
        .room-footer {
            padding: 6px 10px;
            gap: 5px;
        }

        .input-capsule {
            padding: 2px 4px 2px 10px;
        }

        .input-capsule input {
            font-size: 0.88rem;
            padding: 4px 0;
        }

        .btn-send-gradient {
            padding: 5px 12px;
            font-size: 0.78rem;
        }
        .btn-send-gradient span {
            display: none;
        }
        .btn-send-gradient i {
            font-size: 1rem;
            margin: 0 !important;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xxl flex-grow-1 container-p-y px-2 px-sm-3 pt-2">

    <!-- Main Chat Frame Card (Integrated Clean Layout) -->
    <div class="chat-layout-card" id="chatLayoutCard">
        
        <!-- ==================== LEFT SIDEBAR ==================== -->
        <div class="chat-sidebar">
            
            <!-- Sidebar Header & Controls -->
            <div class="sidebar-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="chat-brand-icon">
                            <i class="bx bxs-chat"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem; line-height: 1.2;">Live Chat</h6>
                            <small class="text-muted" style="font-size: 0.7rem;" id="statSummaryText">กำลังโหลด...</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <a href="<?= site_url('skjadmin/telegram-notify') ?>" class="btn btn-sm btn-outline-info rounded-circle p-0" title="ตั้งค่า Telegram Bot" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="bx bxl-telegram fs-5"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle p-0" onclick="loadSessions(true)" title="รีเฟรชข้อความ" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                            <i class="bx bx-refresh fs-5"></i>
                        </button>
                    </div>
                </div>

                <!-- Search Input -->
                <div class="search-input-wrap">
                    <i class="bx bx-search search-icon"></i>
                    <input type="text" id="searchChatInput" placeholder="ค้นหาชื่อ, เบอร์โทร, ข้อความ..." onkeyup="filterSessions()">
                </div>

                <!-- Filter Tabs -->
                <div class="chat-filter-tabs">
                    <button class="chat-filter-btn active" onclick="setChatFilter('all', this)">
                        ทั้งหมด <span class="badge-count" id="filterCountAll">0</span>
                    </button>
                    <button class="chat-filter-btn" onclick="setChatFilter('unread', this)">
                        🔔 รอตอบ <span class="badge-count text-danger fw-bold" id="filterCountUnread" style="display: none;">0</span>
                    </button>
                    <button class="chat-filter-btn" onclick="setChatFilter('active', this)">
                        🟢 คุยอยู่ <span class="badge-count" id="filterCountActive">0</span>
                    </button>
                    <button class="chat-filter-btn" onclick="setChatFilter('closed', this)">
                        📁 ปิดแล้ว
                    </button>
                </div>
            </div>

            <!-- List of Chat Sessions -->
            <div class="chat-list-scroll" id="chatListContainer">
                <div class="text-center p-4 text-muted">
                    <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                    <div class="small">กำลังโหลดห้องสนทนา...</div>
                </div>
            </div>

        </div>

        <!-- ==================== RIGHT CANVAS (CHAT ROOM) ==================== -->
        <div class="chat-canvas" id="chatMainArea">
            
            <!-- Empty State Placeholder -->
            <div class="empty-chat-placeholder" id="emptyStateBox">
                <div class="empty-chat-icon">
                    <i class="bx bx-chat"></i>
                </div>
                <h5 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">เลือกห้องสนทนาเพื่อเริ่มบริการ</h5>
                <p class="text-muted small mb-0" style="max-width: 360px; line-height: 1.5;">
                    คลิกเลือกผู้ติดต่อจากรายการด้านซ้ายเพื่อดูประวัติการสนทนา หรือพิมพ์ตอบคำถามนักเรียนและผู้ปกครอง
                </p>

                <div class="empty-feature-cards">
                    <div class="empty-feature-item">
                        <i class="bx bx-bolt-circle"></i>
                        <span>ตอบกลับสดเรียลไทม์</span>
                    </div>
                    <div class="empty-feature-item">
                        <i class="bx bxl-telegram"></i>
                        <span>ซิงค์กับ Telegram 2 ทาง</span>
                    </div>
                    <div class="empty-feature-item">
                        <i class="bx bx-message-alt-check"></i>
                        <span>ปุ่มตอบด่วน 1-Click</span>
                    </div>
                </div>
            </div>

            <!-- Active Room Container -->
            <div id="activeRoomContent" style="display: none; height: 100%; flex-direction: column; width: 100%;">
                
                <!-- Room Header -->
                <div class="room-header">
                    <div class="room-header-profile">
                        <!-- Mobile Back Button -->
                        <button type="button" class="btn-chat-back" onclick="backToSessionList()" title="ย้อนกลับรายการ">
                            <i class="bx bx-chevron-left fs-3"></i>
                        </button>

                        <div class="session-avatar" id="currentAvatar" style="background: var(--chat-pink-gradient);">
                            U
                        </div>
                        <div class="overflow-hidden">
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="mb-0 fw-bold text-dark text-truncate" id="currentUserName" style="font-size: 0.98rem;">ผู้ติดต่อ</h6>
                                <span class="badge bg-label-success rounded-pill px-2 py-0 small font-monospace flex-shrink-0" id="currentStatusBadge">Active</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <small class="text-muted text-truncate" id="currentUserTel">
                                    <i class="bx bx-phone text-primary me-1"></i> -
                                </small>
                                <span class="text-muted" style="font-size: 0.7rem;">•</span>
                                <small class="text-muted font-monospace" id="currentUserTokenBadge" style="font-size: 0.72rem;">
                                    #CHAT_...
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Header Action Tools -->
                    <div class="d-flex align-items-center gap-1 gap-sm-2 room-header-actions flex-shrink-0">
                        <a href="javascript:void(0);" class="btn btn-outline-success btn-sm rounded-pill px-2 px-sm-3" id="btnCallUser" target="_blank" style="display: none;">
                            <i class="bx bx-phone me-1"></i> <span class="d-none d-sm-inline">โทรหา</span>
                        </a>
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-2 px-sm-3" id="btnToggleStatus" onclick="toggleCurrentSessionStatus()">
                            <i class="bx bx-check-circle me-1"></i> <span class="d-none d-sm-inline">จบการสนทนา</span>
                        </button>
                    </div>
                </div>

                <!-- Messages Stream Canvas -->
                <div class="room-messages-container" id="chatMessagesBox">
                    <!-- Dynamic Bubble Items -->
                </div>

                <!-- Footer: Quick Replies & Input Capsule -->
                <div class="room-footer">
                    
                    <!-- Quick Reply Suggestions -->
                    <div class="quick-pill-container">
                        <span class="quick-pill" onclick="insertQuickReply('สวัสดีครับ ยินดีต้อนรับสู่ระบบรับสมัครนักเรียน SKJ ครับ มีข้อสงสัยสอบถามได้เลยครับ')">👋 ทักทาย</span>
                        <span class="quick-pill" onclick="insertQuickReply('เอกสารที่ต้องใช้: สำเนา ปพ.1, สำเนาทะเบียนบ้าน, สำเนาบัตรประชาชน และรูปถ่าย 1.5 นิ้วครับ')">📄 เอกสารที่ใช้</span>
                        <span class="quick-pill" onclick="insertQuickReply('สามารถตรวจสอบสถานะการสมัครได้ที่เมนู ตรวจสอบสถานะ บนหน้าเว็บไซต์ครับ')">🔍 ตรวจสอบสถานะ</span>
                        <span class="quick-pill" onclick="insertQuickReply('ระบบเปิดรับสมัครรอบทั่วไป ตั้งแต่วันที่ระบุในประกาศ หากมีข้อสงสัยโทร. 035-779-106 ครับ')">📅 กำหนดการ</span>
                        <span class="quick-pill" onclick="insertQuickReply('หากต้องการแก้ไขข้อมูลการสมัคร สามารถแจ้งชื่อ-สกุล และเลขประจำตัวประชาชน เพื่อให้เจ้าหน้าที่ตรวจสอบให้ครับ')">✏️ แก้ไขข้อมูล</span>
                        <span class="quick-pill" onclick="insertQuickReply('ยินดีให้บริการครับ หากมีข้อสงสัยเพิ่มเติมสอบถามได้ตลอดเวลานะครับ ขอบคุณครับ ✨')">🙏 ขอบคุณ/ปิดบทสนทนา</span>
                    </div>

                    <!-- Input Capsule Form -->
                    <form id="adminReplyForm" class="ajax-form" data-ajax="true" onsubmit="sendAdminReply(event)">
                        <div class="input-capsule">
                            <input type="text" id="adminReplyInput" placeholder="พิมพ์ข้อความตอบกลับที่นี่..." autocomplete="off" required>
                            <button class="btn-send-gradient no-disable" type="submit" id="btnAdminSend">
                                <span>ส่ง</span>
                                <i class="bx bx-paper-plane"></i>
                            </button>
                        </div>
                    </form>

                    <div class="d-flex justify-content-between align-items-center px-1">
                        <small class="text-muted" style="font-size: 0.68rem;">
                            <i class="bx bxl-telegram text-info me-1"></i> ข้อความซิงค์กับ Telegram Bot อัตโนมัติ
                        </small>
                        <small class="text-muted d-none d-sm-inline" style="font-size: 0.68rem;">
                            <i class="bx bx-info-circle me-1"></i> กด <kbd class="bg-light text-dark px-1 rounded border">Enter</kbd> เพื่อส่งทันที
                        </small>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let allSessions = [];
    let currentSessionId = null;
    let currentFilter = 'all';
    let pollInterval = null;
    const initialSessionToken = '<?= esc($activeToken) ?>';
    let initialTokenHandled = false;

    // Avatar Color Palette for contacts (Suankularb & Harmonious Pastels)
    const avatarGradients = [
        'linear-gradient(135deg, #ff6b8b, #ff8ea7)',
        'linear-gradient(135deg, #56ccf2, #2f80ed)',
        'linear-gradient(135deg, #8b5cf6, #a78bfa)',
        'linear-gradient(135deg, #10b981, #34d399)',
        'linear-gradient(135deg, #f59e0b, #fbbf24)',
        'linear-gradient(135deg, #ec4899, #f472b6)'
    ];

    document.addEventListener('DOMContentLoaded', function() {
        loadSessions(false);

        // Auto poll sessions and current messages every 3.5 seconds
        pollInterval = setInterval(function() {
            loadSessions(false, true);
            if (currentSessionId) {
                loadSessionMessages(currentSessionId, true);
            }
        }, 3500);
    });

    function backToSessionList() {
        const layoutCard = document.getElementById('chatLayoutCard');
        if (layoutCard) {
            layoutCard.classList.remove('chat-room-active');
        }
        currentSessionId = null;
        initialTokenHandled = true;

        // Hide active room and restore empty state
        const activeRoom = document.getElementById('activeRoomContent');
        const emptyState = document.getElementById('emptyStateBox');
        if (activeRoom) activeRoom.style.display = 'none';
        if (emptyState) emptyState.style.display = 'flex';

        // Clear ?session= param from address bar without reloading
        if (window.location.search.includes('session=')) {
            window.history.replaceState({}, document.title, window.location.pathname);
        }

        lastSessionsSignature = '';
        filterSessions();
    }

    function loadSessions(showSpinner = false, silent = false) {
        if (showSpinner && !silent) {
            document.getElementById('chatListContainer').innerHTML = `
                <div class="text-center p-4 text-muted">
                    <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                    <div class="small">กำลังโหลดข้อมูล...</div>
                </div>`;
        }

        fetch('<?= site_url('skjadmin/live-chat/sessions') ?>', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                allSessions = data.sessions || [];
                updateStatsOverview(allSessions);
                filterSessions();

                // Auto select token from URL ONLY ONCE on initial load
                if (initialSessionToken && !initialTokenHandled && !currentSessionId) {
                    initialTokenHandled = true;
                    const match = allSessions.find(s => s.session_token === initialSessionToken);
                    if (match) {
                        selectSession(match.session_id);
                    }
                }
            }
        })
        .catch(err => console.error('Error fetching sessions:', err));
    }

    function updateStatsOverview(sessions) {
        const total = sessions.length;
        const active = sessions.filter(s => s.status === 'active').length;
        const unread = sessions.reduce((acc, s) => acc + (parseInt(s.unread_admin_count) || 0), 0);

        const summaryEl = document.getElementById('statSummaryText');
        if (summaryEl) {
            summaryEl.innerText = `${total} ห้องสนทนา • กำลังคุย ${active}`;
        }

        const countAllEl = document.getElementById('filterCountAll');
        if (countAllEl) countAllEl.innerText = total;

        const countActiveEl = document.getElementById('filterCountActive');
        if (countActiveEl) countActiveEl.innerText = active;

        const unreadBadge = document.getElementById('filterCountUnread');
        if (unreadBadge) {
            if (unread > 0) {
                unreadBadge.innerText = unread;
                unreadBadge.style.display = 'inline-block';
            } else {
                unreadBadge.style.display = 'none';
            }
        }
    }

    function setChatFilter(filterType, btnEl) {
        currentFilter = filterType;
        document.querySelectorAll('.chat-filter-btn').forEach(b => b.classList.remove('active'));
        if (btnEl) btnEl.classList.add('active');
        filterSessions();
    }

    function filterSessions() {
        const q = (document.getElementById('searchChatInput').value || '').toLowerCase().trim();
        let filtered = allSessions;

        // Apply Tab Filter
        if (currentFilter === 'unread') {
            filtered = filtered.filter(s => (parseInt(s.unread_admin_count) || 0) > 0);
        } else if (currentFilter === 'active') {
            filtered = filtered.filter(s => s.status === 'active');
        } else if (currentFilter === 'closed') {
            filtered = filtered.filter(s => s.status === 'closed');
        }

        // Apply Search Query
        if (q) {
            filtered = filtered.filter(s => 
                (s.user_name && s.user_name.toLowerCase().includes(q)) ||
                (s.user_tel && s.user_tel.toLowerCase().includes(q)) ||
                (s.last_message && s.last_message.toLowerCase().includes(q)) ||
                (s.session_token && s.session_token.toLowerCase().includes(q))
            );
        }

        renderSessionsList(filtered);
    }

    let lastSessionsSignature = '';
    let lastRenderedRoomId = null;
    let lastRenderedMessagesSignature = '';

    function renderSessionsList(sessions) {
        const signature = JSON.stringify(sessions.map(s => [s.session_id, s.unread_admin_count, s.status, s.last_message, s.last_message_time, currentSessionId === s.session_id]));
        if (signature === lastSessionsSignature) {
            return; // Data has not changed, do not touch DOM
        }
        lastSessionsSignature = signature;

        const container = document.getElementById('chatListContainer');
        if (!sessions || sessions.length === 0) {
            container.innerHTML = `
                <div class="text-center p-4 text-muted">
                    <i class="bx bx-conversation fs-2 opacity-50 mb-1"></i>
                    <div class="small fw-bold">ไม่พบห้องสนทนา</div>
                    <small class="text-muted" style="font-size: 0.72rem;">เมื่อมีผู้ติดต่อจากหน้าเว็บ รายการจะแสดงที่นี่</small>
                </div>`;
            return;
        }

        let html = '';
        sessions.forEach((s) => {
            const isActive = currentSessionId === s.session_id ? 'active' : '';
            const unreadCount = parseInt(s.unread_admin_count) || 0;
            const unreadBadge = unreadCount > 0 ? `<span class="unread-pill-badge">${unreadCount}</span>` : '';
            const initial = (s.user_name || 'U').charAt(0).toUpperCase();
            const timeStr = formatTime(s.last_message_time || s.updated_at);
            const gradient = avatarGradients[s.session_id % avatarGradients.length];
            const isOnline = s.status === 'active';

            html += `
            <div class="session-card ${isActive}" onclick="selectSession(${s.session_id})">
                <div class="d-flex align-items-center gap-2">
                    <div class="session-avatar" style="background: ${gradient};">
                        ${initial}
                        ${isOnline ? '<span class="online-pulse" title="กำลังใช้งาน"></span>' : ''}
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="mb-0 session-name text-truncate" title="${escapeHtml(s.user_name)}">
                                ${escapeHtml(s.user_name)}
                            </h6>
                            <small class="text-muted" style="font-size: 0.68rem; font-weight: 500;">${timeStr}</small>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="session-snippet mb-0 text-truncate" style="max-width: 200px;">
                                ${s.last_sender === 'admin' ? '<span class="text-primary fw-bold">คุณ: </span>' : ''}${escapeHtml(s.last_message || 'เริ่มการสนทนา')}
                            </p>
                            ${unreadBadge}
                        </div>
                    </div>
                </div>
            </div>`;
        });

        container.innerHTML = html;
    }

    function selectSession(sessionId) {
        if (currentSessionId !== sessionId) {
            lastRenderedRoomId = null;
            lastRenderedMessagesSignature = '';
        }
        currentSessionId = sessionId;
        document.getElementById('emptyStateBox').style.display = 'none';
        document.getElementById('activeRoomContent').style.display = 'flex';

        // Add mobile room active class to transition to chat view
        const layoutCard = document.getElementById('chatLayoutCard');
        if (layoutCard) {
            layoutCard.classList.add('chat-room-active');
        }

        // Re-render session list to update active highlight
        lastSessionsSignature = '';
        filterSessions();

        loadSessionMessages(sessionId, false);
    }

    function loadSessionMessages(sessionId, silent = false) {
        fetch(`<?= site_url('skjadmin/live-chat/messages') ?>/${sessionId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const s = data.session;
                document.getElementById('currentUserName').innerText = s.user_name;
                document.getElementById('currentAvatar').innerText = (s.user_name || 'U').charAt(0).toUpperCase();
                document.getElementById('currentAvatar').style.background = avatarGradients[s.session_id % avatarGradients.length];
                
                // Phone & call button
                const telEl = document.getElementById('currentUserTel');
                const callBtn = document.getElementById('btnCallUser');
                if (s.user_tel && s.user_tel !== '-') {
                    telEl.innerHTML = `<i class="bx bx-phone text-primary me-1"></i> <a href="tel:${s.user_tel}" class="text-dark fw-bold">${s.user_tel}</a>`;
                    callBtn.href = `tel:${s.user_tel}`;
                    callBtn.style.display = 'inline-flex';
                } else {
                    telEl.innerHTML = `<i class="bx bx-phone text-muted me-1"></i> ไม่ได้ระบุเบอร์`;
                    callBtn.style.display = 'none';
                }

                document.getElementById('currentUserTokenBadge').innerText = `#CHAT_${s.session_token.substring(0,8)}`;
                
                // Status badge & toggle button
                const statusBadge = document.getElementById('currentStatusBadge');
                const btnToggle = document.getElementById('btnToggleStatus');
                if (s.status === 'active') {
                    statusBadge.className = 'badge bg-label-success rounded-pill px-2 py-0 small font-monospace';
                    statusBadge.innerText = 'Active';
                    btnToggle.innerHTML = '<i class="bx bx-check-circle me-1"></i> จบการสนทนา';
                    btnToggle.className = 'btn btn-outline-secondary btn-sm rounded-pill px-3';
                } else {
                    statusBadge.className = 'badge bg-label-secondary rounded-pill px-2 py-0 small font-monospace';
                    statusBadge.innerText = 'Closed';
                    btnToggle.innerHTML = '<i class="bx bx-refresh me-1"></i> เปิดการสนทนาใหม่';
                    btnToggle.className = 'btn btn-outline-success btn-sm rounded-pill px-3';
                }

                renderMessagesStream(data.messages || [], silent, sessionId);
            }
        })
        .catch(err => console.error('Error loading session messages:', err));
    }

    function renderMessagesStream(messages, silent = false, sessionId = null) {
        const msgSignature = (sessionId || '') + '_' + messages.length + '_' + (messages.length > 0 ? messages[messages.length - 1].message_id : '0');
        if (silent && msgSignature === lastRenderedMessagesSignature && lastRenderedRoomId === sessionId) {
            return; // No new messages, do not re-render DOM to avoid flicker!
        }
        lastRenderedMessagesSignature = msgSignature;
        lastRenderedRoomId = sessionId;

        const box = document.getElementById('chatMessagesBox');
        let html = '';

        // Add start date pill
        html += `<div class="chat-date-separator"><i class="bx bx-calendar-event text-primary"></i> บันทึกการสนทนา</div>`;

        messages.forEach(m => {
            const isUser = m.sender_type === 'user';
            const isAdmin = m.sender_type === 'admin';
            const isSystem = m.sender_type === 'system';

            let typeClass = 'user';
            if (isAdmin) typeClass = 'admin';
            if (isSystem) typeClass = 'system';

            const senderLabel = isAdmin ? (m.sender_name || 'คุณ (เจ้าหน้าที่)') : (isUser ? (m.sender_name || 'ผู้ติดต่อ') : 'ระบบ');
            const timeStr = formatTime(m.created_at);

            html += `
            <div class="msg-group ${typeClass}">
                ${!isSystem ? `
                    <div class="msg-meta-row mb-1">
                        <span class="fw-bold text-dark" style="font-size: 0.75rem;">
                            ${isAdmin ? '<i class="bx bx-shield-quarter text-primary me-1"></i>' : '<i class="bx bx-user text-secondary me-1"></i>'}${escapeHtml(senderLabel)}
                        </span>
                    </div>` : ''}
                
                <div class="msg-bubble">
                    ${escapeHtml(m.message).replace(/\\n/g, '<br>')}
                </div>
                
                ${!isSystem ? `
                    <div class="msg-meta-row">
                        <span>${timeStr}</span>
                        ${isAdmin ? '<i class="bx bx-check-double text-primary" style="font-size: 0.9rem;" title="ส่งเรียบร้อยแล้ว"></i>' : ''}
                    </div>` : ''}
            </div>`;
        });

        const isNearBottom = box.scrollHeight - box.clientHeight <= box.scrollTop + 140;
        box.innerHTML = html;

        if (!silent || isNearBottom) {
            box.scrollTop = box.scrollHeight;
        }
    }

    function sendAdminReply(e) {
        e.preventDefault();
        const input = document.getElementById('adminReplyInput');
        const text = input.value.trim();
        if (!text || !currentSessionId) return;

        const btn = document.getElementById('btnAdminSend');

        // Optimistic UI update
        const box = document.getElementById('chatMessagesBox');
        const tempDiv = document.createElement('div');
        tempDiv.className = 'msg-group admin';
        tempDiv.innerHTML = `
            <div class="msg-meta-row mb-1">
                <span class="fw-bold text-dark" style="font-size: 0.75rem;"><i class="bx bx-shield-quarter text-primary me-1"></i>คุณ (กำลังส่ง...)</span>
            </div>
            <div class="msg-bubble">${escapeHtml(text).replace(/\\n/g, '<br>')}</div>
            <div class="msg-meta-row">
                <span>${formatTime(new Date().toISOString())}</span>
                <i class="bx bx-check text-muted" style="font-size: 0.9rem;"></i>
            </div>
        `;
        box.appendChild(tempDiv);
        box.scrollTop = box.scrollHeight;
        input.value = '';

        const formData = new FormData();
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        formData.append('session_id', currentSessionId);
        formData.append('message', text);

        fetch('<?= site_url('skjadmin/live-chat/reply') ?>', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                loadSessionMessages(currentSessionId, true);
                loadSessions(false, true);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'ส่งไม่สำเร็จ',
                    text: data.message || 'เกิดข้อผิดพลาดในการส่งข้อความ',
                    confirmButtonColor: '#ff6b8b'
                });
            }
        })
        .catch(err => console.error('Error sending reply:', err))
        .finally(() => {
            input.focus();
        });
    }

    function toggleCurrentSessionStatus() {
        if (!currentSessionId) return;

        fetch(`<?= site_url('skjadmin/live-chat/toggle-status') ?>/${currentSessionId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                loadSessionMessages(currentSessionId, false);
                loadSessions(false, true);
            }
        });
    }

    function formatTime(dateStr) {
        if (!dateStr) return '';
        const d = new Date(dateStr.replace(/-/g, '/'));
        if (isNaN(d.getTime())) return dateStr;
        const hours = String(d.getHours()).padStart(2, '0');
        const minutes = String(d.getMinutes()).padStart(2, '0');
        return `${hours}:${minutes} น.`;
    }

    function escapeHtml(str) {
        if (!str) return '';
        const p = document.createElement('p');
        p.textContent = str;
        return p.innerHTML;
    }
</script>
<?= $this->endSection() ?>
