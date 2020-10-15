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
<div class="jco-ue2bb">
<h1>Convert UserEcho to bbPress</h1>
<?php if ( ! Jco_Userecho2bbpress_Admin::data_files_exist() ) { ?>
  <p> Add your UserEcho forums export files in JSON format to the data directory. There should be four files:
  <ul>
    <li>topics.json</li>
    <li>comments.json</li>
    <li>forums.json</li>
    <li>users.json</li>
  </ul></p>
<?php } elseif (! isset( $_GET['jco'] ) ) { //If GET is not set, we're on step 1 ?>
      <h2>Public Forums</h2>
    <?php
     echo Jco_Userecho2bbpress_Admin::display_forum_data();
     ?>
     <h2>Step 1: Select Forum</h2>
     <?php
     echo Jco_Userecho2bbpress_Admin::display_forum_selector_form();
  } elseif ( $_GET['jco']['step'] == 2 ){ ?>
    <h2>Step 2: Map Categories to Forums</h2>
  <?php
    echo Jco_Userecho2bbpress_Admin::display_topic_mapping_form($_GET['jco']['forum_id']);
    //var_dump($var);
  } elseif ( $_GET['jco']['step'] == 3 ) { ?>
    <h3>Step 3: Preview</h3>
  <?php
    var_dump( $_GET );
  }
  ?>
</div>
