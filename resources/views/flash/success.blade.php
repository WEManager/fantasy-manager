@if (request()->session()->exists('success'))
    <div class="alert alert-success" role="alert">
        {{ request()->session()->get('success') }}
    </div>
@endif