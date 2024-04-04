<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    //
    public function changeLanguage(Request $request)
    {
        $locale = $request->input('locale');

        if (in_array($locale, config('app.supported_languages'))) {
            App::setLocale($locale);
            session(['locale' => $locale]); // Optionally, store the user's preference in the session.
        }

        return redirect()->back();
    }
}
