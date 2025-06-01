<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\WebLoginRequest;
use App\Repositories\Auth\AuthRepository;

class AuthController extends Controller
{
    /** @var string */
    public string $LANG_PATH = 'web_auth.';

    /** @var AuthRepository */
    public AuthRepository $repo;

    /**
     * Constructor class
     * @param AuthRepository $repo
     */
    public function __construct(AuthRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * View login page
     */
    public function index()
    {
        $isLogin = $this->repo->isLogin();
        if ($isLogin) {
            return redirect()->route('dashboard.index');
        }

        $dataView = [
            'LANG_PATH' => $this->LANG_PATH,
        ];

        return view('admin.auth', $dataView);
    }

    /**
     * Post login logic request
     * @param WebLoginRequest $request
     */
    public function postLogin(WebLoginRequest $request)
    {
        $doLogin = $this->repo->doLogin($request);

        if ($doLogin) {
            /** if success, redirect to index for go to the dashboard page */
            return $this->index();
        }

        /** if failed, redirect back with error message */
        return redirect()
            ->route('login')
            ->withErrors(['attempt' => __('auth.failed')]);
    }

    /**
     * Logout logic
     */
    public function logout()
    {
        $this->repo->logout();
        return redirect()->route('login');
    }
}
