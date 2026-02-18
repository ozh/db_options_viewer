<?php
/*
 * Plugin Name: DB Options Viewer
 * Plugin URI:  https://github.com/ozh/db_options_viewer/
 * Description: Displays all options stored in the YOURLS database
 * Version:     1.0
 * Author:      Ozh
 */

// Prevent direct access
if (!defined('YOURLS_ABSPATH')) die();

yourls_add_action('plugins_loaded', 'dboptview_init');

function dboptview_init() {
    yourls_register_plugin_page('dboptview', 'DB Options Viewer', 'dboptview_page');
}

function dboptview_page() {
    echo '<h2>DB Options Viewer</h2>';

    $ydb = yourls_get_db('read-options');

    $table = YOURLS_DB_TABLE_OPTIONS;

    try {
        $rows = $ydb->fetchObjects("SELECT option_id, option_name, option_value FROM `$table` ORDER BY option_id ASC");
    } catch (Exception $e) {
        echo '<p style="color:red;">Query error: ' . yourls_esc_html($e->getMessage()) . '</p>';
        return;
    }

    if (empty($rows)) {
        echo '<p>No options found in the database.</p>';
        return;
    }

    echo '<p>' . count($rows) . ' option(s) found.</p>';
    echo '<style>
        #dboptview-table { border-collapse: collapse; width: 100%; font-size: 13px; }
        #dboptview-table th,
        #dboptview-table td { border: 1px solid #ccc; padding: 6px 10px; text-align: left; vertical-align: top; }
        #dboptview-table th { background: #f1f1f1; font-weight: bold; }
        #dboptview-table tr:nth-child(even) td { background: #fafafa; }
        #dboptview-table td.val { font-family: monospace; word-break: break-all; max-width: 600px; }
    </style>';

    echo '<table id="dboptview-table">';
    echo '<thead><tr><th>ID</th><th>Option name</th><th>Option value</th></tr></thead>';
    echo '<tbody>';

    foreach ($rows as $row) {
        echo '<tr>';
        echo '<td>' . yourls_esc_html($row->option_id) . '</td>';
        echo '<td>' . yourls_esc_html($row->option_name) . '</td>';
        echo '<td class="val">' . yourls_esc_html($row->option_value) . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
}
