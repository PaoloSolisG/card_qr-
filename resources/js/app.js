/**
 * Small progressive enhancements shared by the card and the admin panel.
 * Everything is driven by data-* attributes so views stay declarative.
 */

// Open / close native <dialog> elements.
document.addEventListener('click', (event) => {
    const opener = event.target.closest('[data-dialog-open]');

    if (opener) {
        event.preventDefault();
        document.getElementById(opener.dataset.dialogOpen)?.showModal();

        return;
    }

    const closer = event.target.closest('[data-dialog-close]');

    if (closer) {
        closer.closest('dialog')?.close();
    }
});

// Close a dialog when tapping its backdrop.
document.querySelectorAll('dialog').forEach((dialog) => {
    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) {
            dialog.close();
        }
    });
});

// Re-open a dialog after a failed submit (validation errors).
document.querySelectorAll('dialog[data-open-on-load]').forEach((dialog) => dialog.showModal());

// Mobile navigation toggle in the admin panel.
document.querySelectorAll('[data-menu-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        document.getElementById(button.dataset.menuToggle)?.classList.toggle('hidden');
    });
});

// Copy text to the clipboard with quick feedback.
document.querySelectorAll('[data-copy]').forEach((button) => {
    button.addEventListener('click', async () => {
        await navigator.clipboard?.writeText(button.dataset.copy);

        const label = button.querySelector('[data-copy-label]');

        if (label) {
            const original = label.textContent;
            label.textContent = 'Copiado';
            setTimeout(() => (label.textContent = original), 1600);
        }
    });
});

// Ask before submitting destructive forms.
document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });
});
