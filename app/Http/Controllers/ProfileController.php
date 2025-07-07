<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Contracts\Services\UserServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Exception;

class ProfileController extends Controller
{
    public function __construct(
        private readonly UserServiceInterface $userService
    ) {
        $this->middleware('auth');
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $this->userService->findUser($request->user()->id);

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $user = $request->user();
            $data = $request->validated();

            // Usar o UserService para atualizar o perfil
            $updated = $this->userService->updateUserProfile($user, $data);

            if ($updated) {
                return Redirect::route('profile.edit')
                    ->with('success', 'Perfil atualizado com sucesso!');
            }

            return Redirect::route('profile.edit')
                ->with('error', 'Erro ao atualizar perfil.');
        } catch (Exception $e) {
            return Redirect::route('profile.edit')
                ->withInput()
                ->with('error', 'Erro ao atualizar perfil: ' . $e->getMessage());
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            $request->validateWithBag('userDeletion', [
                'password' => ['required', 'current_password'],
            ]);

            $user = $request->user();

            // Para exclusão do próprio perfil, usar o repositório diretamente
            // para contornar a validação de auto-exclusão do UserService
            $deleted = $this->userService->deleteUserProfile($user);

            if ($deleted) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return Redirect::to('/')
                    ->with('success', 'Conta excluída com sucesso.');
            }

            return Redirect::route('profile.edit')
                ->with('error', 'Erro ao excluir conta.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return Redirect::route('profile.edit')
                ->withErrors($e->errors(), 'userDeletion');
        } catch (Exception $e) {
            return Redirect::route('profile.edit')
                ->with('error', 'Erro ao excluir conta: ' . $e->getMessage());
        }
    }
}
