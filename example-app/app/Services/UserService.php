<?php

namespace App\Services;

use App\Common\Constants\Common;
use App\Repositories\UserRepository;
use App\Exceptions\UpdateFailException;
use Log;
use Exception;
use Illuminate\Support\Facades\Hash;

/**
 */
class UserService
{
    /**
     * Main repository.
     */
    public function __construct()
    {
        $this->userRepository = app()->make(UserRepository::class);
    }

    /**
     * changePassword
     */
    public function changePassword($params){
        // dd($params);
        $user = \Auth::user();
        $currentPassword = $params['current_password'];
        $password = $params['password'];
        $passwordConfirmation = $params['password_confirmation'];
        if(empty($currentPassword)
            || empty($password)
            || empty($passwordConfirmation)){
                throw new UpdateFailException('Vui lòng nhập các trường có *!', 0, null);
        }

        if($password !== $passwordConfirmation){
            throw new UpdateFailException('Xác nhận mật khẩu không đúng!', 0, null);
        }

        if (Hash::check($currentPassword, $user->password)) {
            try {
                $where = [
                    'id' => $user->id,
                ];
                $update = [
                    'password' => Hash::make($password),
                ];

                $updateUser = $this->userRepository->updateFirstByConditions($update, $where);

                if (!$updateUser) {
                    Log::error('err updateUser');
                    throw new UpdateFailException('Lỗi, vui lòng thử lại!', 0, null);
                }

                \Auth::logout();
                return [
                    'success' => 1,
                    'message' => 'Đổi mật khẩu thành công!',
                ];
            } catch (Exception $e) {
                throw new UpdateFailException($e->getMessage(), 0, $e);
            }
        } else {
            Log::error('err updatePassword');
            throw new UpdateFailException('Mật khẩu hiện tại không đúng!', 0, null);
        }
    }

    /**
     * getStaffList
     */
    public function getStaffList($managerId){
        $conditions = [
            'manager_id' => $managerId,
        ];
        return $this->userRepository->getByConditions($conditions);
    }

}
