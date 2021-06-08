<?php

namespace App\Exceptions;

use App\Common\Constants\AlertType;
use App\Common\Helpers\CommonHelper;
use Exception;

class AjaxFailException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        $message = $this->getMessage() ?? $this->message;
        $request->session()->flash('alert', [
            'type' => AlertType::DANGER,
            'message' => $message,
        ]);

        $result = CommonHelper::ajaxFailResponse($message);

        return response()->json($result);
    }
}
