# 🗑️ Moodle Backup Cleanup Tool

**Moodle Plugin - Automatic Cleanup of Manual Backups**

## 📌 Overview

The **Backup Cleanup Tool** is a scheduled task plugin for **Moodle** that automatically removes outdated manual backup files based on configurable retention periods. This helps optimize storage usage and maintain a clean backup environment.

## 🚀 Features

-   ✅ Scheduled task to delete expired backup files.
-   ✅ Configurable Time-To-Live (TTL) settings for different backup types:
    -   **Course backups** 🏫
    -   **Activity backups** 📌
    -   **Private backups** 🔒
-   ✅ Error handling and logging for removed files.
-   ✅ Supports Moodle **3.8 - 4.0+**.

## 🛠️ Installation

1.  **Download the plugin** or clone the repository into your Moodle tool directory:
    
    sh
    
    CopiarEditar
    
    `git clone https://github.com/your-repo/tool_backup_cleanup.git admin/tool/backup_cleanup` 
    
2.  **Visit the Moodle admin page** to complete the installation.
3.  **Configure settings** via `Site Administration > Plugins > Backup Cleanup`.
4.  **Enable the scheduled task** under `Site Administration > Server > Scheduled tasks`.

## ⚙️ Configuration

Go to `Site Administration > Plugins > Backup Cleanup` to set up:

| Setting                        | Description                                       |
|--------------------------------|---------------------------------------------------|
| **Enable**                     | Toggle the cleanup task on/off.                  |
| **Remove course backups older than**  | Define TTL for course backups (in seconds). |
| **Remove activity backups older than** | Define TTL for activity backups (in seconds). |
| **Remove private backups older than**  | Define TTL for private backups (in seconds). |

## 🔄 Scheduled Task Execution

The task runs automatically based on the Moodle scheduled task system.  
To trigger it manually, run:

`php admin/cli/scheduled_task.php --execute='\tool_backup_cleanup\task\cleanup_task'` 

## 📜 License

This plugin is released under the **GNU GPL v3**.