function refreshPopularProducts(baseURL,staticURL,categoryid) {
    $(document).ready(function() {
        
        categoryid = categoryid || 0;
        

        
       $(".next").click(function(){
       var limit = $(this).attr('id'); 
       var productkind = $(this).parent().attr('id');
           
       if (productkind == 'popular-browse') {
       var popularItems = $(".popular-item");
       var thumbsBox = '#popular-box';
       }

       if (productkind == 'new-browse') {
       var popularItems = $(".new-item");
       var thumbsBox =  '#new-box';
       }
            
            $(document).ajaxStart(function() {
            $(thumbsBox).fadeTo("slow",0.5);
            //$(thumbsBox).toggle( "drop" );
            console.log(thumbsBox);
            });
            
            $("#"+productkind).children("span.next").removeClass("active");
            $(this).addClass("next active");        
            
            $.ajax({
            type: "POST",
            url: baseURL +"includes/ajax/popularHandler.php",
            async: true,
            dataType: "json",
            data: { productkind:productkind,
                    popularproducts:limit,
                    category:categoryid},
            success: function(data) {
                       var i = 0;
                  
                        
                       var totalPopularItems = popularItems.length;
                
                            while ( i < totalPopularItems) {
                                       $.each(popularItems, function () {
                                         var thumb = data[i];

                                         $(this).children(".thumbimage").children("a").attr("href", baseURL + 'sku/' +  thumb.id + '-' +  thumb.URLtitle + '/');
                                         $(this).children(".thumbimage").children("a").attr("title", thumb.title); 
                                         $(this).children(".thumbimage").find("img").attr("src", staticURL + thumb.thumb_path); 
                                         $(this).children(".thumbimage").find("img").attr("alt", thumb.title); 
                                         $(this).children(".thumbtitle").children("a").attr("href", baseURL + 'sku/' +  thumb.id + '-' +  thumb.URLtitle + '/'); 
                                         $(this).children(".thumbtitle").children("a").attr("title", thumb.title);
                                         $(this).children(".thumbtitle").children("a").text(thumb.title);
                                         $(this).children(".price").text(thumb.price);

                                         i++;
                                      });  
                           }},
            error:  function() {
            alert("Something went wrong");}
            });   
        
        $(document).ajaxComplete(function() {
        $(thumbsBox).fadeTo("1200", 1);
        });   
        
       });
    });    
}