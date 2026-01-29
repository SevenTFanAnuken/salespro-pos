<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    // This matches: Route::get('/login', [AuthController::class, 'showLoginForm'])
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // This matches: Route::get('/register', [AuthController::class, 'showRegistrationForm'])
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // This matches: Route::post('/login', [AuthController::class, 'login'])
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if the "remember" checkbox was ticked
        $remember = $request->has('remember');

        // Pass $remember as the second argument
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // This matches: Route::post('/register', [AuthController::class, 'register'])
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        // Redirect to login page instead of the dashboard
        return redirect()->route('login')->with('success', 'Registration successful! Please log in to continue.');
    }

    // This matches: Route::post('/logout', [AuthController::class, 'logout'])
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function userControl()
    {
        $users = User::all();

        // This line will stop the app and show you the data. 
        // If you don't see a black screen with data when you refresh, 
        // your route is hitting the wrong place!
        // dd($users); 

        return view('dashboard.user', compact('users'));
    }

    /**
     * Admin: Finance Report Page
     */
    public function financeReport()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        // Demo Finance Data
        $data = [
            'revenue'  => 125430,
            'expenses' => 45200,
            'profit'   => 125430 - 45200
        ];

        return view('dashboard.admin.finance', $data);
    }

    public function changeRole(Request $request, $id)
    {
        // 1. Security Check
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // 2. Find and Update
        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return back()->with('success', 'User role updated to ' . $request->role);
    }

    /**
     * Display the Point of Sale interface
     */
    public function showPointOfSale()
    {
        // Part A: List products & promotions
        // In the future, these will come from your Database
        $products = [
            ['id' => 1, 'name' => 'Premium Coffee Bean', 'price' => 15.00, 'promotion' => '10% Discount'],
            ['id' => 2, 'name' => 'Milk Carton 1L', 'price' => 3.50, 'promotion' => 'Buy 2 Get 1 Free'],
            ['id' => 3, 'name' => 'Organic Sugar', 'price' => 5.00, 'promotion' => null],
        ];

        return view('dashboard.point_of_sale', compact('products'));
    }

    /**
     * Part B: Add Sale (Save to Database)
     */
    public function storePointOfSaleSale(Request $request)
    {
        // This is where you would save the transaction details
        return back()->with('success', 'The Sale has been recorded in the system.');
    }
}
