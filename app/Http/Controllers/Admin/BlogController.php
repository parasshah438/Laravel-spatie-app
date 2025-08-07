<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct()
    {
        // Apply permission middleware to all methods
        $this->middleware('permission:view blogs,admin')->only(['index', 'show']);
        $this->middleware('permission:create blogs,admin')->only(['create', 'store']);
        $this->middleware('permission:edit blogs,admin')->only(['edit', 'update']);
        $this->middleware('permission:delete blogs,admin')->only(['destroy']);
        $this->middleware('permission:publish blogs,admin')->only(['publish', 'unpublish']);
    }

    /**
     * Display a listing of blogs.
     */
    public function index()
    {
        $blogs = Blog::latest()->paginate(10);
        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new blog.
     */
    public function create()
    {
        return view('admin.blogs.create');
    }

    /**
     * Store a newly created blog.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
        ]);

        $blog = Blog::create([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
            'author_id' => auth('admin')->id(),
        ]);

        // Log activity
        activity()
            ->causedBy(auth('admin')->user())
            ->performedOn($blog)
            ->log('Created blog: ' . $blog->title);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog created successfully.');
    }

    /**
     * Display the specified blog.
     */
    public function show(Blog $blog)
    {
        return view('admin.blogs.show', compact('blog'));
    }

    /**
     * Show the form for editing the blog.
     */
    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified blog.
     */
    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
        ]);

        $blog->update([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
        ]);

        // Log activity
        activity()
            ->causedBy(auth('admin')->user())
            ->performedOn($blog)
            ->log('Updated blog: ' . $blog->title);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog updated successfully.');
    }

    /**
     * Remove the specified blog.
     */
    public function destroy(Blog $blog)
    {
        $title = $blog->title;
        $blog->delete();

        // Log activity
        activity()
            ->causedBy(auth('admin')->user())
            ->log('Deleted blog: ' . $title);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog deleted successfully.');
    }

    /**
     * Publish a blog (custom permission example)
     */
    public function publish(Blog $blog)
    {
        $blog->update(['status' => 'published']);

        // Log activity
        activity()
            ->causedBy(auth('admin')->user())
            ->performedOn($blog)
            ->log('Published blog: ' . $blog->title);

        return back()->with('success', 'Blog published successfully.');
    }

    /**
     * Unpublish a blog
     */
    public function unpublish(Blog $blog)
    {
        $blog->update(['status' => 'draft']);

        // Log activity
        activity()
            ->causedBy(auth('admin')->user())
            ->performedOn($blog)
            ->log('Unpublished blog: ' . $blog->title);

        return back()->with('success', 'Blog unpublished successfully.');
    }
}
