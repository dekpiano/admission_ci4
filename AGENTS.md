# Agent Instructions & Critical Architecture Rules

## 🚨 CRITICAL NON-NEGOTIABLE RULES

### 1. BOTTOM NAVIGATION (APP BAR & OFFCANVAS DRAWER) - ห้ามลบเด็ดขาด
- **File:** `app/Views/User/UserLayout.php`
- **Sections to protect:**
  - `/* MOBILE & UNIVERSAL BOTTOM NAVIGATION (APP BAR) */` CSS styles
  - `<!-- MOBILE BOTTOM NAVIGATION (APP BAR - MOBILE FIRST) -->` HTML markup (`<nav class="mobile-bottom-bar">`)
  - `#mobileOffcanvasDrawer` Offcanvas menu drawer
- **Notice:** The top navbar has been removed. **Navigation is exclusively driven by the Bottom Navigation App Bar and the Offcanvas Menu Drawer** across all screen sizes (mobile & desktop floating dock). **DO NOT DELETE OR OVERWRITE** this navigation architecture.

### 2. SweetAlert2 Only (No Bootstrap Modals)
- All modals (PDPA agreements, school announcements, system alerts) MUST use `Swal.fire` instead of Bootstrap modals.

### 3. Design System (Suankularb Pink & Sky Blue)
- **Official Suankularb Colors (สีประจำโรงเรียน):**
  - **สีชมพู (Suankularb Pink):** `#ff6b8b`
  - **สีฟ้า (Suankularb Sky Blue):** `#56ccf2`
  - **Gradient:** `linear-gradient(135deg, #ff6b8b 0%, #56ccf2 100%)`
- Mobile-First responsive layouts.

### 4. Content Centering (พยายามไว้กึ่งกลางจอตลอด)
- Always center cards and core contents both horizontally and vertically in the viewport across all user-facing views.

### 5. Thai Buddhist Era Calendar (ปฏิทินภาษาไทย พ.ศ. เสมอ)
- All datepickers, calendars, date inputs, and date displays across User and Admin panels MUST strictly use **Thai language and Buddhist Era (พ.ศ.)** (Christian Year + 543).
- Month names in Thai (ม.ค. - ธ.ค. / มกราคม - ธันวาคม).

