@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const token = localStorage.getItem('jwt_token');
        if (!token) {
            window.location.href = '/';
            return;
        }
    });
</script>
@endpush 