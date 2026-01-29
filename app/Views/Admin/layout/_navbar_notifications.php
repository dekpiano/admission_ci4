<!-- Notification Bell -->
<li class="nav-item dropdown dropdown-notifications navbar-dropdown me-3 me-xl-2">
  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
    data-bs-auto-close="outside" aria-expanded="false">
    <i class="bx bx-bell bx-sm"></i>
    <span class="badge bg-danger rounded-pill badge-notifications" id="notification-badge" style="display: none;">0</span>
  </a>
  <ul class="dropdown-menu dropdown-menu-end py-0" style="min-width: 380px;">
    <li class="dropdown-menu-header border-bottom">
      <div class="dropdown-header d-flex align-items-center py-3">
        <h5 class="text-body mb-0 me-auto">การแจ้งเตือน</h5>
        <a href="javascript:void(0);" class="dropdown-notifications-all text-body" id="mark-all-read" data-bs-toggle="tooltip"
          data-bs-placement="top" title="ทำเครื่องหมายว่าอ่านแล้วทั้งหมด">
          <i class="bx fs-4 bx-envelope-open"></i>
        </a>
      </div>
    </li>
    <li class="dropdown-notifications-list scrollable-container" style="max-height: 400px; overflow-y: auto;">
      <ul class="list-group list-group-flush" id="notification-list">
        <li class="list-group-item text-center py-3">
          <span class="text-muted"><i class="bx bx-loader-alt bx-spin me-1"></i> กำลังโหลด...</span>
        </li>
      </ul>
    </li>
    <li class="dropdown-menu-footer border-top p-2">
      <a href="javascript:void(0);" class="btn btn-light btn-sm w-100" id="refresh-notifications">
        <i class="bx bx-refresh me-1"></i> รีเฟรช
      </a>
    </li>
  </ul>
</li>

<style>
  .dropdown-notifications {
    position: relative;
  }

  .badge-notifications {
    position: absolute;
    top: 0;
    right: 0;
    font-size: 0.65rem;
    padding: 0.25rem 0.45rem;
    transform: translate(25%, -25%);
  }

  .notification-item {
    display: flex;
    align-items: flex-start;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #eee;
    transition: background-color 0.2s;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
  }

  .notification-item:hover {
    background-color: rgba(40, 167, 69, 0.05);
  }

  .notification-item.unread {
    background-color: rgba(40, 167, 69, 0.08);
  }

  .notification-item.unread::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background-color: #28a745;
  }

  .notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-right: 0.75rem;
  }

  .notification-icon.new_applicant {
    background-color: rgba(40, 167, 69, 0.15);
    color: #28a745;
  }

  .notification-icon.system {
    background-color: rgba(0, 123, 255, 0.15);
    color: #007bff;
  }

  .notification-icon.warning {
    background-color: rgba(255, 193, 7, 0.15);
    color: #ffc107;
  }

  .notification-content {
    flex: 1;
    min-width: 0;
  }

  .notification-title {
    font-weight: 600;
    font-size: 0.875rem;
    margin-bottom: 0.15rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .notification-message {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0.15rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .notification-time {
    font-size: 0.7rem;
    color: #adb5bd;
  }

  .notification-empty {
    text-align: center;
    padding: 2rem;
    color: #6c757d;
  }

  .notification-empty i {
    font-size: 3rem;
    margin-bottom: 0.5rem;
    color: #dee2e6;
  }

  /* Scrollbar Styling */
  .dropdown-notifications-list::-webkit-scrollbar {
    width: 5px;
  }

  .dropdown-notifications-list::-webkit-scrollbar-thumb {
    background-color: #ccc;
    border-radius: 10px;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Load notifications on page load
    loadNotifications();

    // Refresh every 30 seconds
    setInterval(loadNotifications, 30000);

    // Refresh button click
    document.getElementById('refresh-notifications').addEventListener('click', function () {
      loadNotifications();
    });

    // Mark all as read
    document.getElementById('mark-all-read').addEventListener('click', function () {
      markAllAsRead();
    });
  });

  function loadNotifications() {
    fetch('<?= site_url('skjadmin/notifications/get') ?>', {
      method: 'GET',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          updateNotificationBadge(data.unread_count);
          renderNotifications(data.notifications);
        }
      })
      .catch(error => {
        console.error('Error loading notifications:', error);
      });
  }

  function updateNotificationBadge(count) {
    const badge = document.getElementById('notification-badge');
    if (count > 0) {
      badge.textContent = count > 99 ? '99+' : count;
      badge.style.display = 'block';
    } else {
      badge.style.display = 'none';
    }
  }

  function renderNotifications(notifications) {
    const list = document.getElementById('notification-list');

    if (notifications.length === 0) {
      list.innerHTML = `
        <li class="notification-empty">
          <i class="bx bx-bell-off"></i>
          <p>ไม่มีการแจ้งเตือน</p>
        </li>
      `;
      return;
    }

    let html = '';
    notifications.forEach(notif => {
      const iconClass = getIconClass(notif.type);
      const unreadClass = notif.is_read ? '' : 'unread';

      html += `
        <li>
          <a href="${notif.url}" class="notification-item ${unreadClass}" 
             data-notification-id="${notif.id}" onclick="markAsRead(${notif.id})">
            <div class="notification-icon ${notif.type}">
              <i class="bx ${iconClass}"></i>
            </div>
            <div class="notification-content">
              <div class="notification-title">${escapeHtml(notif.title)}</div>
              <div class="notification-message">${escapeHtml(notif.message)}</div>
              <div class="notification-time">${escapeHtml(notif.time_ago)}</div>
            </div>
          </a>
        </li>
      `;
    });

    list.innerHTML = html;
  }

  function getIconClass(type) {
    switch (type) {
      case 'new_applicant':
        return 'bx-user-plus';
      case 'system':
        return 'bx-cog';
      case 'warning':
        return 'bx-error';
      default:
        return 'bx-bell';
    }
  }

  function markAsRead(id) {
    fetch('<?= site_url('skjadmin/notifications/read') ?>/' + id, {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json'
      }
    })
      .then(response => response.json())
      .then(data => {
        // Update badge count
        const badge = document.getElementById('notification-badge');
        let currentCount = parseInt(badge.textContent) || 0;
        if (currentCount > 0) {
          updateNotificationBadge(currentCount - 1);
        }
      })
      .catch(error => {
        console.error('Error marking as read:', error);
      });
  }

  function markAllAsRead() {
    fetch('<?= site_url('skjadmin/notifications/read-all') ?>', {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json'
      }
    })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          updateNotificationBadge(0);
          // Remove unread class from all items
          document.querySelectorAll('.notification-item.unread').forEach(item => {
            item.classList.remove('unread');
          });
        }
      })
      .catch(error => {
        console.error('Error marking all as read:', error);
      });
  }

  function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }
</script>
