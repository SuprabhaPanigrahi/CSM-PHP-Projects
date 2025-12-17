<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class FormController extends Controller
{
    // Step 1: Personal Information
    public function step1(Request $request)
    {
        $formData = $request->session()->get('form_data', []);
        $cookieData = json_decode($request->cookie('form_cookie', '{}'), true);
        
        return view('step1', [
            'formData' => array_merge($cookieData, $formData)
        ]);
    }

    public function postStep1(Request $request)
    {
        $request->validate([
            'full_name' => 'required|min:3',
            'age' => 'required|numeric|min:1'
        ]);

        // Store in session
        $request->session()->put('form_data.full_name', $request->full_name);
        $request->session()->put('form_data.age', $request->age);

        // Store in cookie (will be available even if session expires)
        $cookieData = [
            'full_name' => $request->full_name,
            'age' => $request->age
        ];
        
        $cookie = Cookie::make('form_cookie', json_encode($cookieData), 60); // 1 hour

        return redirect()->route('form.step2')->withCookie($cookie);
    }

    // Step 2: Contact Information
    public function step2(Request $request)
    {
        $formData = $request->session()->get('form_data', []);
        
        // Check if user completed step1
        if (!isset($formData['full_name'])) {
            return redirect()->route('form.step1');
        }

        return view('step2', ['formData' => $formData]);
    }

    public function postStep2(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required'
        ]);

        // Store in session
        $request->session()->put('form_data.email', $request->email);
        $request->session()->put('form_data.phone', $request->phone);

        // Update cookie with step2 data
        $existingCookie = json_decode($request->cookie('form_cookie', '{}'), true);
        $cookieData = array_merge($existingCookie, [
            'email' => $request->email,
            'phone' => $request->phone
        ]);
        
        $cookie = Cookie::make('form_cookie', json_encode($cookieData), 60);

        return redirect()->route('form.step3')->withCookie($cookie);
    }

    // Step 3: Summary
    public function step3(Request $request)
    {
        $formData = $request->session()->get('form_data', []);
        $cookieData = json_decode($request->cookie('form_cookie', '{}'), true);
        
        // Merge session and cookie data
        $mergedData = array_merge($cookieData, $formData);
        
        // Check if user completed step2
        if (!isset($mergedData['email'])) {
            return redirect()->route('form.step1');
        }

        return view('step3', ['formData' => $mergedData]);
    }

    // Final Submission
    public function submit(Request $request)
    {
        // Get all data from session
        $formData = $request->session()->get('form_data', []);
        
        // Here you would typically save to database
        // For this example, we'll just display and clear
        
        // Clear session and cookie
        $request->session()->forget('form_data');
        
        return redirect()->route('form.step1')
            ->with('success', 'Form submitted successfully!')
            ->with('submitted_data', $formData)
            ->withCookie(Cookie::forget('form_cookie'));
    }

    // Clear all data
    public function clear(Request $request)
    {
        $request->session()->forget('form_data');
        
        return redirect()->route('form.step1')
            ->with('info', 'All data cleared!')
            ->withCookie(Cookie::forget('form_cookie'));
    }
}