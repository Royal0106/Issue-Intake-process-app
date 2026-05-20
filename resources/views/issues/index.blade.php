@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Issue Intake System</h1>

    <!-- Create Issue Form -->
    <div class="issue-form">
        <h2>Create New Issue</h2>

        @if ($errors->any())
            <div class="error-messages">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('store') }}" method="POST">
            @csrf
            <label>Title:</label>
            <input type="text" name="title" value="{{ old('title', isset($issue) ? $issue->title : '') }}">

            <label>Description:</label>
            <textarea name="description">{{ old('description', isset($issue) ? $issue->description : '') }}</textarea>

            <label>Priority:</label>
            <select name="priority">
                <option value="low" {{ old('priority', isset($issue) ? $issue->priority : '')=='low'?'selected':'' }}>Low</option>
                <option value="medium" {{ old('priority', isset($issue) ? $issue->priority : '')=='medium'?'selected':'' }}>Medium</option>
                <option value="high" {{ old('priority', isset($issue) ? $issue->priority : '')=='high'?'selected':'' }}>High</option>
            </select>

            <label>Category:</label>
            <select name="category_id" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" 
                        {{ old('category_id', isset($issue) ? $issue->category_id : '') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit">Submit</button>
        </form>
    </div>


    <div class="filter-container">
        <form action="{{ route('index') }}" method="GET" class="filter-form">
            <div class="filter-group">
                <label>Status:</label>
                <select name="status">
                    <option value="">All</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Priority:</label>
                <select name="priority">
                    <option value="">All</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Category:</label>
                <select name="category_id">
                    <option value="">All</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="filter-btn">Filter</button>
        </form>
    </div>

    <!-- List All Issues -->

    <table class="issues-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Priority</th>
                <th>Category</th>
                <th>Status</th>
                <th>Summary</th>
                <th>Next Action</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
           
            @foreach($issues as $issue)
            <tr>
                <td>{{ $issue->title }}</td>
                <td>
                    @php
                        $color = 'gray';
                        if(strtolower($issue->priority)=='high') $color='red';
                        elseif(strtolower($issue->priority)=='medium') $color='orange';
                        elseif(strtolower($issue->priority)=='low') $color='green';
                    @endphp
                    <span style="color:white; background-color:{{$color}}; padding:3px 7px; border-radius:5px;">
                        {{ ucfirst($issue->priority) }}
                    </span>
                </td>
                <td>{{ $issue->category->name ?? 'N/A' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $issue->status)) }}</td>
                <td>{{ $issue->summary ?? '-' }}</td>
                <td>{{ $issue->next_action ?? '-' }}</td>
                <td style="min-width: 120px;">
                    <a href="{{ route('show', $issue->id) }}" class="action-btn view-btn">View</a>
                    <a href="{{ route('edit', $issue->id) }}" class="action-btn edit-btn">Edit</a>
                </td>
            </tr>
            @endforeach

            @if(count($issues) == 0)
            <tr>
                <td colspan="7" class="empty-wrapper">No Available Issues</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection