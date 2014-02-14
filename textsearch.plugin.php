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
 
		$matches= array();
		if ( preg_match( '£\d{1,4}', $post->content, $matches) ) {
			// we got one! Now tease out the src element
			$html= new HTMLTokenizer( $matches[0] );
/* need to check what's in $html. Should be something like £1234 */

			$tokens= $html->parse();
			foreach ($tokens as $node ) {
				if ( 'img' == $node['name'] ) {
					$elements= $node['attrs'];
				}
			}
		}
		//if ( ! isset( $elements['src'] ) ) {
			//// no src= found, so don't try to do anything else
		//	return;
		//}

		$thumb= $post->info->price;

		if ( ! isset( $thumb ) ) {
			// no thumbnail exists for this post yet, so make one
			$post->info->textsearch_thumb= $this->make_thumbnail( $elements['src'] );
			$post->info->textsearch_md5= md5_file( $this->get_image_file( $elements['src'] ) );
			$post->info->commit();
		} else {
			// a thumbnail exists; we should check whether we need to update it
			if (true) { // ( md5_file( $this->get_image_file( $elements['src'] ) ) != $post->info->textsearch_md5 ) {
				// the image has a different MD5 sum than the
				// one we previously calculated for it, so
				// generate a new thumbnail
				$post->info->textsearch_thumb= $this->make_thumbnail( $elements['src'] );
				$post->info->textsearch_md5= md5_file( $this->get_image_file( $elements['src'] ) );
				$post->info->commit();
			}
		}
	}

	/**
	 * post_filter_content_excerpt_out
	 * filters the post's excerpt to display only the thumbnail
	**/
	public function filter_post_content_excerpt_out ( $excerpt, $post )
	{
		if (isset( $post->info->textsearch_thumb ) ) {
			return $post->info->textsearch_thumb;
		} else {
			return $excerpt;
		}
	}	
}
?>

