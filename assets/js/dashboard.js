/**
 * Dashboard JavaScript — modals, toasts, sidebar, animations
 */

document.addEventListener('DOMContentLoaded', () => {
    initSidebar();
    initModal();
    initToasts();
    initAnimations();
    initLenis();
});

/* ── Sidebar toggle ─────────────────────────────────────── */
function initSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const toggleBtn = document.getElementById('sidebarToggle');

    mobileBtn?.addEventListener('click', () => sidebar?.classList.toggle('open'));
    toggleBtn?.addEventListener('click', () => sidebar?.classList.toggle('open'));

    document.addEventListener('click', (e) => {
        if (sidebar?.classList.contains('open') &&
            !sidebar.contains(e.target) &&
            e.target !== mobileBtn) {
            sidebar.classList.remove('open');
        }
    });
}

/* ── Modal system ───────────────────────────────────────── */
function initModal() {
    const overlay = document.getElementById('modalOverlay');
    const closeBtn = document.getElementById('modalClose');

    closeBtn?.addEventListener('click', closeModal);
    overlay?.addEventListener('click', (e) => {
        if (e.target === overlay) closeModal();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });
}

function openModal(title, bodyHtml) {
    const overlay = document.getElementById('modalOverlay');
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalBody').innerHTML = bodyHtml;
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('modalOverlay')?.classList.remove('active');
    document.body.style.overflow = '';
}

/* ── Toast notifications ──────────────────────────────────── */
function initToasts() {
    const flash = document.getElementById('flashToast');
    if (flash) {
        setTimeout(() => {
            flash.style.animation = 'slideIn 0.3s reverse forwards';
            setTimeout(() => flash.remove(), 300);
        }, 4000);
    }
}

function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `<span>${message}</span><button class="toast-close" onclick="this.parentElement.remove()">&times;</button>`;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}

/* ── GSAP fade-up animations ──────────────────────────────── */
function initAnimations() {
    if (typeof gsap === 'undefined') return;

    document.querySelectorAll('.fade-up').forEach((el, i) => {
        gsap.to(el, {
            opacity: 1,
            y: 0,
            duration: 0.7,
            delay: i * 0.08,
            ease: 'cubic-bezier(0.34, 1.56, 0.64, 1)',
        });
    });

    document.querySelectorAll('.stat-card').forEach((card) => {
        card.addEventListener('mouseenter', () => {
            gsap.to(card, { y: -4, duration: 0.3, ease: 'power2.out' });
        });
        card.addEventListener('mouseleave', () => {
            gsap.to(card, { y: 0, duration: 0.3, ease: 'power2.out' });
        });
    });
}

/* ── Lenis smooth scroll ──────────────────────────────────── */
function initLenis() {
    if (typeof Lenis === 'undefined') return;
    const lenis = new Lenis({ duration: 1.2, smoothWheel: true });
    function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
    requestAnimationFrame(raf);
}

/* ── CSRF helper for fetch requests ───────────────────────── */
function csrfHeaders() {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    return {
        'Content-Type': 'application/json',
        'X-CSRF-Token': token,
    };
}

/* ── Appointment approve modal ──────────────────────────────── */
function approveAppointment(id) {
    openModal('Approve Appointment', `
        <form id="approveForm" onsubmit="submitApprove(event, ${id})">
            <div class="form-group">
                <label>Appointment Date</label>
                <input type="date" name="approved_date" class="form-control" required min="${new Date().toISOString().split('T')[0]}">
            </div>
            <div class="form-group">
                <label>Appointment Time</label>
                <input type="time" name="approved_time" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Notes (optional)</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Any additional notes..."></textarea>
            </div>
            <button type="submit" class="btn btn-success" style="width:100%">Confirm Approval</button>
        </form>
    `);
}

function submitApprove(e, id) {
    e.preventDefault();
    const form = e.target;
    const data = {
        action: 'approve',
        id: id,
        approved_date: form.approved_date.value,
        approved_time: form.approved_time.value,
        notes: form.notes.value,
    };

    fetch(getApiUrl('appointments.php'), {
        method: 'POST',
        headers: csrfHeaders(),
        body: JSON.stringify(data),
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            closeModal();
            showToast(res.message, 'success');
            setTimeout(() => location.reload(), 1200);
        } else {
            showToast(res.message, 'error');
        }
    })
    .catch(() => showToast('Something went wrong.', 'error'));
}

