<?php


namespace CI;

/**
 * Description of ImageGallery
 *
 * @author Ronald
 * 
 */

class ImageGallery {
    public static function productImageGallery ( $images ) {
        echo '<div id="product-images-gallery" class="owl-carousel owl-theme">';
        
        foreach ($images as $image) { ?>
            <img itemprop="image" class="product-image img-responsive img-rounded center-block" src="<?php echo $image['orig'];?>" >
        <?php }
        
        echo '</div>';   
    }    
}
