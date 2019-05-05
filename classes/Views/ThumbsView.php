<?php 
namespace CI\Views;

class ThumbsView { 
    

    public function initView( $mode, $subMode,  $isModalLink, $thumbs, $isSimilairThumbs ) { 
        
        
        switch ( $mode ) {
            
            case 'brand':
                
                if ( $subMode == 'top-products') {
                    self::callTemplate( 'thumbsDiscountTemplate', $isModalLink, $thumbs, $isSimilairThumbs ); 
                } else {
                    self::callTemplate( 'thumbsBasicTemplate', $isModalLink, $thumbs, $isSimilairThumbs );
                }
                
                
                break;
                
            case 'category':
            
                if ( $subMode == 'top-products') {
                    self::callTemplate( 'thumbsDiscountTemplate', $isModalLink, $thumbs, $isSimilairThumbs ); 
                } else {
                    self::callTemplate( 'thumbsBasicTemplate', $isModalLink, $thumbs, $isSimilairThumbs );
                }


            break;
            
            case 'related-products':
                    
                self::callTemplate( 'thumbsBasic_4_Template', $isModalLink, $thumbs, $isSimilairThumbs );
                
                break;
            
            default:
                
                self::callTemplate( 'thumbsBasicTemplate', $isModalLink, $thumbs, $isSimilairThumbs );
                
                break;
            
            
        }
    }  
    
    function callTemplate ( $templateName, $isModalLink, $thumbs, $isSimilairThumbs) {
        global $twig;
        
        echo $twig->render( $templateName .'.html.twig', array( 'thumbs' => $thumbs, 
                                                                   'BASE_URL' => BASE_URL,
                                                                   'STATIC_URL' => STATIC_URL,
                                                                   'isModalLink' => $isModalLink,
                                                                   'isSimilairThumbs' => $isSimilairThumbs
                    
        ));
    }
}
