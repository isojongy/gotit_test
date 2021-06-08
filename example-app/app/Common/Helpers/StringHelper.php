<?php

declare(strict_types=1);

namespace App\Common\Helpers;

use Carbon\Carbon;

/**
 *  String Helper
 *  Provides a set of util functions can be used in String processor.
 *
 * @category   App\Helper
 *
 *
 * @version    1.0
 *
 * @see         \App\Common\Abstraction\Controller
 * @since     File available since Release 1.0
 */
class StringHelper
{
    /**
     * Get data delimiter.
     *
     * @return string
     */
    public static function getDelimiter()
    {
        if (\mb_strtoupper(\mb_substr(PHP_OS, 0, 3)) === 'WIN') {
            return "\r\n";
        }

        return "\n";
    }

    /**
     * StartsWith is a util function helps to check the start of string.
     *
     * @param $haystack
     * @param $needle
     *
     * @return bool
     */
    public static function startsWith($haystack, $needle)
    {
        if ($haystack === null || $needle === null || $haystack === '' || $haystack === '') {
            return false;
        }

        $haystackLength = \mb_strlen($haystack);
        $needleLength = \mb_strlen($needle);

        if ($haystackLength < $needleLength) {
            return false;
        }

        return \mb_substr($haystack, 0, $needleLength) === $needle;
    }

    /**
     * EndsWith is a util function helps to check the end of string.
     *
     * @param $haystack
     * @param $needle
     *
     * @return bool
     */
    public static function endsWith($haystack, $needle)
    {
        // search forward starting from end minus needle length characters
        return $needle === '' || (($temp = \mb_strlen($haystack) - \mb_strlen($needle)) >= 0 && \mb_strpos($haystack, $needle, $temp) !== false);
    }

    /**
     * Convert PHP string to HTML.
     *
     * @param $string
     *
     * @return string
     */
    public static function convertToHTML($string)
    {
        $html = nl2br($string);

        return $html;
    }

    /**
     * Format currency to Japanese Yen with comma.
     *
     * @param int $amount
     *
     * @return string
     */
    public static function formatJapaneseYen($amount)
    {
        if ($amount === null) {
            return '0';
        }
        if (is_string($amount)) {
            $amount = (int) $amount;
        }

        return number_format($amount, 0, '.', ',');
    }

    /**
     * Get value between specific characters
     * Example:
     *      "<< A >>" then return value should be "A".
     *
     * @param $start
     * @param $end
     * @param $string
     *
     * @return bool|string
     */
    public static function getBetween($start, $end, $string)
    {
        $string = ' '.$string;
        $position = strpos($string, $start);
        if ($position === false) {
            return '';
        }
        $position += strlen($start);
        $len = strpos($string, $end, $position) - $position;

        return substr($string, $position, $len);
    }

    /**
     * @param $string
     *
     * @return string
     */
    public static function formatZipCode($string)
    {
        $result = '';
        if ($string !== null) {
            $subject = mb_substr((string) $string, 0, 8);
            if (mb_strpos($subject, '-') !== false || mb_strpos($subject, ' ') !== false) {
                $result = $subject;
            } else {
                $subject = substr((string) $string, 0, 7);
                $result = preg_replace('/\d{3}/', '$0-', str_replace('.', null, $subject), 1);
            }
        }

        return $result;
    }

    /**
     * Function format address to Japanese before process check.
     *
     * @param $inputString
     *
     * @return string
     */
    public static function formatStringToJapanese($inputString)
    {

        //replace all space to ''
        $inputString = preg_replace('/\s+/', '', $inputString);
        //replace character of japanese
        $replaceOf = ['ヶ', 'ケ', 'ヵ', 'カ', 'ノ', 'ツ', 'っ', 'ッ'];
        $replaceBy = ['ケ', 'ケ', 'ケ', 'ケ', 'ノ', 'ッ', 'ッ', 'ッ'];

        $inputString = str_replace($replaceOf, $replaceBy, $inputString);

        /* Convert all "kana" to "zen-kaku" "kata-kana" */
        $inputString = mb_convert_kana($inputString, 'RAK');

        return $inputString;
    }

    /**
     * @param $comment
     * @param $separator
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getGoodsNoListFromComment($comment, $separator)
    {
        $arrGoods = collect();
        if ($comment && $separator) {
            $startChar = '《';
            $endChar = '》';
            $arrString = \explode($separator, $comment);
            foreach ($arrString as $string) {
                $result = StringHelper::getBetween($startChar, $endChar, $string);
                if ($result) {
                    if (! preg_match("/[\d]{4}年[\d]?[\d]月[\d]?[\d]日/", $result)) {
                        $arrGoods->push($result);
                    }
                }
            }
        }

        return $arrGoods;
    }

    /**
     * @param $comment
     * @param $separator
     *
     * @return string
     */
    public static function getGoodsNoFromComment($comment, $separator)
    {
        $goodsNo = '';

        if ($comment && $separator) {
            $pattern = '/'.$separator.'.*?》/';
            if (preg_match($pattern, $comment, $matches)) {
                $startChar = '《';
                $endChar = '》';
                $goodsNo = StringHelper::getBetween($startChar, $endChar, $matches[0]);
            }
        }

        return $goodsNo;
    }

    /**
     * getDateFromComment.
     *
     *
     * @return mixed
     */
    public static function getDateFromComment($string)
    {
        $startChar = '《';
        $endChar = '》';

        $date = StringHelper::getBetween($startChar, $endChar, $string);
        if (! empty($date)) {
            if (preg_match("/[\d]{4}年[\d]?[\d]月[\d]?[\d]日/", $date)) {
                $date = str_replace(['年', '月', '日', '着', '発'], ['-', '-', '', '', ''], $date);
                if (! empty($date)) {
                    $date = Carbon::parse($date)->format('Y-m-d');
                }

                return $date;
            } else {
                return '';
            }
        }

        return '';
    }

    /**
     * convertSymbolDataToArrayCheck.
     *
     * Support data
     *  $symbol = "☆○";
     *  $symbol = "☆, ○";
     *  $symbol = "☆ ○";
     *
     *
     * @return array
     */
    public static function convertSymbolDataToArrayCheck($symbol)
    {
        if (empty($symbol)) {
            return [];
        }

        $symbolArr = explode(',', $symbol);
        if (count($symbolArr) == 1) {
            $symbolArr = explode(' ', $symbol);
        }

        if (count($symbolArr) == 1) {
            $tmpString = $symbolArr[0];
            $length = \mb_strlen($tmpString);
            $symbol1ArrNew = [];
            for ($i = 0; $i < $length; $i++) {
                $symbol1ArrNew[] = \mb_substr($tmpString, $i, 1);
            }
            $symbolArr = $symbol1ArrNew;
        }

        $symbolArr = array_filter($symbolArr);
        $symbolArr = array_map('trim', $symbolArr);

        return $symbolArr;
    }

