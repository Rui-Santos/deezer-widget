<?php /*

**************************************************************************

Plugin Name:  Deezer Widget
Plugin URI:   http://wordpress.org/extend/plugins/deezer-widget/
Description:  Three widgets to play: playlist, album or radio from Deezer
Version:      1.0
Author:       Adrien P.
Author URI:   http://perraudeaua.com/
License:      GPLv2 or later
 
Copyright 2012 Adrien PERRAUDEAU

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

**************************************************************************/


/**
 * Register widgets
 */
add_action( 'widgets_init', create_function( '', 'register_widget( "Deezer_Playlist" );' ) );
add_action( 'widgets_init', create_function( '', 'register_widget( "Deezer_Radio" );' ) );
add_action( 'widgets_init', create_function( '', 'register_widget( "Deezer_Album" );' ) );


/**************************************************************************
 * 
 *                              Deezer Playlist
 * 
 **************************************************************************/

class Deezer_Playlist extends WP_Widget {

	function deezer_playlist(){
            $options = array(
                "classname" => "deezer-playlist",
                "description" => "Display your deezer playlist"
            );
            $this->WP_widget("deezer-playlist","Deezer Playlist",$options);
        }
        
        function widget($args, $instance) {
		
		extract( $args );
                
        $title = apply_filters( 'widget_title', esc_attr( $instance[ 'title' ]));
		$playlist_id = intval( $instance[ 'playlist_id' ]);
		$width = intval( $instance[ 'width' ] );
		$height = intval( $instance[ 'height' ] );
        $show_cover = ! empty( $instance['show_cover'] ) ? 'true' : 'false';
        $autoplay = ! empty( $instance['autoplay'] ) ? 'true' : 'false';
                
        echo $before_widget;
        echo $before_title.$title.$after_title;

        echo '<iframe 
              scrolling="no"
              frameborder="0"
              allowTransparency="true"
              src="http://www.deezer.com/en/plugins/player?autoplay='.$autoplay.'&playlist=true&width='.$width.'&height='.$height.'&cover='.$show_cover.'&type=playlist&id='.$playlist_id.'&title=&format=vertical" 
              width='.$width.' 
              height='.$height.'>
              </iframe>';
                        
         echo $after_widget;   
               
	}
        
 	public function form( $instance ) {
                        
 		$title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'My playlist', 'deezer-playlist' );
 		$playlist_id = isset( $instance[ 'playlist_id' ] ) ? $instance[ 'playlist_id' ] : '30595446';
        $width = isset( $instance[ 'width' ] ) ? $instance[ 'width' ] : 200;
 		$height = isset( $instance[ 'height' ] ) ? $instance[ 'height' ] : 600;
        $show_cover = $instance['show_cover'] ? 'checked="checked"' : '';
        $autoplay = $instance['autoplay'] ? 'checked="checked"' : '';

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'deezer-playlist' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'playlist_id' ); ?>"><?php _e( 'Playlist ID:', 'deezer-playlist' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'playlist_id' ); ?>" name="<?php echo $this->get_field_name( 'playlist_id' ); ?>" type="text" value="<?php echo esc_attr( $playlist_id ); ?>" />
		</p>

		<h4>Advanced Options</h4>
		<p>
			<label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Dimension (px): ', 'deezer-playlist' ); ?></label><br/>
                        <input size="5" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="text" value="<?php echo esc_attr( $width ); ?>" /> x 
                        <input size="5" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo esc_attr( $height ); ?>" />

		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php echo $show_cover; ?> id="<?php echo $this->get_field_id('show_cover'); ?>" name="<?php echo $this->get_field_name('show_cover'); ?>" /> 
                        <label for="<?php echo $this->get_field_id('show_cover'); ?>"><?php _e('Show cover '); ?></label><br/>			
			
                        <input class="checkbox" type="checkbox" <?php echo $autoplay; ?> id="<?php echo $this->get_field_id('autoplay'); ?>" name="<?php echo $this->get_field_name('autoplay'); ?>" /> 
                        <label for="<?php echo $this->get_field_id('autoplay'); ?>"><?php _e('Autoplay '); ?></label><br/>                        
                </p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
        $new_instance = wp_parse_args( (array) $new_instance, 
                    array( 
                        'title' => '', 
                        'playlist_id' => '', 
                        'width' => '', 
                        'height' => '', 
                    ) 
                );
                
		$instance[ 'title' ] = sanitize_text_field( $new_instance[ 'title' ] );
		$instance[ 'playlist_id' ] = intval( $new_instance[ 'playlist_id' ] );
        $instance[ 'width' ] = intval( $new_instance[ 'width' ] );
        $instance[ 'height' ] = intval( $new_instance[ 'height' ] );
        $instance['show_cover'] = $new_instance['show_cover'] ? 1 : 0;
		$instance['autoplay'] = $new_instance['autoplay'] ? 1 : 0;
	return $instance;
	}

}

