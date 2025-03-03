<?php

defined('MOODLE_INTERNAL') || die;

if($hassiteconfig){
    $settingspage = new admin_settingpage(
        'tool_backup_cleanup_settings',
        new lang_string('settings:title', 'tool_backup_cleanup')
    );

    if ($ADMIN->fulltree) {
        $name = 'enabled';
        $settingspage->add(
            new admin_setting_configcheckbox(
                "tool_backup_cleanup/$name",
                    new lang_string("settings:$name", 'tool_backup_cleanup'),
                    new lang_string("settings:$name" . '_desc', 'tool_backup_cleanup'),
                1
            )
        );
    
        $name = 'course_backups_ttl';
        $settingspage->add(
            new admin_setting_configduration(
                "tool_backup_cleanup/$name",
                new lang_string("settings:$name", 'tool_backup_cleanup'),
                '',
                30 * DAYSECS,
                DAYSECS
            )
        );

        $name = 'activity_backups_ttl';
        $settingspage->add(
            new admin_setting_configduration(
                "tool_backup_cleanup/$name",
                new lang_string("settings:$name", 'tool_backup_cleanup'),
                '',
                7 * DAYSECS,
                DAYSECS
            )
        );
        
        $name = 'private_backups_ttl';
        $settingspage->add(
            new admin_setting_configduration(
                "tool_backup_cleanup/$name",
                new lang_string("settings:$name", 'tool_backup_cleanup'),
                '',
                7 * DAYSECS,
                DAYSECS
            )
        );
    }
    
    $ADMIN->add('tools', $settingspage);
}

