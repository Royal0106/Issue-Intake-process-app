<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Issue #{{ $issue->id }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

    <div class="container">
        <h1 class="mb-4">Issue #{{ $issue->id }} Details</h1>

        <!-- Back Button -->
        <a href="{{ route('index') }}" class="btn btn-secondary mb-3">← Back to Issues</a>

        <!-- Issue Card -->
        <div class="card @if($issue->escalated) border-danger @endif">
            <div class="card-header">
                <strong>{{ $issue->title }}</strong>
                @if($issue->escalated)
                    <span class="badge bg-danger float-end">Escalated</span>
                @endif
            </div>
            <div class="card-body">
                <p><strong>Description:</strong><br>{{ $issue->description }}</p>
                <p><strong>Priority:</strong> {{ $issue->priority }}</p>
                <p><strong>Category:</strong> {{ $issue->category->name }}</p>
                <p><strong>Status:</strong> {{ $issue->status }}</p>
                <hr>
                <p><strong>AI/Generated Summary:</strong><br>{{ $issue->summary ?? 'N/A' }}</p>
                <p><strong>Suggested Next Action:</strong><br>{{ $issue->next_action ?? 'N/A' }}</p>
                <hr>
                <p class="text-muted">Created at: {{ $issue->created_at->format('Y-m-d H:i') }}</p>
                <p class="text-muted">Last updated: {{ $issue->updated_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>

        <!-- Edit Button -->
        <a href="{{ route('edit', $issue->id) }}" class="btn btn-primary mt-3">Edit Issue</a>
    </div>

</body>
</html>