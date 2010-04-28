<?php

/**
 * CodeIgniter Flickr Library (http://www.haughin.com/code/lastfm)
 * 
 * Author: Elliot Haughin (http://www.haughin.com), elliot@haughin.com
 *
 * ========================================================
 * REQUIRES: Simplepie RSS Parser Library (http://www.haughin.com/code/simplepie)
 * ========================================================
 * 
 * Description:
 * Gets the latest flickr photos for a user, group, or the public.
 * Can specify tags, size, and cache options.
 * 
 * Get full documentation here: http://www.haughin.com/code/flickr
 * 
 * VERSION: 1.0 (2008-02-10)
 * 
 **/


class Flickr {
	
	var $type		= 'public';
	var $num_items	= 30;
	var $tags		= NULL;
	var $image_size	= 'square';
	var $user		= NULL;
	var $use_cache	= FALSE;
	var $cache_path	= NULL;
	var $cache_url	= NULL;
	var $rss_cache_path;
	var $cache_time = 3600;
	
	function Flickr()
	{
		$this->obj =& get_instance();
		$this->obj->load->library('simplepie');
	}
	
	function init($config)
	{
		foreach ($config as $key => $value)
		{
			$this->$key = $value;
		}
	}
	
	function get_photos()
	{
		$rss_entries = $this->get_rss_entries();
		
		if ( !empty($rss_entries) )
		{
			$rss_entries = array_slice($rss_entries, 0, $this->num_items);
			
			$images = array();
			
			foreach ($rss_entries as $item)
			{
				
				if ( preg_match('<img src="([^"]*)" [^/]*/>', $item->get_description(), $imgUrlMatches) )
				{
					$imgurl = $imgUrlMatches[1];
					
					switch ($this->image_size)
					{
						case 'square':
							$imgurl = str_replace("m.jpg", "s.jpg", $imgurl);
						break;
						
						case 'thumbnail':
							$imgurl = str_replace("m.jpg", "t.jpg", $imgurl);
						break;
						
						case 'medium':
							$imgurl = str_replace("_m.jpg", ".jpg", $imgurl);
						break;
					}
					
					preg_match('<http://farm[0-9]{0,3}\.static.flickr\.com/\d+?\/([^.]*)\.jpg>', $imgurl, $flickr_slug_matches);
					$flickr_slug = $flickr_slug_matches[1];
					
					if ( $this->use_cache )
					{
						if ( $this->cache_image($imgurl, $flickr_slug) )
						{
							$imgurl = $this->cache_url.'/'.$flickr_slug.'.jpg';
						}
					}
					
					$title	= htmlspecialchars( stripslashes( $item->get_title('title') ) );
					$url	= $item->get_permalink();
					
					$images[] = array(
									'title'		=> $title,
									'url'		=> $url,
									'image_url'	=> $imgurl
								);
					
				}
			}
			
			return $images;
		}
	}

	function get_rss_entries()
	{
		switch ($this->type)
		{
			case 'user':
				$rss_url = 'http://api.flickr.com/services/feeds/photos_public.gne?id=' . $this->user . '&tags=' . $this->tags . '&format=rss_200';
			break;
			
			case 'group':
				$rss_url = 'http://api.flickr.com/services/feeds/groups_pool.gne?id=' . $this->user . '&format=rss_200';
			break;
			
			case 'community':
				$rss_url = 'http://api.flickr.com/services/feeds/photos_public.gne?tags=' . $this->tags . '&format=rss_200';
			break;
		}
		
		if ( empty($rss_url) )
		{
			echo 'Flickr Library is not set up correctly!';
		}
		
		$this->obj->simplepie->cache_location = $this->rss_cache_path;
		$this->obj->simplepie->cache_duration = $this->cache_time;
		
		$this->obj->simplepie->set_feed_url($rss_url);
		$this->obj->simplepie->init();
		
		$this->obj->simplepie->handle_content_type();
		
		return $this->obj->simplepie->get_items();
	}
	
	function cache_image($image_url, $flickr_slug)
	{
		if (!file_exists($this->cache_path.'/'.$flickr_slug.'.jpg'))
		{
			if ( function_exists('curl_init') )
			{
				$curl			= curl_init();
				$local_image	= fopen($this->cache_path.'/'.$flickr_slug.'.jpg', 'wb');
				
				curl_setopt($curl, CURLOPT_URL, $image_url);
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
                curl_setopt($curl, CURLOPT_FILE, $local_image);
                curl_exec($curl);
                curl_close($curl);
			}
			else
			{
				$filedata = "";
                $remote_image = fopen($image_url, 'rb');
              	if ($remote_image)
				{
					while ( !feof($remote_image) )
					{
                     	$filedata.= fread($remote_image, 1024*8);
                   	 }
              	}

            	fclose($remote_image);

            	$local_image = fopen($this->cache_path.'/'.$flickr_slug.'.jpg', 'wb');
            	fwrite($local_image, $filedata);
            	fclose($local_image);
			}
		}
		
		if (file_exists($this->cache_path.'/'.$flickr_slug.'.jpg'))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

?>