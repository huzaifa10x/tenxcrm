<div class="container">
    <h1>Industry Details</h1>
    <div class="card">
        <div class="card-header">
            <strong>{{ $industry->industry }}</strong>
        </div>
        <div class="card-body">
            <p><strong>Industry Name:</strong> {{ $industry->industry }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('industries.edit', $industry->id) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('industries.destroy', $industry->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>