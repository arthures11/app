<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function customLogin(Request $request){

        $input = $request->all();
//        $this->validate($request, [
//            'email' => 'required|email',
//            'password' => 'required',
//        ]);


        if(Auth::attempt(['email'=> $input['email'], 'password'=> $input['password']])){
            if(Auth::user()->type == 0){
                return redirect()->route('dashboard');
            }
            elseif (Auth::user()->type == 1) {
                return redirect()->route('dashboard');
            }
        }
        else{
            return redirect()->route('login')->with('error', "Wrong credentials");
        }

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
