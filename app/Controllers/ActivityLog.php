<?php

namespace App\Controllers;

class ActivityLog extends BaseAppController
{
    public function index()
    {
        if (! is_admin()) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Access denied.');
        }

        return $this->render('activity_log/index', [
            'title' => 'Activity Log',
            'logs'  => $this->model->getActivityLogs(null, 50),
        ]);
    }

    public function deleteByRange()
    {
        if (! is_admin()) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Access denied.');
        }

        $date = $this->request->getPost('date');
        if (empty($date) || strpos($date, ' - ') === false) {
            return redirect()->to(base_url('activitylog'))->with('error', 'Please select a valid date range.');
        }

        [$start, $end] = array_map('trim', explode(' - ', $date));

        $this->model->deleteActivityLogsByRange(['start' => $start, 'end' => $end]);

        return redirect()->to(base_url('activitylog'))->with('message', "Activity logs from {$start} to {$end} have been deleted.");
    }
}
