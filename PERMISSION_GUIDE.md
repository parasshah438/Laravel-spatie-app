# Admin Permission Management Guide

This Laravel application uses Spatie Permission package for role and permission management.

## How to Add New Feature Permissions

### 1. Using the Admin Interface

1. **Login to Admin Panel**: `/admin/login`
2. **Navigate to Permissions**: Admin Panel → System Management → Permissions
3. **Create New Permission**: Click "Create Permission"
4. **Fill Permission Details**:
   - **Name**: Use format `manage feature_name` (e.g., `manage blogs`, `manage sliders`)
   - **Guard**: Select `admin`
   - **Feature**: Choose appropriate feature category (System Management, Content Management, etc.)
   - **Description**: Brief description of what this permission allows

### 2. Assign Permissions to Roles

1. **Navigate to Roles**: Admin Panel → System Management → Roles
2. **Edit Role**: Click "Edit" on the role you want to modify
3. **Select Permissions**: Check the permissions you want to assign
4. **Save Changes**: Click "Update Role"

## Available Permission Features

### System Management
- `manage roles` - Create, edit, delete roles
- `manage permissions` - Create, edit, delete permissions
- `manage admins` - Manage admin users

### Content Management
- `manage blogs` - Full blog management
- `manage services` - Service management
- `manage sliders` - Homepage slider management

### Website Management
- `manage pages` - Static page management
- `manage categories` - Category management
- `manage media` - Media library access

### Settings & Reports
- `manage settings` - Site configuration
- `view reports` - Analytics and reports

## Using Permissions in Code

### In Blade Templates
```blade
@can('manage blogs')
    <a href="{{ route('admin.blogs.index') }}">Manage Blogs</a>
@endcan
```

### In Controllers
```php
public function index()
{
    $this->authorize('manage blogs');
    // Controller logic
}
```

### In Middleware
```php
Route::group(['middleware' => ['auth:admin', 'permission:manage blogs']], function () {
    // Protected routes
});
```

## Creating New Features

When adding new features to the admin panel:

1. **Create the permission** using the admin interface
2. **Add routes** with proper middleware
3. **Create controllers** with authorization checks
4. **Update the sidebar** to include navigation links
5. **Assign permissions** to appropriate roles

## Default Roles

- **Super Admin**: Has all permissions
- **Admin**: Has most permissions except critical system management
- **Editor**: Has content management permissions only
- **Viewer**: Has read-only access

## Best Practices

1. **Use descriptive permission names** (e.g., `manage blogs` instead of `blogs`)
2. **Group related permissions** by feature
3. **Test permissions** after creating them
4. **Document new permissions** for other developers
5. **Use middleware** for route protection
6. **Use @can directives** for UI elements

## Troubleshooting

### Permission not working?
1. Clear cache: `php artisan cache:clear`
2. Check guard name (should be 'admin' for admin features)
3. Verify role has the permission assigned
4. Check middleware on routes

### Role assignment not saving?
1. Check if role exists
2. Verify permission exists
3. Clear permission cache: `php artisan permission:cache-reset`

## Database Tables

- `permissions` - Stores all permissions
- `roles` - Stores all roles
- `model_has_permissions` - User/Admin permissions
- `model_has_roles` - User/Admin roles
- `role_has_permissions` - Role permissions
