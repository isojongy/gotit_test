<?php

namespace App\Exceptions;

use CommonHelper;
use Exception;

class UpdateFailException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        $message = $this->message ?? $this->getMessage();
        return back()
                ->withInput()
                ->with('alert', CommonHelper::alertCreateFail($message));
    }
}
