<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       boldgrid.com
 * @since      1.0.0
 *
 * @package    Jco_Userecho2bbpress
 * @subpackage Jco_Userecho2bbpress/admin
 */

/**
 * Class representing a forums object.
 *
 * @package    Jco_Userecho2bbpress
 * @subpackage Jco_Userecho2bbpress/admin
 * @author     Jesse C Owens <jesseo@boldgrid.com>
 */
class Jco_Userecho2bbpress_Forum{

  private $forum;

  public function __construct( $json_file ) {
    $this->forum = json_decode( file_get_contents( $json_file ), true );
  }

  public function get_forum() {
    return $this->forum;
  }

  public function get_public_forums() {
    $public_forums = array();
    foreach ( $this->forum as $forum ) {
      if ( $forum['type']['name'] == 'PUBLIC' ) {
        $public_forums[$forum['id']] = $forum['name'];
      }
    }

    return $public_forums;
  }

  public function get_forum_topic_count( $id ) {
    $key = array_search( $id, array_column($this->forum, 'id') );
    return $this->forum[$key]['topic_count'];
  }

}
