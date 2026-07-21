<?php
// api/helpers.php

function jsonResponse($success, $data = [], $error = '') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'data' => $data,
        'error' => $error
    ]);
    exit;
}

function logActivity($pdo, $userId, $action, $module, $recordId, $description = '') {
    try {
        $stmt = $pdo->prepare("INSERT INTO activities (user_id, action, module, record_id, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $action, $module, $recordId, $description]);
        return true;
    } catch (Exception $e) {
        error_log("Failed to log activity: " . $e->getMessage());
        return false;
    }
}

function numberToWords($num) {
    $num = str_replace(array(',', ' '), '' , trim($num));
    if(! $num) return false;
    $num = (int) $num;
    $words = array();
    $list1 = array('', 'ONE', 'TWO', 'THREE', 'FOUR', 'FIVE', 'SIX', 'SEVEN', 'EIGHT', 'NINE', 'TEN', 'ELEVEN',
        'TWELVE', 'THIRTEEN', 'FOURTEEN', 'FIFTEEN', 'SIXTEEN', 'SEVENTEEN', 'EIGHTEEN', 'NINETEEN');
    $list2 = array('', 'TEN', 'TWENTY', 'THIRTY', 'FORTY', 'FIFTY', 'SIXTY', 'SEVENTY', 'EIGHTY', 'NINETY', 'HUNDRED');
    $list3 = array('', 'THOUSAND', 'MILLION', 'BILLION');
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);
    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' HUNDRED' . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ( $tens < 20 ) {
            $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
        } else {
            $tens = (int)($tens / 10);
            $tens = ' ' . $list2[$tens] . ' ';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = ' ' . $list1[$singles] . ' ';
        }
        $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
    }
    return implode(' ', $words);
}

function getAmountInWords($amount) {
    if (!$amount || $amount == 0) return 'ZERO ONLY';
    $amount = number_format((float)$amount, 2, '.', '');
    $parts = explode('.', $amount);
    $dirhams = numberToWords($parts[0]);
    $fils = numberToWords($parts[1]);
    
    $res = trim($dirhams);
    if ($parts[1] > 0) {
        $res .= ' AND ' . trim($fils) . ' FILS';
    }
    return preg_replace('/\s+/', ' ', $res . ' ONLY');
}
