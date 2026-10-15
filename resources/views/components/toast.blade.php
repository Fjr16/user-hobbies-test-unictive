@props(['icon' => '', 'status', 'message'])

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast app-toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="{{ $icon }}"></i>
            <strong class="me-auto">{{ $status }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">{{ $message }}</div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.app-toast').forEach(el => {
            bootstrap.Toast.getOrCreateInstance(el, { delay: 4000 }).show();
        });
    });
</script>

