<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Issue #{{ $issue->id }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

<div class="container">
    <h1 class="mb-4">Edit Issue #{{ $issue->id }}</h1>

    <!-- Back Button -->
    <a href="{{ route('show', $issue->id) }}" class="btn btn-secondary mb-3">← Back to Issue Details</a>

    <!-- Edit Form -->
    <div class="card">
        <div class="card-header">Update Issue</div>
        <div class="card-body">
            <form method="POST" action="{{ route('update', $issue->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $issue->title) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4" required>{{ old('description', $issue->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Priority</label>
                    <select name="priority" class="form-select">
                        <option value="low" {{ $issue->priority === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ $issue->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ $issue->priority === 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>

                <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ isset($issue) && $issue->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="open" {{ $issue->status === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ $issue->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="closed" {{ $issue->status === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Update Issue</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>