
<!-- Kansas Map -->
<h3 class="mb-3">Alert Map</h3>
@include('partials.kansas_map')

<!-- Alert data for JS -->
<script>
    window.kansasAlerts = @json($alerts);
</script>
<script src="{{ asset('js/alert-map.js') }}"></script>