    /**
     * @param $string
     *
     * @return bool
     */
    public static function checkHaveNGChar($string)
    {
        $result = false;
        if (isset($string)) {
            //Regex string reg match
            $regMatch = "/[,|?|\*|\#|\\|%|\，|\||\“|\”|№|㏍|℡|㊤|㊥|㊦|㊧|㊨|㈱|㈲|㈹|㍾|㍽|㍼|㍻|①|②|③|④|⑤|⑥|⑦|⑧|⑨|⑩|⑪|⑫|⑬|⑭|⑮|⑯|⑰|⑱|⑲|⑳|Ⅰ|Ⅱ|Ⅲ|Ⅳ|Ⅴ|Ⅵ|Ⅶ|Ⅷ|Ⅸ|Ⅹ|纊|鍈|蓜|炻|棈|兊]/iu";
            if (preg_match($regMatch, $string)) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * @param $string
     *
     * @return string
     */
    public static function removeSpaceSingleQuoteDash($string)
    {
        $resultString = '';
        if (isset($string)) {
            $tempString = \str_replace(' ', '', $string);
            if (isset($tempString)) {
                $tempString = \str_replace('\'', '', $tempString);
                if (isset($tempString)) {
                    $resultString = \str_replace('-', '', $tempString);
                }
            }
        }

        return $resultString;
    }

    /**
     * @param $string
     *
     * @return string
     */
    public static function removeSpaceDash($string)
    {
        $resultString = '';
        if (isset($string)) {
            $tempString = \str_replace(' ', '', $string);
            if (isset($tempString)) {
                $tempString = \str_replace('-', '', $tempString);
                if (isset($tempString)) {
                    $resultString = \str_replace('\'', '\'\'', $tempString);
                }
            }
        }

        return $resultString;
    }

    /**
     * Remove $noNeedComment if it is exist in $string
     * also remove the blank line in $string.
     *
     * @param $string
     * @param $noNeedComment
     *
     * @return string
     */
    public static function removeNoNeedComment($string, $noNeedComment)
    {
        if (isset($string) && isset($noNeedComment)) {
            $string = \str_replace($noNeedComment, '', $string);
            $string = \str_replace("\r", '', $string);
        }

        return $string;
    }

    /**
     * Skip content in comment.
     *
     * @param $comment
     * @param $specialComment
     *
     * @return string
     */
    public static function skipSpecialContentInComment($commentContent, $specialComment)
    {
        $foundPosition = \mb_strpos($commentContent, $specialComment);
        if ($foundPosition !== false) {
            $tempArray = \explode("\n", $commentContent);
            $tempArray[$foundPosition] = '';
            $tempArray[$foundPosition + 1] = '';
            if (config('settings.orderComment.skipDeliveryDateLine2')) {
                $tempArray[$foundPosition + 2] = '';
            }
            $commentContent = implode("\n", $tempArray);

            $commentContent = \str_replace("\n", '', $commentContent);
            $commentContent = \str_replace("\r", '', $commentContent);
        }

        return [
            'foundPosition' => $foundPosition,
            'commentContent' => $commentContent,
        ];
    }

    public static function formatFromLenString($str, $len = 40)
    {
        $string = $str;
        if (strlen($str) > $len) {
            $stringCut = substr($str, 0, $len);
            $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...';
        }

        return $string;
    }

    /**
     * Function get length of string with multibyte and single byte mix.
     *
     * @param $str
     *
     * @retun int
     */
    public static function getLengthOfString($str)
    {
        $length = strlen(mb_convert_encoding($str, 'SJIS', 'UTF-8'));

        return $length;
    }

    /**
     *  Get an input string and format it to
     *      XXX-XXXX-XXX (10 numners)
     *      XXX-XXXX-XXXX (11 numners)
     *  No limit string length of 10 or 11 numbers.
     *
     * @param $inputString
     *
     * @return bool|mixed|string
     */
    public static function formatTelephoneNumber($inputString)
    {
        if ($inputString === '' || ! is_string($inputString)) {
            return '';
        }
        $inputString = \str_replace('-', '', $inputString);

        // Ensure only extract numbers
        preg_match_all('!\d+!', $inputString, $matches);
        if (! empty($matches)) {
            $inputString = implode(',', $matches[0]);
        }

        $inputString = (substr($inputString, 0, 3).'-'.substr($inputString, 3, 4).'-'.substr($inputString, 7));

        return $inputString;
    }

    /**
     * Decode content if having encode ISO-2022-JP, UTF-8, Shift_JIS, or GB2312.
     *
     *
     * @param $string
     *
     * @return string
     */
    public static function decodeContent($string)
    {
        $result = '';

        if (! empty($string)) {
            $result = $string;

            \mb_internal_encoding('UTF-8');
            $string = preg_replace('/[[:cntrl:]]/', '', $string);
            $word = \trim((string) $string);
            $checkWord = \mb_strtoupper($word);
            if (\mb_strpos($checkWord, '=?UTF') !== false
                || \mb_strpos($checkWord, '=?GB') !== false
                || \mb_strpos($checkWord, '=?ISO') !== false
                || \mb_strpos($checkWord, '=?SHIFT') !== false
                || \mb_strpos($checkWord, '=?SJIS') !== false
                || \mb_strpos($checkWord, '=?WINDOWS') !== false
            ) {
                try {
                    $word = \mb_decode_mimeheader($word);
                    if (\mb_strpos($word, '=?') !== false) {
                        $word = \iconv_mime_decode($word, 0, 'UTF-8');
                    }
                    $result = $word;
                } catch (\Exception $e) {
                    $msg = $e->getMessage();
                    \Log::info('Error in: '.$result);
                    \Log::info('Error message '.$msg);

                    $checkWord = \mb_strtoupper($result);
                    if (\mb_strpos($checkWord, '=?UTF') !== false
                        || \mb_strpos($checkWord, '=?GB') !== false
                        || \mb_strpos($checkWord, '=?ISO') !== false
                        || \mb_strpos($checkWord, '=?SHIFT') !== false
                        || \mb_strpos($checkWord, '=?SJIS') !== false
                        || \mb_strpos($checkWord, '=?WINDOWS') !== false
                    ) {
                        $result = \mb_decode_mimeheader($result);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * getContentFromEmailHeader.
     *
     *
     * @return void
     */
    public static function getContentFromEmailHeader($emailHeader, $fields = ['Subject'])
    {
        $data = [];

        $emailHeaderData = imap_rfc822_parse_headers($emailHeader);
        $imapError = imap_errors();
        if (! empty($imapError)) {
            $tmpMessageId = $emailHeaderData->message_id ?? '';
            \Log::info('The message_id '.$tmpMessageId.' has imap error: '.json_encode($imapError));
        }

        if (! empty($fields)) {
            foreach ($fields as $key => $field) {
                switch ($field) {
                    case 'Subject':
                        $result = $emailHeaderData->subject ?? '';
                        break;

                    case 'FromEmail':
                        $result = $emailHeaderData->fromaddress ?? '';
                        $result = str_replace(',', ';', $result);
                        break;

                    case 'ToEmail':
                        $result = $emailHeaderData->toaddress ?? '';
                        $result = str_replace(',', ';', $result);
                        break;

                    case 'Sender':
                        $result = $emailHeaderData->fromaddress ?? '';
                        $result = $emailHeaderData->senderaddress ?? $result;
                        $result = str_replace(',', ';', $result);
                        break;

                    case 'ReplyToEmail':
                        $result = $emailHeaderData->fromaddress ?? '';
                        $result = $emailHeaderData->reply_toaddress ?? $result;
                        $result = str_replace(',', ';', $result);
                        break;

                    case 'References':
                        $result = $emailHeaderData->references ?? '';
                        break;

                    default:
                        break;
                }

                $data[$field] = StringHelper::decodeContent($result);
            }
        }

        return $data;
    }

    /**
     * @param $str
     * @return mixed
     */
    public static function replaceJapNumberByInt($str)
    {
        $maps = ['０', '１', '２', '３', '４', '５', '６', '７', '８', '９'];
        foreach ($maps as $int => $jap) {
            $str = str_replace($jap, $int, $str);
        }

        return $str;
    }

    /**
     * @param $sourceTexts
     * @param $changedTexts
     * @param $subjectString
     * @return null|string|string[]
     */
    public static function changeTextWithRegex($sourceTexts, $changedTexts, $subjectString)
    {
        $patternArrays = array_map(function ($item) {
            return '('.preg_quote($item).')';
        }, $sourceTexts);

        return preg_replace($patternArrays, $changedTexts, $subjectString);
    }

    /**
     * USE SPECIAL SPAN to check text from customer note.
     */
    const SPECIAL_SPAN = 'quangspan';

    /**
     * Set raw text with color.
     *
     * @param array  $textArray
     * @param string $color
     * @param string $checkSpecialSpan
     *
     * @return array
     */
    public static function setRawTextWithColor($textArray, $color = 'black', $checkSpecialSpan = false)
    {
        return array_map(function ($text) use ($color, $checkSpecialSpan) {
            if ($checkSpecialSpan) {
                return '<'.StringHelper::SPECIAL_SPAN." style='color: ".$color.";'>".$text.'</'.StringHelper::SPECIAL_SPAN.'>';
            } else {
                return "<span style='color: ".$color.";'>".$text.'</span>';
            }
        }, $textArray);
    }

    /**
     * @param string $rawText
     * @param array  $replaceTextArr
     * @param string $color
     * @param string $checkSpecialSpan
     *
     * @return string
     */
    public static function getColorTextFromOrderComment($rawText, $replaceTextArr, $color = 'black', $checkSpecialSpan = false)
    {
        $rawText = $rawText ?? '';
        $formatedText = str_replace("\r", '', $rawText);

        $textAddedColorArr = StringHelper::setRawTextWithColor($replaceTextArr, $color, $checkSpecialSpan);
        $textAfterSetColor = StringHelper::changeTextWithRegex($replaceTextArr, $textAddedColorArr, $formatedText);

        return StringHelper::nl2br2($textAfterSetColor ?? '');
    }

    /**
     * nl2br2.
     *
     *
     * @return string
     */
    public static function nl2br2($string)
    {
        $string = str_replace(["\r\n", "\r", "\n"], '<br />', $string);

        return $string;
    }

    /**
     * @param $str
     * @return mixed
     */
    public static function replaceChiNumberByInt($str)
    {
        $maps = ['〇', '一', '二', '三', '四', '五', '六', '八', '九', '十'];
        foreach ($maps as $int => $chi) {
            $str = str_replace($chi, $int, $str);
        }

        return $str;
    }

    /**
     * @param $string
     * @return bool
     */
    public static function checkHaveNumber($string)
    {
        $result = false;
        if (isset($string)) {
            $string = StringHelper::replaceJapNumberByInt($string);
            $string = StringHelper::replaceChiNumberByInt($string);
            //Regex string reg match
            $regMatch = '~[0-9]~';
            if (preg_match($regMatch, $string)) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Get an input string and format it to XXXXXXXXXX.
     *
     * @param $inputString
     *
     * @return bool|mixed|string
     */
    public static function formatTelephoneNumberWithOutSpaceAndHyphen($inputString)
    {
        if ($inputString === '' || ! is_string($inputString)) {
            return '';
        }
        $inputString = \str_replace('-', '', $inputString);
        $inputString = \str_replace(' ', '', $inputString);

        return $inputString;
    }

    /**
     * Get translated phrases based on specified keys.
     * @param array $keys
     * @param null $prefix
     * @return array
     */
    public static function getPhrases($keys = [], $prefix = null)
    {
        $phrases = [];
        foreach ($keys as $key => $value) {
            if (is_array($value)) {
                $phrases[$key] = self::getPhrases($value, $prefix ? $prefix.'.'.$key : $key);
            } else {
                $phrases[$value] = __($prefix ? $prefix.'.'.$value : $value);
            }
        }

        return $phrases;
    }

    /**
     * Get translated phrase for UI.
     *
     * @return array
     */
    public static function getPhrasesForUI()
    {
        return self::getPhrases([
            'title' => [
                'alert' => [
                    'success',
                    'error',
                    'confirm',
                    'cancel',
                    'ok',
                    'close',
                    'warning',
                ],
            ],
            'label' => [
                'updateStock' => [
                    'note' => [
                        'edit',
                        'update',
                        'textEdited',
                        'textError',
                        'restrict',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Check char is special character in regex.
     *
     * @param string $char
     * @return bool
     */
    public static function isSpecialRegexChar(string $char)
    {
        $specialRegexChar = '.\+*?[^]$(){}=!<>|:-#/';
        if (strpos($specialRegexChar, $char) !== false) {
            return true;
        }

        return false;
    }

    /**
     * Reformat date by change time unit delimiter.
     *
     * @param string $date
     * @param string $originDelimiter
     * @param string $newDelimiter
     * @return mixed|string|null
     */
    public static function reFormatDate(string $date, $originDelimiter = '', $newDelimiter = '-')
    {
        if ($originDelimiter == '') {
            if (strlen($date) !== 8) {
                return;
            }

            return substr($date, 0, 4).'-'.substr($date, 4, 2).'-'.substr($date, 6, 2);
        }

        if (self::isSpecialRegexChar($originDelimiter)) {
            $originDelimiter = '\\'.$originDelimiter;
        }

        /**
         * This rule serve some common case of date. It doesn't cover all case of date
         * It can be improve to cover absolutely case of date type.
         */
        $datePattern = '/^[0-9]{0,4}'.$originDelimiter.'[0-1][0-9]'.$originDelimiter.'[0-3][0-9]$/';
        if (preg_match($datePattern, $date) != 1) {
            return;
        }

        return str_replace($originDelimiter, $newDelimiter, $date);
    }

    /**
     * Trim string of mixing normal, 1 byte, 2 bytes, half-width and full-width Japanese characters
     *  Using "mb_strimwidth" to trim, and "mb_strwidth" to check string length.
     *
     *
     * @return string
     */
    public static function trimWidthString($string, $start, $end = 0, $trimmarker = '')
    {
        $data = '';

        $string = (string) $string;
        $start = (int) $start;
        $end = (int) $end;
        if (! empty($string)) {
            $stringLength = mb_strwidth($string);
            $startPosition = min($stringLength, $start);

            $endPosition = (int) $end;
            if ($endPosition <= 0) {
                $endPosition = $stringLength;
            }
            if ($startPosition > 0) {
                $data1 = mb_strimwidth($string, 0, $startPosition, '', 'UTF-8');
                $data2 = mb_strimwidth($string, 0, $startPosition + $endPosition, '', 'UTF-8');
                $data = str_replace($data1, '', $data2);
            } else {
                $data = mb_strimwidth($string, 0, $endPosition, '', 'UTF-8');
            }
        }

        return $data;
    }

    /**
     * convertPatternToMathStringValue.
     *
     * Checked patterns:
     *  $pattern = "１２１n-12";
     *  $pattern = "１２１n-12asd";
     *  $pattern = "/+-１２１n-12 - asd/1";
     *  $pattern = "2/(-n12)";
     *  $pattern = "2/(+n12)";
     *  $pattern = "no number";
     *  $pattern = "abc123";
     *  $pattern = "0";
     *  $pattern = "2";
     *  $pattern = "2n-1";
     *  $pattern = "2 n - 1";
     *  $pattern = "1*n-2*n";
     *  $pattern = "1 * n - 2 * n";
     *  $pattern = "2/n-1";
     *  $pattern = "n/2-1";
     *  $pattern = "n / 2 - 1";
     *  $pattern = "n";
     *  $pattern = "n - 2";
     *  $pattern = "2 - n";
     *  $pattern = "n-1";
     *  $pattern = "n2-1";
     *  $pattern = "3n2-1";
     *  $pattern = "2n-1";
     */
    public static function convertPatternToMathStringValue($quantity, $pattern)
    {
        if (empty($pattern)) {
            $pattern = '0';
        }
        if (empty($quantity)) {
            $quantity = '0';
        }

        $mathString = '0';

        if (preg_match("/\*/i", $pattern)) {
            $mathString = preg_replace('/n/i', $quantity, $pattern);
        } else {
            $mathString = $pattern;
            $mathString = preg_replace('/n/i', '*'.$quantity.'*', $mathString);
        }

        // Replace and remove unnecessary characters
        $mathString = preg_replace(['/０/i'], '0', $mathString);
        $mathString = preg_replace(['/１/i'], '1', $mathString);
        $mathString = preg_replace(['/２/i'], '2', $mathString);
        $mathString = preg_replace(['/３/i'], '3', $mathString);
        $mathString = preg_replace(['/４/i'], '4', $mathString);
        $mathString = preg_replace(['/５/i'], '5', $mathString);
        $mathString = preg_replace(['/６/i'], '6', $mathString);
        $mathString = preg_replace(['/７/i'], '7', $mathString);
        $mathString = preg_replace(['/８/i'], '8', $mathString);
        $mathString = preg_replace(['/９/i'], '9', $mathString);
        $mathString = preg_replace(["/(\+$|^\+)/i", "/(\-$|^\-)/i", "/(\*$|^\*)/i", "/(\/$|^\/)/i", '/ /i', '/[a-z|A-Z]/i'], '', $mathString);
        $mathString = preg_replace(["/\*\+/i", "/\+\*/i", "/\+\+/i"], '+', $mathString);
        $mathString = preg_replace(["/\-\//i", "/\+\//i", "/\*\//i", "/\/\*/i"], '/', $mathString);
        $mathString = preg_replace(["/\-\*/i", "/\+\*/i", "/\*\*/i"], '*', $mathString);
        $mathString = preg_replace(["/\(\*/i", "/\(\//i"], '(', $mathString);
        $mathString = preg_replace(["/\*\)/i", "/\/\)/i", "/\+\)/i", "/\-\)/i"], ')', $mathString);
        $mathString = preg_replace(["/\*\-/i", "/\-\*/i", "/\-\+/i", "/\+\-/i"], '-', $mathString);

        if (empty($mathString)) {
            $mathString = '0';
        }

        eval('$mathString = ('.$mathString.');');

        return $mathString;
    }

    /**
     * Remove all sub string presenting time in a whole string.
     *
     * @param string $originString
     * @return string|string[]|null
     */
    public static function removeTimeInString($originString)
    {
        /**
         * Sometime $originString is 0,
         * this value need to be kept.
         */
        if (empty($originString)) {
            return $originString;
        }

        return preg_replace(
            '/[0-9]{2}:[0-9]{2}:[0-9]{2}/',
            '',
            $originString
        );
    }

    /**
     * Convert encoding for body content of email.
     *
     * @param string $encoding
     * @return string
     */
    public static function convertEncoding($str, $from = 'ISO-8859-2', $to = 'UTF-8')
    {
        if (! $from) {
            return mb_convert_encoding($str, $to);
        }

        if (strtolower($from) == 'us-ascii') {
            if (strpos(json_encode($str), "\u001b") !== false) {
                \Log::info('--- us-ascii ---');
                \Log::info($str);
                \Log::info('--- json_encode us-ascii u001b ---');
                \Log::info(json_encode($str));
                $from = 'ISO-2022-JP';
            }
        }

        // Check strange charset encoding from header
        $strangeFromArr = [
            '3D',
        ];
        if (in_array(strtoupper($from), $strangeFromArr)) {
            return mb_convert_encoding($str, $to);
        }

        $from = StringHelper::getEncodingAliases($from);
        if (strtolower($from) == 'us-ascii' && strtolower($to) == 'utf-8') {
            return $str;
        }

        try {
            if (function_exists('iconv') && strtolower($from) != 'utf-7' && strtolower($to) != 'utf-7') {
                return mb_convert_encoding($str, $to, $from);
            } else {
                if (! $from) {
                    return mb_convert_encoding($str, $to);
                }

                return mb_convert_encoding($str, $to, $from);
            }
        } catch (\Exception $e) {
            \Log::info('Error encoding: '.$e->getMessage());

            return mb_convert_encoding($str, $to);
        }
    }

    /**
     * Returns proper encoding mapping, if exsists. If it doesn't, return unchanged $encoding.
     *
     * @param string $encoding
     * @return string
     */
    public static function getEncodingAliases($encoding)
    {
        $aliases = [
            /*
            |--------------------------------------------------------------------------
            | Email encoding aliases
            |--------------------------------------------------------------------------
            |
            | Email encoding aliases used to convert to iconv supported charsets
            |
            |
            | This Source Code Form is subject to the terms of the Mozilla Public
            | License, v. 2.0. If a copy of the MPL was not distributed with this
            | file, You can obtain one at http://mozilla.org/MPL/2.0/.
            |
            | This Original Code has been modified by IBM Corporation.
            | Modifications made by IBM described herein are
            | Copyright (c) International Business Machines
            | Corporation, 1999
            |
            | Modifications to Mozilla code or documentation
            | identified per MPL Section 3.3
            |
            | Date         Modified by     Description of modification
            | 12/09/1999   IBM Corp.       Support for IBM codepages - 850,852,855,857,862,864
            |
            | Rule of this file:
            | 1. key should always be in lower case ascii so we can do case insensitive
            |    comparison in the code faster.
            | 2. value should be the one used in unicode converter
            |
            | 3. If the charset is not used for document charset, but font charset
            |    (e.g. XLFD charset- such as JIS x0201, JIS x0208), don't put here
            |
            */
            'ascii'                    => 'us-ascii',
            'us-ascii'                 => 'us-ascii',
            'ansi_x3.4-1968'           => 'us-ascii',
            '646'                      => 'us-ascii',
            'iso-8859-1'               => 'ISO-8859-1',
            'iso-8859-2'               => 'ISO-8859-2',
            'iso-8859-3'               => 'ISO-8859-3',
            'iso-8859-4'               => 'ISO-8859-4',
            'iso-8859-5'               => 'ISO-8859-5',
            'iso-8859-6'               => 'ISO-8859-6',
            'iso-8859-6-i'             => 'ISO-8859-6-I',
            'iso-8859-6-e'             => 'ISO-8859-6-E',
            'iso-8859-7'               => 'ISO-8859-7',
            'iso-8859-8'               => 'ISO-8859-8',
            'iso-8859-8-i'             => 'ISO-8859-8-I',
            'iso-8859-8-e'             => 'ISO-8859-8-E',
            'iso-8859-9'               => 'ISO-8859-9',
            'iso-8859-10'              => 'ISO-8859-10',
            'iso-8859-11'              => 'ISO-8859-11',
            'iso-8859-13'              => 'ISO-8859-13',
            'iso-8859-14'              => 'ISO-8859-14',
            'iso-8859-15'              => 'ISO-8859-15',
            'iso-8859-16'              => 'ISO-8859-16',
            'iso-ir-111'               => 'ISO-IR-111',
            'iso-2022-cn'              => 'ISO-2022-CN',
            'iso-2022-cn-ext'          => 'ISO-2022-CN',
            'iso-2022-kr'              => 'ISO-2022-KR',
            'iso-2022-jp'              => 'ISO-2022-JP',
            'utf-16be'                 => 'UTF-16BE',
            'utf-16le'                 => 'UTF-16LE',
            'utf-16'                   => 'UTF-16',
            'windows-1250'             => 'windows-1250',
            'windows-1251'             => 'windows-1251',
            'windows-1252'             => 'windows-1252',
            'windows-1253'             => 'windows-1253',
            'windows-1254'             => 'windows-1254',
            'windows-1255'             => 'windows-1255',
            'windows-1256'             => 'windows-1256',
            'windows-1257'             => 'windows-1257',
            'windows-1258'             => 'windows-1258',
            'ibm866'                   => 'IBM866',
            'ibm850'                   => 'IBM850',
            'ibm852'                   => 'IBM852',
            'ibm855'                   => 'IBM855',
            'ibm857'                   => 'IBM857',
            'ibm862'                   => 'IBM862',
            'ibm864'                   => 'IBM864',
            'utf-8'                    => 'UTF-8',
            'utf-7'                    => 'UTF-7',
            'shift_jis'                => 'Shift_JIS',
            'big5'                     => 'Big5',
            'euc-jp'                   => 'EUC-JP',
            'euc-kr'                   => 'EUC-KR',
            'gb2312'                   => 'GB2312',
            'gb18030'                  => 'gb18030',
            'viscii'                   => 'VISCII',
            'koi8-r'                   => 'KOI8-R',
            'koi8_r'                   => 'KOI8-R',
            'cskoi8r'                  => 'KOI8-R',
            'koi'                      => 'KOI8-R',
            'koi8'                     => 'KOI8-R',
            'koi8-u'                   => 'KOI8-U',
            'tis-620'                  => 'TIS-620',
            't.61-8bit'                => 'T.61-8bit',
            'hz-gb-2312'               => 'HZ-GB-2312',
            'big5-hkscs'               => 'Big5-HKSCS',
            'gbk'                      => 'gbk',
            'cns11643'                 => 'x-euc-tw',
            //
            // Aliases for ISO-8859-1
            //
            'latin1'                   => 'ISO-8859-1',
            'iso_8859-1'               => 'ISO-8859-1',
            'iso8859-1'                => 'ISO-8859-1',
            'iso8859-2'                => 'ISO-8859-2',
            'iso8859-3'                => 'ISO-8859-3',
            'iso8859-4'                => 'ISO-8859-4',
            'iso8859-5'                => 'ISO-8859-5',
            'iso8859-6'                => 'ISO-8859-6',
            'iso8859-7'                => 'ISO-8859-7',
            'iso8859-8'                => 'ISO-8859-8',
            'iso8859-9'                => 'ISO-8859-9',
            'iso8859-10'               => 'ISO-8859-10',
            'iso8859-11'               => 'ISO-8859-11',
            'iso8859-13'               => 'ISO-8859-13',
            'iso8859-14'               => 'ISO-8859-14',
            'iso8859-15'               => 'ISO-8859-15',
            'iso_8859-1:1987'          => 'ISO-8859-1',
            'iso-ir-100'               => 'ISO-8859-1',
            'l1'                       => 'ISO-8859-1',
            'ibm819'                   => 'ISO-8859-1',
            'cp819'                    => 'ISO-8859-1',
            'csisolatin1'              => 'ISO-8859-1',
            //
            // Aliases for ISO-8859-2
            //
            'latin2'                   => 'ISO-8859-2',
            'iso_8859-2'               => 'ISO-8859-2',
            'iso_8859-2:1987'          => 'ISO-8859-2',
            'iso-ir-101'               => 'ISO-8859-2',
            'l2'                       => 'ISO-8859-2',
            'csisolatin2'              => 'ISO-8859-2',
            //
            // Aliases for ISO-8859-3
            //
            'latin3'                   => 'ISO-8859-3',
            'iso_8859-3'               => 'ISO-8859-3',
            'iso_8859-3:1988'          => 'ISO-8859-3',
            'iso-ir-109'               => 'ISO-8859-3',
            'l3'                       => 'ISO-8859-3',
            'csisolatin3'              => 'ISO-8859-3',
            //
            // Aliases for ISO-8859-4
            //
            'latin4'                   => 'ISO-8859-4',
            'iso_8859-4'               => 'ISO-8859-4',
            'iso_8859-4:1988'          => 'ISO-8859-4',
            'iso-ir-110'               => 'ISO-8859-4',
            'l4'                       => 'ISO-8859-4',
            'csisolatin4'              => 'ISO-8859-4',
            //
            // Aliases for ISO-8859-5
            //
            'cyrillic'                 => 'ISO-8859-5',
            'iso_8859-5'               => 'ISO-8859-5',
            'iso_8859-5:1988'          => 'ISO-8859-5',
            'iso-ir-144'               => 'ISO-8859-5',
            'csisolatincyrillic'       => 'ISO-8859-5',
            //
            // Aliases for ISO-8859-6
            //
            'arabic'                   => 'ISO-8859-6',
            'iso_8859-6'               => 'ISO-8859-6',
            'iso_8859-6:1987'          => 'ISO-8859-6',
            'iso-ir-127'               => 'ISO-8859-6',
            'ecma-114'                 => 'ISO-8859-6',
            'asmo-708'                 => 'ISO-8859-6',
            'csisolatinarabic'         => 'ISO-8859-6',
            //
            // Aliases for ISO-8859-6-I
            //
            'csiso88596i'              => 'ISO-8859-6-I',
            //
            // Aliases for ISO-8859-6-E",
            //
            'csiso88596e'              => 'ISO-8859-6-E',
            //
            // Aliases for ISO-8859-7",
            //
            'greek'                    => 'ISO-8859-7',
            'greek8'                   => 'ISO-8859-7',
            'sun_eu_greek'             => 'ISO-8859-7',
            'iso_8859-7'               => 'ISO-8859-7',
            'iso_8859-7:1987'          => 'ISO-8859-7',
            'iso-ir-126'               => 'ISO-8859-7',
            'elot_928'                 => 'ISO-8859-7',
            'ecma-118'                 => 'ISO-8859-7',
            'csisolatingreek'          => 'ISO-8859-7',
            //
            // Aliases for ISO-8859-8",
            //
            'hebrew'                   => 'ISO-8859-8',
            'iso_8859-8'               => 'ISO-8859-8',
            'visual'                   => 'ISO-8859-8',
            'iso_8859-8:1988'          => 'ISO-8859-8',
            'iso-ir-138'               => 'ISO-8859-8',
            'csisolatinhebrew'         => 'ISO-8859-8',
            //
            // Aliases for ISO-8859-8-I",
            //
            'csiso88598i'              => 'ISO-8859-8-I',
            'iso-8859-8i'              => 'ISO-8859-8-I',
            'logical'                  => 'ISO-8859-8-I',
            //
            // Aliases for ISO-8859-8-E",
            //
            'csiso88598e'              => 'ISO-8859-8-E',
            //
            // Aliases for ISO-8859-9",
            //
            'latin5'                   => 'ISO-8859-9',
            'iso_8859-9'               => 'ISO-8859-9',
            'iso_8859-9:1989'          => 'ISO-8859-9',
            'iso-ir-148'               => 'ISO-8859-9',
            'l5'                       => 'ISO-8859-9',
            'csisolatin5'              => 'ISO-8859-9',
            //
            // Aliases for UTF-8",
            //
            'unicode-1-1-utf-8'        => 'UTF-8',
            // nl_langinfo(CODESET) in HP/UX returns 'utf8' under UTF-8 locales",
            'utf8'                     => 'UTF-8',
            //
            // Aliases for Shift_JIS",
            //
            'x-sjis'                   => 'Shift_JIS',
            'shift-jis'                => 'Shift_JIS',
            'ms_kanji'                 => 'Shift_JIS',
            'csshiftjis'               => 'Shift_JIS',
            'windows-31j'              => 'Shift_JIS',
            'cp932'                    => 'Shift_JIS',
            'sjis'                     => 'Shift_JIS',
            //
            // Aliases for EUC_JP",
            //
            'cseucpkdfmtjapanese'      => 'EUC-JP',
            'x-euc-jp'                 => 'EUC-JP',
            //
            // Aliases for ISO-2022-JP",
            //
            'csiso2022jp'              => 'ISO-2022-JP',
            // The following are really not aliases ISO-2022-JP, but sharing the same decoder",
            'iso-2022-jp-2'            => 'ISO-2022-JP',
            'csiso2022jp2'             => 'ISO-2022-JP',
            //
            // Aliases for Big5",
            //
            'csbig5'                   => 'Big5',
            'cn-big5'                  => 'Big5',
            // x-x-big5 is not really a alias for Big5, add it only for MS FrontPage",
            'x-x-big5'                 => 'Big5',
            // Sun Solaris",
            'zh_tw-big5'               => 'Big5',
            //
            // Aliases for EUC-KR",
            //
            'cseuckr'                  => 'EUC-KR',
            'ks_c_5601-1987'           => 'EUC-KR',
            'iso-ir-149'               => 'EUC-KR',
            'ks_c_5601-1989'           => 'EUC-KR',
            'ksc_5601'                 => 'EUC-KR',
            'ksc5601'                  => 'EUC-KR',
            'korean'                   => 'EUC-KR',
            'csksc56011987'            => 'EUC-KR',
            '5601'                     => 'EUC-KR',
            'windows-949'              => 'EUC-KR',
            //
            // Aliases for GB2312",
            //
            // The following are really not aliases GB2312, add them only for MS FrontPage",
            'gb_2312-80'               => 'GB2312',
            'iso-ir-58'                => 'GB2312',
            'chinese'                  => 'GB2312',
            'csiso58gb231280'          => 'GB2312',
            'csgb2312'                 => 'GB2312',
            'zh_cn.euc'                => 'GB2312',
            // Sun Solaris",
            'gb_2312'                  => 'GB2312',
            //
            // Aliases for windows-125x ",
            //
            'x-cp1250'                 => 'windows-1250',
            'x-cp1251'                 => 'windows-1251',
            'x-cp1252'                 => 'windows-1252',
            'x-cp1253'                 => 'windows-1253',
            'x-cp1254'                 => 'windows-1254',
            'x-cp1255'                 => 'windows-1255',
            'x-cp1256'                 => 'windows-1256',
            'x-cp1257'                 => 'windows-1257',
            'x-cp1258'                 => 'windows-1258',
            //
            // Aliases for windows-874 ",
            //
            'windows-874'              => 'windows-874',
            'ibm874'                   => 'windows-874',
            'dos-874'                  => 'windows-874',
            //
            // Aliases for macintosh",
            //
            'macintosh'                => 'macintosh',
            'x-mac-roman'              => 'macintosh',
            'mac'                      => 'macintosh',
            'csmacintosh'              => 'macintosh',
            //
            // Aliases for IBM866",
            //
            'cp866'                    => 'IBM866',
            'cp-866'                   => 'IBM866',
            '866'                      => 'IBM866',
            'csibm866'                 => 'IBM866',
            //
            // Aliases for IBM850",
            //
            'cp850'                    => 'IBM850',
            'cp-850'                    => 'IBM850',
            '850'                      => 'IBM850',
            'csibm850'                 => 'IBM850',
            //
            // Aliases for IBM852",
            //
            'cp852'                    => 'IBM852',
            'cp-852'                    => 'IBM852',
            '852'                      => 'IBM852',
            'csibm852'                 => 'IBM852',
            //
            // Aliases for IBM855",
            //
            'cp855'                    => 'IBM855',
            'cp-855'                    => 'IBM855',
            '855'                      => 'IBM855',
            'csibm855'                 => 'IBM855',
            //
            // Aliases for IBM857",
            //
            'cp857'                    => 'IBM857',
            'cp-857'                    => 'IBM857',
            '857'                      => 'IBM857',
            'csibm857'                 => 'IBM857',
            //
            // Aliases for IBM862",
            //
            'cp862'                    => 'IBM862',
            'cp-862'                    => 'IBM862',
            '862'                      => 'IBM862',
            'csibm862'                 => 'IBM862',
            //
            // Aliases for IBM864",
            //
            'cp864'                    => 'IBM864',
            'cp-864'                    => 'IBM864',
            '864'                      => 'IBM864',
            'csibm864'                 => 'IBM864',
            'ibm-864'                  => 'IBM864',
            //
            // Aliases for T.61-8bit",
            //
            't.61'                     => 'T.61-8bit',
            'iso-ir-103'               => 'T.61-8bit',
            'csiso103t618bit'          => 'T.61-8bit',
            //
            // Aliases for UTF-7",
            //
            'x-unicode-2-0-utf-7'      => 'UTF-7',
            'unicode-2-0-utf-7'        => 'UTF-7',
            'unicode-1-1-utf-7'        => 'UTF-7',
            'csunicode11utf7'          => 'UTF-7',
            //
            // Aliases for ISO-10646-UCS-2",
            //
            'csunicode'                => 'UTF-16BE',
            'csunicode11'              => 'UTF-16BE',
            'iso-10646-ucs-basic'      => 'UTF-16BE',
            'csunicodeascii'           => 'UTF-16BE',
            'iso-10646-unicode-latin1' => 'UTF-16BE',
            'csunicodelatin1'          => 'UTF-16BE',
            'iso-10646'                => 'UTF-16BE',
            'iso-10646-j-1'            => 'UTF-16BE',
            //
            // Aliases for ISO-8859-10",
            //
            'latin6'                   => 'ISO-8859-10',
            'iso-ir-157'               => 'ISO-8859-10',
            'l6'                       => 'ISO-8859-10',
            // Currently .properties cannot handle : in key",
            //iso_8859-10:1992" => "ISO-8859-10",
            'csisolatin6'              => 'ISO-8859-10',
            //
            // Aliases for ISO-8859-15",
            //
            'iso_8859-15'              => 'ISO-8859-15',
            'csisolatin9'              => 'ISO-8859-15',
            'l9'                       => 'ISO-8859-15',
            //
            // Aliases for ISO-IR-111",
            //
            'ecma-cyrillic'            => 'ISO-IR-111',
            'csiso111ecmacyrillic'     => 'ISO-IR-111',
            //
            // Aliases for ISO-2022-KR",
            //
            'csiso2022kr'              => 'ISO-2022-KR',
            //
            // Aliases for VISCII",
            //
            'csviscii'                 => 'VISCII',
            //
            // Aliases for x-euc-tw",
            //
            'zh_tw-euc'                => 'x-euc-tw',
            //
            // Following names appears in unix nl_langinfo(CODESET)",
            // They can be compiled as platform specific if necessary",
            // DONT put things here if it does not look generic enough (like hp15CN)",
            //
            'iso88591'                 => 'ISO-8859-1',
            'iso88592'                 => 'ISO-8859-2',
            'iso88593'                 => 'ISO-8859-3',
            'iso88594'                 => 'ISO-8859-4',
            'iso88595'                 => 'ISO-8859-5',
            'iso88596'                 => 'ISO-8859-6',
            'iso88597'                 => 'ISO-8859-7',
            'iso88598'                 => 'ISO-8859-8',
            'iso88599'                 => 'ISO-8859-9',
            'iso885910'                => 'ISO-8859-10',
            'iso885911'                => 'ISO-8859-11',
            'iso885912'                => 'ISO-8859-12',
            'iso885913'                => 'ISO-8859-13',
            'iso885914'                => 'ISO-8859-14',
            'iso885915'                => 'ISO-8859-15',
            'cp1250'                   => 'windows-1250',
            'cp1251'                   => 'windows-1251',
            'cp1252'                   => 'windows-1252',
            'cp1253'                   => 'windows-1253',
            'cp1254'                   => 'windows-1254',
            'cp1255'                   => 'windows-1255',
            'cp1256'                   => 'windows-1256',
            'cp1257'                   => 'windows-1257',
            'cp1258'                   => 'windows-1258',
            'x-gbk'                    => 'gbk',
            'windows-936'              => 'gbk',
            'ansi-1251'                => 'windows-1251',
        ];

        if (isset($aliases[strtolower($encoding)])) {
            return $aliases[strtolower($encoding)];
        } else {
            return $encoding;
        }
    }

    /**
     * Render partner code from partner Id.
     *
     * @param $partnerId
     * @return string
     */
    public static function renderPartnerCode($partnerId)
    {
        return 'P'.strval($partnerId);
    }

    /**
     * Check string is valid email.
     *
     * @param $str
     * @return bool
     */
    public static function isValidEmail($str)
    {
        if (! (is_string($str) && ! empty($str))) {
            return false;
        }

        return (! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? false : true;
    }

    /**
     * Check string has multiple bytes of specific encode.
     *
     * @param string $str
     * @param string $encode
     * @return bool
     */
    public static function hasMultipleBytes(string $str, $encode = 'SJIS')
    {
        return ! mb_check_encoding($str, 'ASCII') && (mb_check_encoding($str, $encode) || mb_check_encoding($str, 'UTF-8'));
    }

    /**
     * Extract partner Id from order number at wk_order_partner.
     *
     * @param string $orderCode
     * @return int|null
     */
    public static function extractPartnerIdByWkOrderCode(string $orderCode)
    {
        $partnerId = null;
        if (empty($orderCode)) {
            return $partnerId;
        }

        if (is_string($orderCode)) {
            $orderCode = trim($orderCode);
        }

        if (self::hasMultipleBytes($orderCode)) {
            $endPos = \mb_strpos($orderCode, '-');

            // We skip first character
            $partnerId = $endPos !== false ? \mb_substr($orderCode, 1, ($endPos - 1)) : null;
        } else {
            $endPos = strpos($orderCode, '-');

            // We skip first character
            $partnerId = $endPos !== false ? substr($orderCode, 1, ($endPos - 1)) : null;
        }

        return is_string($partnerId) ? intval($partnerId) : null;
    }

    /**
     * getGroupingStringInArray.
     *
     * @param array $orderCode
     * @return string
     */
    public static function getGroupingStringInArray(array $data, $join = ',', $sign = 'x')
    {
        $sign = (string) $sign;
        $join = (string) $join;

        $collection = collect($data);
        $grouped = $collection->groupBy(function ($item, $key) use ($sign) {
            $tempNameArr = explode($sign, $item);
            $tempQuantity = (int) $tempNameArr[count($tempNameArr) - 1];
            if ($tempQuantity <= 0) {
                return $item;
            }
            $tempIndex = strlen((string) $tempQuantity) + 1;

            return substr($item, 0, -$tempIndex);
        });

        $groups = [];
        foreach ($grouped as $stringKey => $groupedItem) {
            $tempValue = $groupedItem->transform(function ($item, $key) use ($sign) {
                $tempNameArr = explode($sign, $item);
                $tempQuantity = (int) $tempNameArr[count($tempNameArr) - 1];
                if ($tempQuantity <= 0) {
                    return 0;
                }
                $tempIndex = $tempQuantity > 0 ? strlen((string) $tempQuantity) : 0;
                $tempResult = substr($item, -$tempIndex);

                return (int) substr($item, -$tempIndex);
            });

            if ($tempValue->count() == 1) {
                if ($tempValue[0] == 0) {
                    $groups[] = $stringKey;
                } else {
                    $groups[] = $stringKey.$sign.$tempValue[0];
                }
            } else {
                $countValue = $tempValue->sum(function ($item) {
                    return $item == 0 ? 1 : $item;
                });

                $groups[] = $stringKey.$sign.$countValue;
            }
        }

        $result = '';
        if (! empty($groups)) {
            $result = implode($join, $groups);
        }

        return $result;
    }

    /**
     * Remove multiple byte characters in string.
     *
     * @param string $str
     * @return mixed
     */
    public static function removeMultipleBytesChar(string $str)
    {
        return filter_var($str, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    }

    /**
     * Remove dash in string.
     *
     * @param string $str
     * @return mixed
     */
    public static function removeDash(string $str)
    {
        $replaceOf = ['-', 'ー', ' ', '　'];
        $replaceBy = ['', '', '', ''];

        return \str_replace($replaceOf, $replaceBy, $str);
    }

    /**
     * Trim all string item in string array.
     *
     * @param array $stringArray
     * @return array|null;
     */
    public static function trimStringArray($stringArray)
    {
        if (! is_array($stringArray)) {
            return;
        }
        foreach ($stringArray as &$strItem) {
            if (is_string($strItem)) {
                $strItem = trim($strItem);
            }
        }

        return $stringArray;
    }

    /**
     * Reformat delivery time to delivery time zone code format
     * The format has number only.
     *
     * Return null when passed parameter type is not string
     *
     * @param string $deliveryTimeZoneCode
     * @return string|null
     */
    public static function formatDeliveryTimeZoneOnlyNumber($deliveryTimeZoneCode)
    {
        if (is_string($deliveryTimeZoneCode)) {
            return str_replace([':00', '-'], ['', ''], $deliveryTimeZoneCode);
        }
    }

    // dashesToCamelCase
    public static function dashesToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace('-', '', ucwords($string, '-'));
        if (! $capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }

    // isEmptyString
    public static function isEmptyString($string = '')
    {
        if (! isset($string)) {
            return true;
        }
        $str = strval($string);
        $str = trim($str);
        if (empty($str)) {
            return true;
        }

        return false;
    }

    //convertSnakeToKebab
    public static function convertSnakeToKebab($string = '')
    {
        return str_replace('_', '-', $string);
    }
}
