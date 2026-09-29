{{--
    Dialog konfirmasi kustom (pengganti confirm() bawaan browser).
    Perilaku: mencegat submit form / klik tombol yang punya data-confirm.

    Atribut pada elemen pemicu:
        data-confirm="Pesan konfirmasi"              (wajib)
        data-confirm-title="Judul"                    (opsional)
        data-confirm-ok="Ya, Bayar Sekarang"          (opsional)
        data-confirm-cancel="Kembali"                 (opsional)
        data-confirm-variant="primary" | "danger"     (opsional, default danger)

    Contoh:
        <form data-confirm="Hapus pesan ini?" ...>
        <button form="formHapus1" data-confirm="Hapus item ini?">
--}}

<div id="confirm-dialog" class="fixed inset-0 z-[100] hidden" role="alertdialog" aria-modal="true"
     aria-labelledby="confirm-dialog-title" aria-describedby="confirm-dialog-message">
    <div data-confirm-backdrop class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>

    <div class="absolute inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div data-confirm-panel
             class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl ring-1 ring-slate-900/5 p-6 sm:p-7 opacity-0 scale-95 transition-all duration-200">

            <span data-confirm-icon-danger
                  class="flex items-center justify-center w-12 h-12 rounded-full bg-red-50 text-red-600 ring-8 ring-red-50/60 mb-4">
                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
            </span>
            <span data-confirm-icon-primary
                  class="hidden items-center justify-center w-12 h-12 rounded-full bg-amber-50 text-amber-600 ring-8 ring-amber-50/60 mb-4">
                <i class="fa-solid fa-circle-question text-lg"></i>
            </span>

            <h2 id="confirm-dialog-title" data-confirm-title class="text-lg font-bold text-slate-900 tracking-tight">Konfirmasi</h2>
            <p id="confirm-dialog-message" data-confirm-message class="mt-2 text-sm leading-relaxed text-slate-500">
                Anda yakin ingin melanjutkan tindakan ini?
            </p>

            <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" data-confirm-cancel
                        class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2 transition">
                    Batal
                </button>
                <button type="button" data-confirm-ok
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-bold text-white bg-red-600 hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 transition disabled:opacity-70 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-check text-xs"></i><span>Ya, Lanjutkan</span>
                </button>
            </div>
        </div>
    </div>
</div>

@once
<script>
(function () {
    const dialog = document.getElementById('confirm-dialog');
    if (!dialog) return;

    const backdrop = dialog.querySelector('[data-confirm-backdrop]');
    const panel = dialog.querySelector('[data-confirm-panel]');
    const titleEl = dialog.querySelector('[data-confirm-title]');
    const messageEl = dialog.querySelector('[data-confirm-message]');
    const okBtn = dialog.querySelector('[data-confirm-ok]');
    const cancelBtn = dialog.querySelector('[data-confirm-cancel]');
    const iconDanger = dialog.querySelector('[data-confirm-icon-danger]');
    const iconPrimary = dialog.querySelector('[data-confirm-icon-primary]');

    const OK_DANGER = 'inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-bold text-white bg-red-600 hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2 transition disabled:opacity-70 disabled:cursor-not-allowed';
    const OK_PRIMARY = 'inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-bold text-slate-900 bg-amber-500 hover:bg-amber-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500 focus-visible:ring-offset-2 transition disabled:opacity-70 disabled:cursor-not-allowed';

    let pending = null;        // { form, submitter }
    let lastTrigger = null;
    let bypass = false;       // true saat kita resubmit sendiri

    function readOptions(el) {
        return {
            title: el.dataset.confirmTitle || 'Konfirmasi',
            message: el.dataset.confirm || 'Anda yakin ingin melanjutkan tindakan ini?',
            ok: el.dataset.confirmOk || 'Ya, Lanjutkan',
            cancel: el.dataset.confirmCancel || 'Batal',
            variant: el.dataset.confirmVariant === 'primary' ? 'primary' : 'danger'
        };
    }

    function open(options, target) {
        pending = target;
        lastTrigger = target && target.submitter ? target.submitter : null;

        titleEl.textContent = options.title;
        messageEl.textContent = options.message;
        okBtn.disabled = false;
        okBtn.querySelector('span').textContent = options.ok;
        cancelBtn.textContent = options.cancel;
        okBtn.className = options.variant === 'primary' ? OK_PRIMARY : OK_DANGER;

        const danger = options.variant === 'danger';
        iconDanger.classList.toggle('hidden', !danger);
        iconDanger.classList.toggle('flex', danger);
        iconPrimary.classList.toggle('hidden', danger);
        iconPrimary.classList.toggle('flex', !danger);

        dialog.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        requestAnimationFrame(function () {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('opacity-0', 'scale-95');
            okBtn.focus();
        });
    }

    function close() {
        backdrop.classList.add('opacity-0');
        panel.classList.add('opacity-0', 'scale-95');
        document.body.classList.remove('overflow-hidden');
        pending = null;

        window.setTimeout(function () {
            dialog.classList.add('hidden');
            if (lastTrigger && document.contains(lastTrigger)) lastTrigger.focus();
            lastTrigger = null;
        }, 180);
    }

    function accept() {
        if (!pending) return close();
        const target = pending;

        okBtn.disabled = true;
        okBtn.querySelector('span').textContent = 'Memproses...';
        bypass = true;

        // requestSubmit()/klik memicu event submit secara sinkron, jadi penanda
        // masih aktif saat listener capture berjalan. Dicabut di tick berikutnya
        // agar submit berikutnya (mis. submit ulang setelah validasi gagal)
        // tetap membuka dialog, bukan lolos tanpa konfirmasi.
        window.setTimeout(function () { bypass = false; }, 0);

        if (target.submitter && target.submitter.form === target.form) {
            target.form.requestSubmit(target.submitter);
        } else {
            target.form.submit();
        }
    }

    // Submit form ber atribut data-confirm (fase capture, sebelum validasi browser).
    document.addEventListener('submit', function (event) {
        if (bypass) return;
        const form = event.target;
        if (!form || !form.hasAttribute || !form.hasAttribute('data-confirm')) return;
        event.preventDefault();
        open(readOptions(form), { form: form, submitter: event.submitter || null });
    }, true);

    // Tombol pemicu di luar form, memakai atribut form="...".
    document.addEventListener('click', function (event) {
        const trigger = event.target.closest('[data-confirm][form]');
        if (!trigger || trigger.tagName === 'FORM') return;
        const form = document.getElementById(trigger.getAttribute('form'));
        if (!form) return;
        event.preventDefault();
        open(readOptions(trigger), { form: form, submitter: trigger });
    });

    okBtn.addEventListener('click', accept);
    cancelBtn.addEventListener('click', close);
    backdrop.addEventListener('click', close);

    document.addEventListener('keydown', function (event) {
        if (dialog.classList.contains('hidden')) return;

        if (event.key === 'Escape') {
            event.preventDefault();
            return close();
        }

        // Jebak fokus di dalam dialog.
        if (event.key === 'Tab') {
            const focusables = dialog.querySelectorAll('button:not([disabled])');
            if (!focusables.length) return;
            const first = focusables[0];
            const last = focusables[focusables.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        }
    });
})();
</script>
@endonce
