<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    const ROLE_SUPER_ADMIN = 3;

    public function addSuperAdminRoleToUser($id)
    {
        try {

            $user = User::find($id);

            $user->roles()->attach(self::ROLE_SUPER_ADMIN);

            return response()->json(
                [
                    'success' => true,
                    'message' => "Super admin role added to user"
                ],
                200
            );
        } catch (\Exception $exception) {

            Log::error("Error updating super_admin role to user: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Error adding super_admin role to user"
                ],
                500
            );
        }
    }
    public function removeSuperAdminRoleToUser($id)
    {
        try {

            $user = User::find($id);

            $user->roles()->detach(self::ROLE_SUPER_ADMIN);

            return response()->json(
                [
                    'success' => true,
                    'message' => "Super admin role removed to user"
                ],
                200
            );
        } catch (\Exception $exception) {

            Log::error("Error removing super_admin role to user: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Super admin role removed to user"
                ],
                500
            );
        }
    }
}
