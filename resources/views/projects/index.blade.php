<h1>Projects</h1>
<a href="{{ route('projects.create') }}">Create New Project</a>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

<table border="1">
    <thead>
        <tr>
            <th>#</th>
            <th>Category</th>
            <th>Status</th>
            <th>Sales Manager</th>
            <th>Project Type</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($projects as $project)
            <tr>
                <td>{{ $project->id }}</td>
                <td>{{ $project->category }}</td>
                <td>{{ $project->status }}</td>
                <td>{{ $project->sales_manager_id }}</td>
                <td>{{ $project->project_type }}</td>
                <td>
                    <a href="{{ route('projects.show', $project->id) }}">View</a> |
                    <a href="{{ route('projects.edit', $project->id) }}">Edit</a> |
                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>