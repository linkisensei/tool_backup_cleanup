<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version information for tool_backup_cleanup
 *
 * @package    tool_backup_cleanup
 * @copyright  2025 Lucas Barreto <lucas.b.fisica@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component    = 'tool_backup_cleanup';
$plugin->release      = '1.0';
$plugin->version      = 2025030300;
$plugin->requires     = 2019112200;
$plugin->supported    = [38, 405];
$plugin->maturity     = MATURITY_STABLE;
