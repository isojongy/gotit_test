<?php

declare(strict_types=1);

namespace App\Common\Helpers;

use App\Common\Constants\AlertType;
use App\Common\Constants\Common;
use App\Models\User;
use Carbon\Carbon;

/**
 *  User Helper
 *  Provides a set of util functions can be used for retrieving User information.
 *
 * @category   App\Helper
 *
 * @copyright  2017
 *
 * @version    1.0
 *
 * @see         \App\Common\Abstraction\Controller
 * @since     File available since Release 1.0
 */
class CommonHelper
{
    /**
     * preFetchArray.
     *
     * @return array
     */
    public static function preFetchArray(array $array = [])
    {
        if (empty($array) || ! count($array)) {
            return [];
        }

        //convert null to empty string
        $array = self::convertNull2EmptyStringForArray($array);

        return $array;
    }

    /**
     * dataTableResponse.
     *
     * @return array
     */
    public static function dataTableResponse(int $totalRecords, $records)
    {
        $res = [
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecords,
            'sEcho' => 0,
            'aaData' => self::preFetchArray($records),
        ];

        return $res;
    }

    /**
     * ajaxSuccessResponse.
     *
     * @return array
     */
    public static function ajaxSuccessResponse($data = null)
    {
        $res = [
            'status' => 1,
        ];

        if (! empty($data)) {
            $res['data'] = $data;
        }

        return $res;
    }

    /**
     * ajaxFailResponse.
     *
     * @return array
     */
    public static function ajaxFailResponse(String $message = '', $data = null)
    {
        $res = [
            'status' => 0,
            'message' => $message,
        ];

        if (! empty($data)) {
            $res['data'] = $data;
        }

        return $res;
    }

    /**
     * convertNull2EmptyStringForArray.
     *
     * @return array
     */
    public static function convertNull2EmptyStringForArray($array)
    {
        //Convert null value to empty string
        array_walk_recursive($array, function (&$item) {
            $item = strval($item);
        });

        return $array;
    }

    /**
     * Return success alert for create
     *
     * @return array
     */
    public static function alertCreateSuccess($message = null)
    {
        return [
            'type' => AlertType::SUCCESS,
            'message' => $message ?? __('message.create_success')
        ];
    }

    /**
     * Return success alert for session
     *
     * @return array
     */
    public static function alertUpdateSuccess($message = null)
    {
        return [
            'type' => AlertType::SUCCESS,
            'message' => $message ?? __('message.savedSuccessfully')
        ];
    }

    /**
     * Return fail alert for create
     *
     * @return array
     */
    public static function alertCreateFail($message = null)
    {
        return [
            'type' => AlertType::DANGER,
            'message' => $message ?? __('message.create_fail')
        ];
    }

    /**
     * Return fail alert for session
     *
     * @return array
     */
    public static function alertUpdateFail($message = null)
    {
        return [
            'type' => AlertType::DANGER,
            'message' => $message ?? __('message.update_fail')
        ];
    }

    /**
     * Return fail alert for session
     *
     * @return array
     */
    public static function alertDeleteSuccess()
    {
        return [
            'type' => AlertType::SUCCESS,
            'message' => __('message.delete_success')
        ];
    }

    /**
     * Return fail alert for session
     *
     * @return array
     */
    public static function alertDeleteFail($message = null)
    {
        return [
            'type' => AlertType::DANGER,
            'message' => $message ?? __('message.delete_fail')
        ];
    }
}
