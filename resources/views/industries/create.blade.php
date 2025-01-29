<div class="container">
    <h1>Create Industry</h1>
    <form action="{{ route('industries.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="industry">Industry Name</label>
            <input type="text" name="industry" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Create</button>
    </form>
</div>