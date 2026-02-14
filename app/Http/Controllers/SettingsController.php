<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SettingsController extends Controller
{
    private const SETTING_KEYS = [
        'company_name',
        'street',
        'city',
        'phone',
        'email',
        'vat_number',
        'iban',
        'signature_city',
        'vat_rate',
    ];

    public function index()
    {
        $settings = [];
        foreach (self::SETTING_KEYS as $key) {
            $settings[$key] = Setting::get($key, '');
        }

        $settings['logo_url'] = Setting::get('logo_path')
            ? Storage::url(Setting::get('logo_path'))
            : null;

        return Inertia::render('Settings/Index', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'vat_number' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'signature_city' => 'required|string|max:255',
            'vat_rate' => 'nullable|numeric|min:0|max:100',
            'logo' => 'nullable|image|max:2048',
        ]);

        foreach (self::SETTING_KEYS as $key) {
            if (array_key_exists($key, $validated)) {
                Setting::set($key, $validated[$key]);
            }
        }

        if ($request->hasFile('logo')) {
            $oldPath = Setting::get('logo_path');
            if ($oldPath) {
                Storage::delete($oldPath);
            }

            $path = $request->file('logo')->store('public/settings');
            Setting::set('logo_path', $path);
        }

        return back();
    }
}