/* ── Appointment reject modal ─────────────────────────────── */
function rejectAppointment(id) {
    openModal('Reject Appointment', `
        <form id="rejectForm" onsubmit="submitReject(event, ${id})">
            <div class="form-group">
                <label>Reason (optional)</label>
                <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Let the customer know why..."></textarea>
            </div>
            <button type="submit" class="btn btn-danger" style="width:100%">Confirm Rejection</button>
        </form>
    `);
}

function submitReject(e, id) {
    e.preventDefault();
    const data = {
        action: 'reject',
        id: id,
        rejection_reason: e.target.rejection_reason.value,
    };

    fetch(getApiUrl('appointments.php'), {
        method: 'POST',
        headers: csrfHeaders(),
        body: JSON.stringify(data),
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            closeModal();
            showToast(res.message, 'success');
            setTimeout(() => location.reload(), 1200);
        } else {
            showToast(res.message, 'error');
        }
    })
    .catch(() => showToast('Something went wrong.', 'error'));
}

/* ── Delete confirmation ──────────────────────────────────── */
function confirmDelete(message, callback) {
    openModal('Confirm Delete', `
        <p style="margin-bottom:1.5rem;color:#6B7280;">${message}</p>
        <div class="btn-group">
            <button class="btn btn-danger" onclick="(${callback})(); closeModal();">Delete</button>
            <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
        </div>
    `);
}

/* ── API URL helper ───────────────────────────────────────── */
function getApiUrl(endpoint) {
    const base = document.querySelector('meta[name="base-url"]')?.content || '';
    return base + '/api/' + endpoint;
}

/* ── Form validation helper ───────────────────────────────── */
function validateForm(form) {
    let valid = true;
    form.querySelectorAll('[required]').forEach(field => {
        const errEl = field.parentElement.querySelector('.form-error');
        if (!field.value.trim()) {
            valid = false;
            if (!errEl) {
                const err = document.createElement('div');
                err.className = 'form-error';
                err.textContent = 'This field is required.';
                field.parentElement.appendChild(err);
            }
            field.style.borderColor = '#EF4444';
        } else {
            if (errEl) errEl.remove();
            field.style.borderColor = '';
        }
    });
    return valid;
}

/* ── Delete staff member ──────────────────────────────────── */
function deleteStaff(id, name) {
    openModal('Delete Staff Member', `
        <p style="margin-bottom:1.5rem;color:#6B7280;">Are you sure you want to delete <strong>${name}</strong>? This action cannot be undone.</p>
        <div class="btn-group">
            <button class="btn btn-danger" id="confirmDeleteStaff">Delete</button>
            <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
        </div>
    `);
    document.getElementById('confirmDeleteStaff').addEventListener('click', () => {
        fetch(getApiUrl('staff.php'), {
            method: 'POST',
            headers: csrfHeaders(),
            body: JSON.stringify({ action: 'delete', id: id }),
        })
        .then(r => r.json())
        .then(res => {
            closeModal();
            if (res.success) {
                showToast(res.message, 'success');
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast(res.message, 'error');
            }
        });
    });
}

/* ── Delete service ─────────────────────────────────────────── */
function deleteService(id, name) {
    openModal('Delete Service', `
        <p style="margin-bottom:1.5rem;color:#6B7280;">Are you sure you want to delete <strong>${name}</strong>?</p>
        <div class="btn-group">
            <button class="btn btn-danger" id="confirmDeleteService">Delete</button>
            <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
        </div>
    `);
    document.getElementById('confirmDeleteService').addEventListener('click', () => {
        fetch(getApiUrl('services.php'), {
            method: 'POST',
            headers: csrfHeaders(),
            body: JSON.stringify({ action: 'delete', id: id }),
        })
        .then(r => r.json())
        .then(res => {
            closeModal();
            if (res.success) {
                showToast(res.message, 'success');
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast(res.message, 'error');
            }
        });
    });
}
