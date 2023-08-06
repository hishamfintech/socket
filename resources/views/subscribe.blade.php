@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
<form method="POST" action="{{ route('subscribe') }}">
    @csrf
    <div>
        <label for="topic">Topic URL:</label>
        <input type="url" name="topic" id="topic" required>
    </div>
    <div>
        <label for="callback">Callback URL:</label>
        <input type="url" name="callback" id="callback" required>
    </div>
    <button type="submit">Subscribe</button>
</form>
