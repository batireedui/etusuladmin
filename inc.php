<?php
session_start();
require_once __DIR__ . '/users/api/aaa.php';

function _select(&$stmt, &$count, $sql, $types, $sqlParams, &...$bindParams)
{
    global $conn;
    // mysqli_report(MYSQLI_REPORT_ALL);
    $stmt = mysqli_prepare($conn, $sql);
    if (!is_null($types)) {
        mysqli_stmt_bind_param($stmt, $types, ...$sqlParams);
    }
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $count = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_bind_result($stmt, ...$bindParams);
}

function _selectRow($sql, $types, $sqlParams, &...$bindParams)
{
    _select($stmt, $count, $sql, $types, $sqlParams, ...$bindParams);
    _fetch($stmt);
}

function _selectRowNoParam($sql, &...$bindParams)
{
    _select($stmt, $count, $sql, null, null, ...$bindParams);
    _fetch($stmt);
}

function _selectNoParam(&$stmt, &$count, $sql, &...$bindParams)
{
    _select($stmt, $count, $sql, null, null, ...$bindParams);
}

function _close_stmt($stmt)
{
    mysqli_stmt_close($stmt);
}

function _close($stmt = null)
{
    global $conn;

    if (!is_null($stmt)) {
        _close_stmt($stmt);
    }

    mysqli_close($conn);
}

function _fetch($stmt)
{
    return mysqli_stmt_fetch($stmt);
}

function _exec($sql, $types, $sqlParams, &$count)
{
    global $conn;
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, $types, ...$sqlParams);
    $success = mysqli_stmt_execute($stmt);
    $count = mysqli_stmt_insert_id($stmt);
    //$count = mysqli_stmt_affected_rows($stmt);
    return $success;
}

function redirect($url)
{
    header("Location: $url");
    exit;
}