@php
    $existingSns = collect(request('serialNumbers'))
        ->map(fn($s) => trim($s))
        ->filter()
        ->unique()
        ->values()
        ->all();
@endphp
<div class="col-12">
    <div class="border rounded-3 bg-warning-subtle p-2 mt-2">
        <div class="d-flex align-items-center">
            <i class="ri-barcode-line fs-16 me-2 text-warning"></i>
            <h6 class="mb-0 fw-bold" data-bs-toggle="collapse" data-bs-target="#multiSnPanel" style="cursor:pointer">
                Multi Serial Number Search</h6>
            <div class="ms-auto d-flex align-items-center gap-2">
                <small class="text-muted" id="multiSnCount">0 SN</small>
                <button type="button" class="btn btn-sm btn-link p-0 text-muted"
                    data-bs-toggle="collapse" data-bs-target="#multiSnPanel">
                    <i class="ri-arrow-down-s-line fs-18"></i>
                </button>
            </div>
        </div>
        <div class="collapse show mt-2" id="multiSnPanel">
            <textarea class="form-control form-control-sm font-monospace" rows="5"
                data-multi-sn-textarea
                placeholder="Paste serial numbers here (one per line, max ±500)&#10;Example:&#10;SN-00123&#10;SN-00124">{{ implode("\n", $existingSns) }}</textarea>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <small class="text-muted">Filters rows whose serial number matches any pasted SN.</small>
                <button type="submit" class="btn btn-sm btn-success">
                    <i class="ri-search-line me-1"></i> Search SNs
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    function initMultiSn() {
        var ta = document.querySelector('textarea[data-multi-sn-textarea]');
        if (!ta) return;
        var countEl = document.getElementById('multiSnCount');
        function count() {
            var n = ta.value.split(/\r?\n/).map(function (s) { return s.trim(); }).filter(Boolean).length;
            if (countEl) countEl.textContent = n + ' SN';
        }
        ta.addEventListener('input', count);
        count();
    }
    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form || form.tagName !== 'FORM') return;
        var ta = form.querySelector('textarea[data-multi-sn-textarea]');
        if (!ta) return; // form tanpa panel: tidak disentuh
        form.querySelectorAll('input[name="serialNumbers[]"]').forEach(function (el) { el.remove(); });
        var sns = ta.value.split(/\r?\n/).map(function (s) { return s.trim(); }).filter(Boolean);
        sns.forEach(function (sn) {
            var h = document.createElement('input');
            h.type = 'hidden'; h.name = 'serialNumbers[]'; h.value = sn;
            form.appendChild(h);
        });
        // tanpa preventDefault — form submit normal bersama filter lain
    });
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMultiSn);
    } else {
        initMultiSn();
    }
})();
</script>
