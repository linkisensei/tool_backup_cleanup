<?php namespace tool_backup_cleanup;

defined('MOODLE_INTERNAL') || die();

global $CFG;

use \advanced_testcase;
use \ReflectionMethod;
use \tool_backup_cleanup\task\cleanup_task;

class task_test extends advanced_testcase{

    public function setUp(): void {
        $this->resetAfterTest(true);
        $this->setAdminUser();
    }

    protected function insert_fake_backup_file(string $component, string $filearea, int $timecreated) : object {
        global $DB;

        $record = [
            'contenthash' => uniqid(uniqid()),
            'pathnamehash' => uniqid(uniqid()),
            'contextid' => 0,
            'component' => $component,
            'filearea' => $filearea,
            'itemid' => 0,
            'filepath' => '/',
            'filename' => uniqid($component . '-backup-') . '.zip',
            'mimetype' => 'application/vnd.moodle.backup',
            'filesize' => 183155000,
            'timecreated' => $timecreated,
            'timemodified' => $timecreated,
        ];

        $record['id'] = $DB->insert_record('files', $record);
        return (object) $record;
    } 

    /**
     * Tests the `get_expired_backups_recordset` method to ensure that it correctly retrieves
     * expired backup files based on the configured time-to-live (TTL) settings.
     * 
     * This test:
     * - Configures the tool with different TTL values for course, activity, and private backups.
     * - Inserts test backup files, some expired and some still valid.
     * - Verifies that only expired backup files are returned.
     * - Ensures that no non-expired files are included in the result.
     */
    public function test_get_expired_backups_recordset(){
        set_config('enabled', 1, 'tool_backup_cleanup');
        set_config('course_backups_ttl', 10 * DAYSECS, 'tool_backup_cleanup');
        set_config('activity_backups_ttl', 5 * DAYSECS, 'tool_backup_cleanup');
        set_config('private_backups_ttl', 5 * DAYSECS, 'tool_backup_cleanup');

        // Inserting fake files
        $expired_files = [];

        $record = $this->insert_fake_backup_file('user', 'backup', strtotime('-60 days'));
        $expired_files[$record->id] = $record;

        $record = $this->insert_fake_backup_file('user', 'backup', strtotime('-6 days'));
        $expired_files[$record->id] = $record;

        $record = $this->insert_fake_backup_file('backup', 'course', strtotime('-15 days'));
        $expired_files[$record->id] = $record;

        $record = $this->insert_fake_backup_file('backup', 'activity', strtotime('-10 days'));
        $expired_files[$record->id] = $record;

        $this->insert_fake_backup_file('user', 'backup', strtotime('-1 days'));
        $this->insert_fake_backup_file('backup', 'course', strtotime('-1 days'));
        $this->insert_fake_backup_file('backup', 'activity', strtotime('-1 days'));
        
        // Testing recordset
        $task = new cleanup_task();
        $reflection = new ReflectionMethod($task, 'get_expired_backups_recordset');
        $reflection->setAccessible(true);
        $recordset = $reflection->invoke($task);

        foreach ($recordset as $record) {
            $this->assertArrayHasKey($record->id, $expired_files, 'Returned not expired files');
            unset($expired_files[$record->id]);
        }

        $this->assertCount(0, $expired_files, "Not all expired_files were returned");
    }
}