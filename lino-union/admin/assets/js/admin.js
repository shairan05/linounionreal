/* ================================================
   LINO UNION – Admin Panel JavaScript
   ================================================ */

'use strict';

document.addEventListener('DOMContentLoaded', () => {
    // Confirm destructive actions
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', (e) => {
            if (!confirm(el.getAttribute('data-confirm') || 'Are you sure?')) {
                e.preventDefault();
            }
        });
    });

    // Auto-dismiss alerts after 5 seconds
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    console.log('%c LINO UNION Admin ', 'background: #1a1a1a; color: #c9a96e; font-size: 14px; font-weight: bold; padding: 6px 10px; border-radius: 4px;');
});
