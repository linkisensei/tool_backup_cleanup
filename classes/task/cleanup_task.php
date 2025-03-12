<?php namespace tool_backup_cleanup\task;

use \moodle_recordset;
use \core\task\scheduled_task;

class cleanup_task extends scheduled_task {

    const DEFAULT_BACKUP_TTL = 7 * DAYSECS;

    protected $config;
    protected $db;
    protected $fs;

    public function __construct(){
        global $DB;

        $this->config = get_config('tool_backup_cleanup');
        $this->db = $DB;
        $this->fs = get_file_storage();
    }

    public function get_name() {
        return get_string('task:cleanup_task', 'tool_backup_cleanup');
    }

    protected function get_config(string $name, $default = null){
        return empty($this->config->$name) ? $default : $this->config->$name;
    }

    protected function get_ttl_config(string $name) : int {
        return intval($this->get_config($name, self::DEFAULT_BACKUP_TTL));
    }

    public function execute(){
        if(!$this->get_config('enabled', false)){
            mtrace("Manual backup cleanup task disabled...");
        }
        $recordset = $this->get_expired_backups_recordset();

        foreach ($recordset as $record) {
            $filename  = rtrim($record->filepath, '/') . $record->filename;
            $file_identifier = "$record->component/$record->filearea/$record->itemid/$filename";

            try {
                $file = $this->fs->get_file_instance($record);
                $file->delete();

                mtrace("\"$file_identifier\" was removed (filesize = $record->filesize)");

            } catch (\Throwable $th) {
                mtrace("Error while removing \"$file_identifier\". " . $th->getMessage());
            }
        }

        $recordset->close();
    }

    protected function get_expired_backups_recordset() : moodle_recordset {
        $now = time();

        $params = [
            'course_backup_expires_at' => $now - $this->get_ttl_config('course_backups_ttl'),
            'private_backup_expires_at' => $now - $this->get_ttl_config('private_backups_ttl'),
            'activity_backup_expires_at' => $now - $this->get_ttl_config('activity_backups_ttl'),
        ];

        $sql = "SELECT *
                FROM {files}
                WHERE (component = 'user' AND filearea = 'backup' AND timecreated < :private_backup_expires_at)
                    OR (
                        component = 'backup'
                        AND (
                            (filearea = 'course' AND timecreated < :course_backup_expires_at)
                            OR (filearea = 'activity' AND timecreated < :activity_backup_expires_at)
                        )
                    )";

        return $this->db->get_recordset_sql($sql, $params);
    }
}
