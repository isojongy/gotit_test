<?php

namespace App\Services;

use App\Common\Constants\Common;
use App\Repositories\GiftRepository;
use App\Repositories\LotteryRepository;
use App\Repositories\UserRepository;
use App\Exceptions\AjaxFailException;
use Log;
use Exception;

/**
 */
class GiftService
{
    /**
     * Main repository.
     */
    public function __construct()
    {
        $this->giftRepository = app()->make(GiftRepository::class);
        $this->lotteryRepository = app()->make(LotteryRepository::class);
        $this->userRepository = app()->make(UserRepository::class);
    }

    /**
     * getGift
     */
    public function getGift($params)
    {
        try{
            // dd($params);
            $code = $params['code'] ?? '';

            if (empty($code)) {
                throw new AjaxFailException('Vui lòng nhập mã!', 0, null);
            }

            if (strlen($code) !== 3) {
                throw new AjaxFailException('Mã phải có 3 ký tự!', 0, null);
            }

            //get lottery
            $user = \Auth::user();
            $lottery = $this->giftRepository->getLotteryByCode($code, $user->phone);
            if (!$lottery) {
                Log::error('Err get lottery');
                throw new AjaxFailException('Mã không tồn tại!', 0, null);
            }

            //get lottery log
            $conditions = [
                'lottery_code' => $code,
            ];
            $lotteryLog = $this->giftRepository->getLotteryLog($conditions);
            if (!$lotteryLog) {
                //do spin gift
                $gift = $this->spinGift();

                //insert lottery log
                $data = [
                    'lottery_code' => $code,
                    'gift_id' => $gift->id,
                ];
                $lotteryLog = $this->giftRepository->createLotteryLog($data);

                return [
                    'success' => 1,
                    'data' => $gift,
                ];
            }
            else{
                //get gift
                $gift = $this->giftRepository->getById($lotteryLog->gift_id);
            }

            if (!empty($gift)) {
                return [
                    'success' => 1,
                    'data' => $gift,
                ];
            }
            else{
                throw new AjaxFailException('Get gift failed!', 0, null);

            }

        } catch (Exception $e) {
            Log::error('spinGift err');
            throw new AjaxFailException($e->getMessage(), 0, null);
        }
    }

    /**
     * spinGift
     */
    public function spinGift()
    {
        $countSpin = session('countSpin', 1) ?? 1;
        \Log::info('countSpin '. $countSpin);

        switch($countSpin){
            case 4:
            case 8:
                $gift = $this->giftRepository->getById(Common::GIFT_MONEY_ID);
                break;
            case 9:
                $gift = $this->giftRepository->getById(Common::GIFT_IPHONE_ID);
                break;
            default:
                $gift = $this->giftRepository->getById(Common::GIFT_BAG_ID);
        }

        $newCountSpin = $countSpin > 8 ? 1 : ++$countSpin;
        session(['countSpin' => $newCountSpin]);

        return $gift;
    }

    /**
     * getGiftListByUser
     */
    public function getGiftListByUser($userId){
        return $this->giftRepository->getGiftListByUser($userId);
    }

    /**
     * redeemPoint
     */
    public function redeemPoint($params)
    {
        try{
            $userId = $params['userId'];
            //get lottery
            $user = $this->userRepository->find($userId);
            $lottery = $this->giftRepository->getLotteryByUser($user->phone);
            if (!$lottery) {
                Log::error('Err get lottery');
                throw new AjaxFailException('Mã không tồn tại!', 0, null);
            }
            if($lottery->point < Common::POINT_LIMIT){
                throw new AjaxFailException('Nhân viên này chưa đủ điểm để đổi mã!', 0, null);
            }

            $codes = explode(',', $lottery->codes);
            $codes[] = rand(100, 999);
            $where = [
                'id' => $lottery->id,
            ];
            $update = [
                'codes' => implode(',', $codes),
                'point' => intval($lottery->point) - Common::POINT_LIMIT,
            ];
            $updateLottery = $this->lotteryRepository->updateFirstByConditions($update, $where);
            if (!$updateLottery) {
                Log::error('err updateLottery');
                throw new AjaxFailException('Lỗi, vui lòng thử lại!', 0, null);
            }

            return [
                'success' => 1,
            ];
        } catch (Exception $e) {
            Log::error('spinGift err');
            throw new AjaxFailException($e->getMessage(), 0, null);
        }
    }

}
