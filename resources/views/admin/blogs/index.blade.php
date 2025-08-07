@extends('admin.layouts.app')

@section('title', 'Blogs Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Blogs Management</h1>
    @can('create blogs', 'admin')
        <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Blog
        </a>
    @endcan
</div>

<div class="card">
    <div class="card-body">
        @if($blogs->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($blogs as $blog)
                            <tr>
                                <td>{{ $blog->title }}</td>
                                <td>{{ $blog->author->name ?? 'Unknown' }}</td>
                                <td>
                                    <span class="badge bg-{{ $blog->status === 'published' ? 'success' : 'warning' }}">
                                        {{ ucfirst($blog->status) }}
                                    </span>
                                </td>
                                <td>{{ $blog->created_at->format('M d, Y') }}</td>
                                <td>
                                    @can('view blogs', 'admin')
                                        <a href="{{ route('admin.blogs.show', $blog) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endcan
                                    
                                    @can('edit blogs', 'admin')
                                        <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    
                                    @can('publish blogs', 'admin')
                                        @if($blog->status === 'draft')
                                            <form method="POST" action="{{ route('admin.blogs.publish', $blog) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i> Publish
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.blogs.unpublish', $blog) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-secondary">
                                                    <i class="fas fa-pause"></i> Unpublish
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                    
                                    @can('delete blogs', 'admin')
                                        <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $blogs->links() }}
        @else
            <div class="text-center py-5">
                <i class="fas fa-blog fa-3x text-muted mb-3"></i>
                <p class="text-muted">No blogs found.</p>
                @can('create blogs', 'admin')
                    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
                        Create Your First Blog
                    </a>
                @endcan
            </div>
        @endif
    </div>
</div>
@endsection
