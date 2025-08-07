<!-- Add to admin navigation -->
@can('view blogs', 'admin')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.blogs.index') }}">
            <i class="fas fa-blog"></i>
            <span>Blogs</span>
        </a>
    </li>
@endcan

@can('view services', 'admin')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.services.index') }}">
            <i class="fas fa-cogs"></i>
            <span>Services</span>
        </a>
    </li>
@endcan

@can('view sliders', 'admin')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.sliders.index') }}">
            <i class="fas fa-images"></i>
            <span>Sliders</span>
        </a>
    </li>
@endcan