/**************************************************************************
 * 
 *                              Deezer Radio
 * 
 **************************************************************************/
class Deezer_Radio extends WP_Widget {

	function deezer_radio(){
            $options = array(
                "classname" => "deezer-radio",
                "description" => "Display a deezer radio (no time limit)"
            );
            $this->WP_widget("deezer-radio","Deezer Radio",$options);
        }
        
        function widget($args, $instance) {
		extract( $args );
                
        $title = apply_filters( 'widget_title', esc_attr( $instance[ 'title' ]));
		$radio_id = intval($instance[ 'radio_id' ] );
		$width = intval( $instance[ 'width' ] );
		$height = intval( $instance[ 'height' ] );
        $show_cover = ! empty( $instance['show_cover'] ) ? 'true' : 'false';
        $autoplay = ! empty( $instance['autoplay'] ) ? 'true' : 'false';
               
        echo $before_widget;
        echo $before_title.$title.$after_title;

        echo '<iframe 
              scrolling="no" 
              frameborder="0" 
              allowTransparency="true" 
              src="http://www.deezer.com/en/plugins/player?autoplay='.$autoplay.'&playlist=true&width='.$width.'&height='.$height.'&cover='.$show_cover.'&type=radio&id=radio-'.$radio_id.'&title=&format=vertical" 
              width='.$width.' 
              height='.$height.'>
              </iframe>';
                        
        echo $after_widget;           
               
	}
        
 	public function form( $instance ) {
                        
 		$title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'My favorite radio', 'deezer-radio' );
 		$radio_id = isset( $instance[ 'radio_id' ] ) ? $instance[ 'radio_id' ] : '16';
        $width = isset( $instance[ 'width' ] ) ? $instance[ 'width' ] : 200;
 		$height = isset( $instance[ 'height' ] ) ? $instance[ 'height' ] : 600;
        $show_cover = $instance['show_cover'] ? 'checked="checked"' : '';
        $autoplay = $instance['autoplay'] ? 'checked="checked"' : '';

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'deezer-radio' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
                        <?php $radios = array(
                            array( 'id' => 16,  'name' => "Pop"),
                            array( 'id' => 39,  'name' => "Pop Folk"),
                            array( 'id' => 10,  'name' => "Rock"),
                            array( 'id' => 65,  'name' => "Blues"),
                            array( 'id' => 17,  'name' => "Hard"),
                            array( 'id' => 22,  'name' => "Metal"),
                            array( 'id' => 31,  'name' => "Punk"),
                            array( 'id' => 6,   'name' => "Electro"),
                            array( 'id' => 18,  'name' => "Dancefloor"),
                            array( 'id' => 19,  'name' => "Soul"),
                            array( 'id' => 21,  'name' => "Funk"),
                            array( 'id' => 25,  'name' => "Disco"),
                            array( 'id' => 33,  'name' => "R&B"),
                            array( 'id' => 20,  'name' => "Rap"),
                            array( 'id' => 64,  'name' => "Urban Style"),
                            array( 'id' => 88,  'name' => "World"),
                            array( 'id' => 108, 'name' => "Reggea, Ska & Dub"),
                            array( 'id' => 15,  'name' => "Jazz Vocal"),
                            array( 'id' => 116, 'name' => "Classic Vocal"),
                            array( 'id' => 117, 'name' => "Classic Instrumental"),
                            array( 'id' => 12,  'name' => "Chansons française"),
                            array( 'id' => 11,  'name' => "Variété française")
                            ); ?>
                            
			<label for="<?php echo $this->get_field_id( 'radio_id' ); ?>"><?php _e( 'Radio:', 'deezer-radio' ); ?></label>
            	<select id="<?php echo $this->get_field_id( 'radio_id' ); ?>" name="<?php echo $this->get_field_name( 'radio_id' ); ?>" >
                            <?php foreach($radios as $radio){ ?> 
                            <option value="<?php echo $radio['id']; ?>" 
                                <?php if($radio['id']==$radio_id) 
                                    echo "selected" ?> >
                                <?php echo $radio['name']; ?>
                            </option>
                            <?php } ?> 
                </select>
		</p>

		<h4>Advanced Options</h4>
		<p>
			<label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Dimension (px): ', 'deezer-radio' ); ?></label><br/>
                        <input size="5" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="text" value="<?php echo esc_attr( $width ); ?>" /> x 
                        <input size="5" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo esc_attr( $height ); ?>" />
		</p>
		<p>
						<input class="checkbox" type="checkbox" <?php echo $show_cover; ?> id="<?php echo $this->get_field_id('show_cover'); ?>" name="<?php echo $this->get_field_name('show_cover'); ?>" /> 
                        <label for="<?php echo $this->get_field_id('show_cover'); ?>"><?php _e('Show cover '); ?></label><br/>			
			
