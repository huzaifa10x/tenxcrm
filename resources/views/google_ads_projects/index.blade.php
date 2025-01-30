<x-app-layout>
    <div class="container">
        <h1>Google Ads Projects</h1>
        <a href="{{ route('google-ads-projects.create') }}" class="btn btn-primary">Create New Project</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Sales Manager</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->category }}</td>
                        <td>{{ $project->status }}</td>
                        <td>{{ $project->salesManager->name }}</td>
                        <td>
                            <a href="{{ route('google-ads-projects.show', $project->id) }}" class="btn btn-info">View</a>
                            <a href="{{ route('google-ads-projects.edit', $project->id) }}"
                                class="btn btn-warning">Edit</a>

                            <!-- Delete Form -->
                            <form action="{{ route('google-ads-projects.destroy', $project->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this project?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
