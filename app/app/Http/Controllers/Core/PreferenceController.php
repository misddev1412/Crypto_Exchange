<?php

    namespace App\Http\Controllers\Core;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Core\PreferenceRequest;
    use App\Models\Coin\CoinPair;
    use App\Models\Core\Language;
    use App\Models\Core\UserPreference;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Cookie;

    class PreferenceController extends Controller
    {
        public function index()
        {
            $data['user'] = Auth::user();
            $data['preference'] = UserPreference::firstOrCreate(
                ['user_id' => Auth::id()],
                [
                    'default_language' => config('app.locale'),
                    'default_coin_pair' => null
                ]
            );
            $data['title'] = __("My Preference");
            return view('core.profile.preference.index', $data);
        }

        public function edit()
        {
            $data['user'] = Auth::user()->load('preference');
            $data['languages'] = Language::active()->pluck('short_code', 'short_code')->toArray();
            $data['coinPairs'] = CoinPair::active()->pluck('name', 'name')->toArray();
            $data['title'] = __("Change Preference");

            return view('core.profile.preference.edit', $data);
        }

        public function update(PreferenceRequest $request)
        {
            $params = $request->only('default_language', 'default_coin_pair');

            if (auth()->user()->preference->update($params)) {
                Cookie::queue(Cookie::forever('coinPair', $request->default_coin_pair));

                return redirect()->route('preference.edit')->with(RESPONSE_TYPE_SUCCESS, __("Preference has been updated successfully."));
            }

            return redirect()->back()->withInput()->with(RESPONSE_TYPE_ERROR, __("Failed to update preference."));
        }
    }
