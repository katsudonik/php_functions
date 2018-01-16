<?php

class Validation {
    /**
     * 必須チェック（API共通）
     * @param array   $data  チェック対象データ配列
     * @param boolean $isAll 全データ必須チェックフラグ
     * @param array   $keys  チェック対象データキー名
     * @return boolean true:正常 false:必須チェックエラー
     */
    public function requireChk($data, $keys) {
        foreach ($data as $key => $value) {
            // チェック対象の場合
            if (in_array($key, $keys)) {
                if (empty($value)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 型（数字）チェック
     * @param array   $data  チェック対象データ配列
     * @param array   $keys  チェック対象データキー名
     * @return boolean true:正常 false:型（数字）チェックエラー
     */
    public function typeDigitChk($data, $keys) {
        // 全データが必須チェック対象の場合
        foreach ($data as $key => $value) {
            // チェック対象の場合
            if (in_array($key, $keys)) {
                if (!empty($value) && !ctype_digit($value)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 形式チェック
     * @param array   $data  チェック対象データ配列
     * @param array   $keys  チェック対象データキー名
     * @param string  $matcheStr  正規表現チェック文字列
     * @return boolean true:正常 false:型（数字）チェックエラー
     */
    public function formatChk($data, $keys, $matcheStr) {
        // 全データが必須チェック対象の場合
        foreach ($data as $key => $value) {
            // チェック対象の場合
            if (in_array($key, $keys)) {
                if (!empty($value) && !preg_match($matcheStr, $value)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 範囲チェック
     * @param array   $data  チェック対象データ配列
     * @param array   $keys  チェック対象データキー名
     * @return boolean true:正常 false:型（数字）チェックエラー
     */
    public function rangeChk($data, $keys, $min, $max = null) {
        // 全データが必須チェック対象の場合
        foreach ($data as $key => $value) {
            // チェック対象の場合
            if (!is_array($value) && mb_strlen($value) > 0 && in_array($key, $keys)) {
                if ($value < $min) {
                    return false;
                }
                if (!is_null($max) && $value > $max) {
                    return false;
                }
            }
        }
        return true;
    }

    public function hasEmoji($data, $keys) {
        // 全データが必須チェック対象の場合
        foreach ($data as $key => $value) {
            // チェック対象の場合
            if (in_array($key, $keys)) {
                $textwithout4byte = preg_replace('/[\xF0-\xF7][\x80-\xBF][\x80-\xBF][\x80-\xBF]/', '', $value);
                if (!empty($value) && ($textwithout4byte != $value)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 日付YYYYMMDDフォーマットチェック
     * @param  string  $date チェック対象データ
     * @return boolean true:正常 false:フォーマットエラー
     */
    public function dateFormatChkForYmd($date) {
        return $this->__dateFormatChk($date, 'Y-m-d');
    }

    /**
     * 日付YYYYMMフォーマットチェック
     * @param  string  $date チェック対象データ
     * @return boolean true:正常 false:フォーマットエラー
     */
    public function dateFormatChkForYm($date) {
        return $this->__dateFormatChk($date.'01', 'Ym', $date);
    }

    /**
     * 日付YYYYMMDD HH:ii:ssフォーマットチェック
     * @param  string  $date チェック対象データ
     * @return boolean true:正常 false:フォーマットエラー
     */
    public function dateFormatChkForYmdhis($date) {
        return $this->__dateFormatChk($date, 'Y-m-d h:i:s');
    }

    /**
     * 日付フォーマットチェック
     * @param string   $date   日付
     * @param string   $format フォーマット
     * @return boolean true:正常 false:型（数字）チェックエラー
     */
    private function __dateFormatChk($date, $format, $plainDate = null) {
        if ($plainDate != null) {
            return $plainDate === date($format, strtotime($date));
        }
        return $date === date($format, strtotime($date));
    }

    /**
     * 画像ファイル形式チェック
     * @param string $imgPath
     */
    public function isImgTypeChek($imgPath) {
        $type = exif_imagetype($imgPath);
        if ($type !== IMAGETYPE_JPEG && $type !== IMAGETYPE_PNG && $type !== IMAGETYPE_GIF) {
            return false;
        }
        return true;
    }
}
