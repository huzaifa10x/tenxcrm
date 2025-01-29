<div class="container">
    <h1>Edit Industry</h1>
    <form action="{{ route('industries.update', $industry->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="industry">Industry Name</label>
            <input type="text" name="industry" class="form-control" value="{{ old('industry', $industry->industry) }}" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
</div>