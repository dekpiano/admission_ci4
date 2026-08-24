# Project Guidelines & Critical Architecture Rules

This document outlines the structure, design system, and non-negotiable architecture rules for the School Admission System.

## Project Overview

*   **Type:** School Admission System (ระบบรับสมัครนักเรียนออนไลน์)
*   **School:** โรงเรียนสวนกุหลาบวิทยาลัย (จิรประวัติ) นครสวรรค์
*   **Framework:** CodeIgniter 4
*   **Backend:** PHP (PHP 8.2 Compatible)
*   **Frontend:** Bootstrap 5, Sneat Theme, Vanilla CSS, SweetAlert2, Boxicons

---

## 🚨 MANDATORY RULES (DO NOT REMOVE OR DELETE)

### 1. BOTTOM NAVIGATION (APP BAR & OFFCANVAS DRAWER) - **ห้ามลบเด็ดขาด**
*   **Architecture:** The top navbar has been removed. All user navigation is exclusively handled via:
    1. **Bottom Navigation App Bar (`<nav class="mobile-bottom-bar">`):** Provides instant 1-tap navigation to Home, Apply M.1, Apply M.4, Status, and All Menu Drawer.
    2. **Offcanvas Menu Drawer (`#mobileOffcanvasDrawer`):** Contains full system links, guides, and admin login.
*   **Responsive Behavior:**
    *   **Mobile (`< 992px`):** Fixed bottom app bar across full width.
    *   **Desktop (`>= 992px`):** Ultra-modern floating bottom dock centered horizontally (`width: 540px`).
*   **Location in Code:** `app/Views/User/UserLayout.php`
*   **Constraint:** **NEVER DELETE, REMOVE, OR OVERWRITE** this bottom navigation bar or its styling during any updates or refactoring.

---

### 2. SweetAlert2 (Swal.fire) Only - NO Bootstrap Modals
*   **Rule:** Standard Bootstrap Modals for key user flows (Announcements, PDPA agreements, error/success alerts) are strictly prohibited and replaced with `Swal.fire`.
*   **Persistence:** School Announcements use `localStorage` keying to support "ไม่ต้องแสดงข้อความประกาศนี้อีก" (Don't show again).

---

### 3. Theme & Brand Identity (Suankularb Pink & Sky Blue)
*   **Primary Colors:**
    *   **Rose Pink:** `#e11d48` / `#ff758c` (Gradient: `linear-gradient(135deg, #e11d48 0%, #0284c7 100%)`)
    *   **Sky Blue:** `#0284c7` / `#38bdf8`
*   **Cards:** Double-colored accent borders (`border-top: 4px solid #e11d48; border-bottom: 4px solid #0284c7;`), rounded-20px to 24px, subtle glassmorphism/shadows.

---

### 4. Mobile-First Responsiveness & Viewport Centering
*   **Content Centering (กึ่งกลางจอเสมอ):** Always center cards and core interactive contents vertically and horizontally within the viewport across all screen sizes.
*   **Single-Screen Viewports:** Authentication/Login pages (e.g. `/confirmation/login`) should fit within a single screen height (`100vh`) with no vertical scroll on mobile.
*   **Touch Targets:** Minimum 44px–48px height for all interactive buttons and inputs.
*   **Input Masking:** Phone numbers and Citizen ID cards automatically formatted with mask and cleaned prior to submit.
