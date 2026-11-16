<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\LoginController as BaseLoginController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoginController extends BaseLoginController
{
    public function logout(Request $request): RedirectResponse
    {
        $user = $this->guard()->user();

        if ($user) {
            try {
                activity()
                    ->performedOn($user)
                    ->causedBy($user)
                    ->event('logout')
                    ->log('logout');
            } catch (\Throwable $e) {
                \Log::warning('Activity log logout failed', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('twill.login');
    }
}
