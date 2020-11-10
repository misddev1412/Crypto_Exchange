<?php

namespace App\Http\Controllers\PersonalAccessTokens;

use App\Http\Controllers\Controller;
use App\Http\Requests\PersonalAccessToken\PersonalAccessTokensRequest;

class PersonalAccessTokensController extends Controller
{
    public function index()
    {
        $data['title'] = __('Personal Access Tokens');
        $data['tokens'] = auth()->user()->tokens;

        return view('personal_access_token.index', $data);
    }

    public function create()
    {
        $data['title'] = __('Create Personal Access Token');

        if( session()->has('token') && !empty( session()->get('token') ) ) {
            $data['token'] = session()->get('token');
        }

        return view('personal_access_token.create', $data);
    }

    public function store(PersonalAccessTokensRequest $request)
    {
        $token = auth()->user()
            ->createToken($request->token_name);

        if (!empty($token)) {
            return redirect()
                ->route('personal-access-tokens.create')
                ->with([
                    RESPONSE_TYPE_SUCCESS => __('The personal access token has been created successfully.'),
                    'token' => $token,
                ]);
        }

        return redirect()
            ->back()
            ->with(RESPONSE_TYPE_ERROR, __('Failed to create the personal access token.'));
    }

    public function destroy($id)
    {
        if (auth()->user()->tokens()->where('id', $id)->delete()) {
            return redirect()
                ->route('personal-access-tokens.index')
                ->with(RESPONSE_TYPE_SUCCESS, __('The personal access token has been deleted successfully.'));
        }

        return redirect()
            ->back()
            ->with(RESPONSE_TYPE_ERROR, __('Failed to delete the personal access token.'));
    }
}
