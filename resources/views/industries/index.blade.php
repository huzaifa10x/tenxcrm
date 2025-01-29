<div class="container">
    <h1>Industries</h1>
    <a href="{{ route('industries.create') }}" class="btn btn-primary">Create Industry</a>
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Industry Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($industries as $industry)
                <tr>
                    <td>{{ $industry->industry }}</td>
                    <td>
                        <a href="{{ route('industries.show', $industry->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('industries.edit', $industry->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('industries.destroy', $industry->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>