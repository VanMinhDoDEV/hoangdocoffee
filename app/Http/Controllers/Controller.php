<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Update the shared activity log file for real-time polling.
     * 
     * @param string $type 'order', 'comment', or 'review'
     * @param int $id The new ID
     */
    protected function updateActivityLog($type, $id)
    {
        $path = storage_path('latest_activity.json');
        
        // Use a simple retry mechanism for locking
        $fp = fopen($path, 'c+');
        if (flock($fp, LOCK_EX)) {
            // Read current content
            $size = filesize($path);
            $content = $size > 0 ? fread($fp, $size) : '{}';
            $data = json_decode($content, true) ?? [];
            
            // Update only the specific type
            $data[$type] = $id;
            
            // Write back
            ftruncate($fp, 0);
            rewind($fp);
            fwrite($fp, json_encode($data));
            fflush($fp);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }
}