                        <input class="checkbox" type="checkbox" <?php echo $autoplay; ?> id="<?php echo $this->get_field_id('autoplay'); ?>" name="<?php echo $this->get_field_name('autoplay'); ?>" /> 
                        <label for="<?php echo $this->get_field_id('autoplay'); ?>"><?php _e('Autoplay '); ?></label><br/>                        
                </p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
        $new_instance = wp_parse_args( (array) $new_instance, 
                    array( 
                        'title' => '', 
                        'radio_id' => '', 
                        'width' => '', 
                        'height' => '', 
                    ) 
                );
                
		$instance[ 'title' ] = sanitize_text_field( $new_instance[ 'title' ] );
		$instance[ 'radio_id' ] = intval( $new_instance[ 'radio_id' ] );
        $instance[ 'width' ] = intval( $new_instance[ 'width' ] );
        $instance[ 'height' ] = intval( $new_instance[ 'height' ] );
        $instance['show_cover'] = $new_instance['show_cover'] ? 1 : 0;
		$instance['autoplay'] = $new_instance['autoplay'] ? 1 : 0;
	return $instance;
	}

}

/**************************************************************************
 * 
 *                              Deezer Album
 * 
 **************************************************************************/

class Deezer_Album extends WP_Widget {

	function deezer_album(){
            $options = array(
                "classname" => "deezer-album",
                "description" => "Display your favorite deezer album"
            );
            $this->WP_widget("deezer-album","Deezer Album",$options);
        }
        
        function widget($args, $instance) {
		extract( $args );
                
        $title = apply_filters( 'widget_title', esc_attr( $instance[ 'title' ]));
		$album_id = intval( $instance[ 'album_id' ]);
		$width = intval( $instance[ 'width' ] );
		$height = intval( $instance[ 'height' ] );
        $show_cover = ! empty( $instance['show_cover'] ) ? 'true' : 'false';
        $autoplay = ! empty( $instance['autoplay'] ) ? 'true' : 'false';
                
        echo $before_widget;
        echo $before_title.$title.$after_title;

        echo '<iframe 
              scrolling="no" 
              frameborder="0" 
              allowTransparency="true" 
              src="http://www.deezer.com/en/plugins/player?autoplay='.$autoplay.'&playlist=true&width='.$width.'&height='.$height.'&cover='.$show_cover.'&type=album&id='.$album_id.'&title=&format=vertical" 
              width='.$width.'
              height='.$height.'>
              </iframe>';
                        
        echo $after_widget;   
           
                
	}
        
 	public function form( $instance ) {
                        
 		$title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'My favorite album', 'deezer-album' );
 		$album_id = isset( $instance[ 'album_id' ] ) ? $instance[ 'album_id' ] : '1031770';
        $width = isset( $instance[ 'width' ] ) ? $instance[ 'width' ] : 200;
 		$height = isset( $instance[ 'height' ] ) ? $instance[ 'height' ] : 600;
        $show_cover = $instance['show_cover'] ? 'checked="checked"' : '';
        $autoplay = $instance['autoplay'] ? 'checked="checked"' : '';

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'deezer-album' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'album_id' ); ?>"><?php _e( 'Album ID:', 'deezer-album' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'album_id' ); ?>" name="<?php echo $this->get_field_name( 'album_id' ); ?>" type="text" value="<?php echo esc_attr( $album_id ); ?>" />
		</p>

		<h4>Advanced Options</h4>
		<p>
			<label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Dimension (px): ', 'deezer-album' ); ?></label><br/>
                        <input size="5" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="text" value="<?php echo esc_attr( $width ); ?>" /> x 
                        <input size="5" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo esc_attr( $height ); ?>" />

		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php echo $show_cover; ?> id="<?php echo $this->get_field_id('show_cover'); ?>" name="<?php echo $this->get_field_name('show_cover'); ?>" /> 
                        <label for="<?php echo $this->get_field_id('show_cover'); ?>"><?php _e('Show cover '); ?></label><br/>			
			
                        <input class="checkbox" type="checkbox" <?php echo $autoplay; ?> id="<?php echo $this->get_field_id('autoplay'); ?>" name="<?php echo $this->get_field_name('autoplay'); ?>" /> 
                        <label for="<?php echo $this->get_field_id('autoplay'); ?>"><?php _e('Autoplay '); ?></label><br/>                        
                </p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
        $new_instance = wp_parse_args( (array) $new_instance, 
                    array( 
                        'title' => '', 
                        'album_id' => '', 
                        'width' => '', 
                        'height' => '', 
                    ) 
                );
                
		$instance[ 'title' ] = sanitize_text_field( $new_instance[ 'title' ] );
		$instance[ 'album_id' ] = intval( $new_instance[ 'album_id' ] );
        $instance[ 'width' ] = intval( $new_instance[ 'width' ] );
        $instance[ 'height' ] = intval( $new_instance[ 'height' ] );
        $instance['show_cover'] = $new_instance['show_cover'] ? 1 : 0;
		$instance['autoplay'] = $new_instance['autoplay'] ? 1 : 0;
		return $instance;
	}

}
