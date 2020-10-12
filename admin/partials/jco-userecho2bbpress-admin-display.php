<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       boldgrid.com
 * @since      1.0.0
 *
 * @package    Jco_Userecho2bbpress
 * @subpackage Jco_Userecho2bbpress/admin/partials
 */
?>

<h1>Convert UserEcho to bbPress</h1>
<?php if ( ! Jco_Userecho2bbpress_Admin::data_files_exist() ) { ?>
  <p> Add your UserEcho forums export files in JSON format to the data directory. There should be four files:
  <ul>
    <li>topics.json</li>
    <li>comments.json</li>
    <li>forums.json</li>
    <li>users.json</li>
  </ul></p>
<?php } elseif (! isset($_POST['submit_forum_selection']) ) { ?>
      <h2>Public Forums</h2>
    <?php
     echo Jco_Userecho2bbpress_Admin::display_forum_data();
     ?>
     <h2>Step 1: Select Forum</h2>
     <?php
     echo Jco_Userecho2bbpress_Admin::display_forum_selector_form();
  } ?>
