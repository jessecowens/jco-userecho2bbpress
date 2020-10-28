<?php

/**
 * The UserEcho forums data model
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
  * @param    mixed Integer or String of Topic ID
  * @return   int   Number of Topics in forum $id
  */
  public function get_forum_topic_count( $id ) {
    $key = array_search( $id, array_column($this->forums, 'id') );
    return $this->forums[$key]['topic_count'];
  }

  /**
  * Retrieve category ID's, names, and their topic counts
  *
  * @since    1.0.0
  * @param    mixed Integer or String of Topic ID
  * @return   array   Keyvalue array of id, name, and topic_count
  */
  public function get_forum_categories( $id ) {
    $forum_categories = array();
    $key = array_search( $id, array_column( $this->forums, 'id' ) );
    foreach ( $this->forums[$key]['categories'] as $category ) {
      $forum_categories[] = array(
          'id' => $category['id'],
          'name' => $category['name'],
          'topic_count' => $category['topic_count'],
        );
    }
    return $forum_categories;
  }

  /**
  * Find out how many topics in a given forum do not have a category assigned.
  *
  * @since    1.0.0
  * @param    mixed Integer or String of Topic ID
  * @return   int   Number of Topics in forum $id
  */
  public function count_uncategorized_topics ( $forum_id ){
    $uncategorized_topics = 0;
    $forum_topics = array_keys( array_column( $this->topics, 'forum_id' ), $forum_id );
    foreach ( $forum_topics as $topic ) {
      if ( $this->topics[$topic]['category_id'] == '' ) {
        $uncategorized_topics++;
      }
    }
    return $uncategorized_topics;
  }

  public function get_topic_category( $id ) {
    $key = array_search( $id, array_column( $this->topics, 'id' ) );
    if ( isset( $this->topics[$key]['category_id'] ) ) {
      return $this->topics[$key]['category_id'];
    } else {
      return 0;
    }
  }

  public function get_topic_content( $topic_id ) {
    $key = array_search( $topic_id, array_column( $this->topics, 'id' ) );
    return $this->topics[$key]['description'];
  }

  public function get_reply_content( $reply_id ) {
    $key = array_search( $reply_id, array_column( $this->comments, 'id' ) );
    return $this->comments[$key]['comment'];
  }

  public function get_topic_title( $topic_id ) {
    $key = array_search( $topic_id, array_column( $this->topics, 'id' ) );
    return $this->topics[$key]['header'];
  }

  public function get_topic_date( $topic_id ) {
    $key = array_search( $topic_id, array_column( $this->topics, 'id' ) );
    return $this->topics[$key]['created'];
  }

  public function get_reply_date( $reply_id ) {
    $key = array_search( $reply_id, array_column( $this->comments, 'id' ) );
    return $this->comments[$key]['created'];
  }

  public function get_topic_reply_count( $topic_id ) {
    $key = array_search( $topic_id, array_column( $this->topics, 'id' ) );
    return $this->topics[$key]['comment_count'];
  }

  public function get_topic_slug( $topic_id ) {
    $key = array_search( $topic_id, array_column( $this->topics, 'id' ) );
    $slug = array();
    preg_match( '/[^\/]+$/', $this->topics[$key]['relative_url'], $slug );

    return $slug[0];
  }

  public function get_preview_topic( $forum_id ) {
    $key = array_search( $forum_id, array_column( $this->topics, 'forum_id' ) );
    return $this->topics[$key]['id'];
  }

  public function get_topic_author_name( $topic_id ) {
    $key = array_search( $topic_id, array_column( $this->topics, 'id' ) );
    $author_name = $this->get_user_name( $this->topics[$key]['author_id'] );
    return $author_name;
  }

  public function get_reply_author_name( $reply_id ) {
    $key = array_search( $reply_id, array_column( $this->comments, 'id' ) );
    $author_name = $this->get_user_name( $this->comments[$key]['author_id'] );
    return $author_name;
  }

  public function get_user_name( $user_id ) {
    $key = array_search( $user_id, array_column( $this->users, 'id' ) );
    return $this->users[$key]['name'];
  }

  public function get_topic_author_email( $topic_id ) {
    $key = array_search( $topic_id, array_column( $this->topics, 'id' ) ) ;
    $author_email = $this->get_user_email( $this->topics[$key]['author_id'] );
    return $author_email;
  }

  public function get_reply_author_email( $reply_id ) {
    $key = array_search( $reply_id, array_column( $this->comments, 'id' ) ) ;
    $author_email = $this->get_user_email( $this->comments[$key]['author_id'] );
    return $author_email;
  }

  public function get_user_email( $user_id ) {
    $key = array_search( $user_id, array_column( $this->users, 'id' ) );
    return $this->users[$key]['email'];
  }

  public function get_topic_author_website( $topic_id ) {
    $key = array_search( $topic_id, array_column( $this->topics, 'id' ) );
    $author_website = $this->get_user_website( $this->topics[$key]['author_id'] );
    return $author_website;
  }

  public function get_reply_author_website( $reply_id ) {
    $key = array_search( $reply_id, array_column( $this->comments, 'id' ) );
    $author_website = $this->get_user_website( $this->comments[$key]['author_id'] );
    return $author_website;
  }

  public function get_user_website( $user_id ) {
    $key = array_search( $user_id, array_column( $this->users, 'id' ) );
    return $this->users[$key]['admin_url'];
  }

  public function get_all_replies( $topic_id ) {
    $replies = array_keys( array_column( $this->comments, 'topic_id' ), $topic_id );
    $reply_ids = array();

    foreach ( $replies as $reply ) {
      $reply_ids[] = $this->comments[$reply]['id'];
    }

    return $reply_ids;
  }

  public function get_reply_privacy( $reply_id ) {
    $key = array_search( $reply_id, array_column( $this->comments, 'id') );
    return $this->comments[$key]['privacy_mode'];
  }
}
