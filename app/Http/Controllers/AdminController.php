<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    public function index()
    {
        $authUser = Auth::user();

        // Only fetch dashboard data for admin
        $dashboardData = [
            'users' => User::count(),
            'roles' => class_exists(Role::class) ? Role::count() : 0,
            'permissions' => class_exists(Permission::class) ? Permission::count() : 0,
        ];

        if ($authUser?->usertype === 'admin') {
            return Inertia::render('Home/admin', array_merge(['auth_user' => $authUser], $dashboardData));
        }

        return Inertia::render('Home/index', ['auth_user' => $authUser]);
    }
}