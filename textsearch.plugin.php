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

	 * @param Post the post for which the price should be stored.
	**/
	public function save_price( $post )
	{
		// set up a temporary variable to capture the first price found
		$matches = array();
		if ( preg_match( '/£[\d,.]*\d/', $post->content, $matches) ) {

			$price = $matches[0];

		$thumb = $post->info->price;

		$post->info->price = $price;
		$post->info->commit();
		}
	}
}
?>
