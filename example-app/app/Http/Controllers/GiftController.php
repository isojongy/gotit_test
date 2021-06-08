<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GiftService;
use CommonHelper;
use Illuminate\Support\Facades\View;

class GiftController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->giftService = app()->make(GiftService::class);
    }

    /**
     * list.
     *
     */
    public function list()
    {
        $user = \Auth::user();
        if($user->isUser()){
            $gifts = $this->giftService->getGiftListByUser($user->phone);
            View::share('gifts', $gifts);
        }
        return view('pages.gifts.list');
    }

    /**
     * spinGift.
     *
     */
    public function spinGift()
    {
        return view('pages.gifts.spin-gift');
    }

    /**
     * postSpinGift
     *
     * @return Response
     */
    public function postSpinGift(Request $request)
    {
        $gift = $this->giftService->getGift($request->all());
        if ($gift['success']) {
            $result = CommonHelper::ajaxSuccessResponse($gift['data']);
            return response()->json($result);
        } else {
            $result = CommonHelper::ajaxFailResponse($gift['message']);
            return response()->json($result);
        }
    }

    /**
     * postRedeemPoint
     *
     * @return Response
     */
    public function postRedeemPoint(Request $request)
    {
        $redeemPoint = $this->giftService->redeemPoint($request->all());
        if ($redeemPoint['success']) {
            $result = CommonHelper::ajaxSuccessResponse();
            return response()->json($result);
        } else {
            $result = CommonHelper::ajaxFailResponse($redeemPoint['message']);
            return response()->json($result);
        }
    }
}
