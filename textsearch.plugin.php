<?php
class textsearch extends Plugin
{
	/**
	 * function action_post_insert_after
	 * Invokes our thumbnail generating function when a new post is saved
	 * @param Post the post being added
	**/
	public function action_post_insert_after( $post )
	{
		$this->save_price( $post );
	}

	/**
	 * function action_post_update_after
	 * invokes our thumbnail generating function when a post is updated
	 * @param Post the post being updated
	**/
	public function action_post_update_after( $post )
	{
		$this->save_price( $post );
	}

	/**
	 * function save_price
	 * Determines whether a thumbnail needs to be created for this post, and adds it to the postinfo for this post
	 * @param Post the post for which the thumb should be generated
	**/
	public function save_price( $post )
	{
		// set up a temporary variable to capture the image tag(s) �\d{4} should do it
		$html = false;
		$matches= array();
		if ( preg_match( '/£[\d,.]*\d/', $post->content, $matches) ) {

			$price = $matches[0];
EventLog::log("found $price");
		}

		$thumb = $post->info->price;

		$post->info->price = $price;
		$post->info->commit();
	}
}
?>
