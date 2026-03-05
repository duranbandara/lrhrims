<?php

if (! function_exists('is_admin')) {
    function is_admin(): bool
    {
        return session()->get('role') === 'admin';
    }
}

if (! function_exists('userdata')) {
    function userdata(string $key = ''): mixed
    {
        if ($key === '') {
            return session()->get();
        }
        return session()->get($key);
    }
}

if (! function_exists('expiry_class')) {
    function expiry_class($daysLeft): string
    {
        if ($daysLeft === null || $daysLeft === '') return 'secondary';
        $d = (int)$daysLeft;
        if ($d < 0)   return 'danger';
        if ($d <= 30) return 'warning';
        if ($d <= 60) return 'info';
        return 'success';
    }
}

if (! function_exists('expiry_label')) {
    function expiry_label($daysLeft): string
    {
        if ($daysLeft === null || $daysLeft === '') return 'No lots';
        $d = (int)$daysLeft;
        if ($d < 0)  return 'EXPIRED';
        if ($d === 0) return 'Today';
        return $d . 'd';
    }
}
