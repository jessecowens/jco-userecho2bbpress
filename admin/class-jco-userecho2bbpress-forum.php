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

  /**
   * The base forum array
   *
   * @since    1.0.0
   * @access   private
   * @var      array    $forum    An array of all forums in the import.
  */
  private $forums;
  private $topics;
  private $comments;
  private $users;

  /**
   * Initialize the class and decode the import file
   *
   * @since    1.0.0
   * @param    string    $json_file      Path to the forums.json file
   */
  public function __construct( $json_path ) {
    $this->forums   = json_decode( file_get_contents( trailingslashit($json_path) . 'forums.json' ),    true );
    $this->topics   = json_decode( file_get_contents( trailingslashit($json_path) . 'topics.json' ),    true );
    $this->comments = json_decode( file_get_contents( trailingslashit($json_path) . 'comments.json' ),  true );
    $this->users    = json_decode( file_get_contents( trailingslashit($json_path) . 'users.json' ),     true );
  }

  /**
  * Get the full forum array utility
  *
  * @since    1.0.0
  * @return   array  An array of forums from the import
  */
  public function get_forum() {
    return $this->forums;
  }


  /**
  * Fetch an array of all the forums marked as PUBLIC
  *
  * @since    1.0.0
  * @return   array   Array of all public forums as id=>name
  */
  public function get_public_forums() {
    $public_forums = array();
    foreach ( $this->forums as $forum ) {
      if ( $forum['type']['name'] == 'PUBLIC' ) {
        $public_forums[$forum['id']] = $forum['name'];
      }
    }

    return $public_forums;
  }

  /**
  * Find out how many topics are in a given forum.
  *
  * @since    1.0.0
  * @return   int   Number of Topics in forum $id
  */
  public function get_forum_topic_count( $id ) {
    $key = array_search( $id, array_column($this->forums, 'id') );
    return $this->forums[$key]['topic_count'];
  }

  public function get_forum_categories( $id ) {
    $forum_categories = array();
    foreach ( $this->forums['categories'] as $category ) {
      $forum_categories[] = array(
        array(
          'id' => $category['id'],
          'name' => $category['name'],
        ),
      );
    }
  }

}
