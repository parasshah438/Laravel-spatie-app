# Admin Route Troubleshooting Guide

## Issue: `/admin` shows 404 error

### Quick Test Routes

Add these temporary test routes to `routes/web.php` to troubleshoot:

```php
// Test admin route access
Route::get('/test-admin', function () {
    return 'Admin route system is working';
});

Route::get('/test-admin-login', function () {
    return view('admin.auth.login');
});

Route::get('/test-admin-check', function () {
    if (auth('admin')->check()) {
        return 'Admin is logged in: ' . auth('admin')->user()->name;
    }
    return 'Admin not logged in';
});
```

### Troubleshooting Steps:

1. **Clear Route Cache**
   ```bash
   php artisan route:clear
   php artisan config:clear
   php artisan cache:clear
   ```

2. **Check if routes are registered**
   ```bash
   php artisan route:list | findstr admin
   ```

3. **Check middleware registration**
   - Verify `bootstrap/app.php` has admin middleware aliases
   - Ensure middleware files exist

4. **Test individual components**
   - Visit `/test-admin` - should show "Admin route system is working"
   - Visit `/test-admin-login` - should show admin login page
   - Visit `/admin/login` - should show admin login page

### Expected Behavior:

- `/admin` should redirect to `/admin/login` if not logged in
- `/admin` should redirect to `/admin/dashboard` if logged in
- `/admin/login` should show the login form

### Common Solutions:

1. **Route not registered**: Add the index route we created
2. **Middleware not working**: Check middleware aliases in bootstrap/app.php
3. **View not found**: Ensure admin login view exists
4. **Cache issues**: Clear all Laravel caches

### Files to Check:

1. `routes/admin.php` - Admin routes
2. `app/Http/Middleware/AdminGuestMiddleware.php` - Guest middleware
3. `app/Http/Controllers/Admin/Auth/LoginController.php` - Login controller
4. `resources/views/admin/auth/login.blade.php` - Login view
5. `bootstrap/app.php` - Middleware registration
