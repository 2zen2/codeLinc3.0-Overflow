jQuery(function ($){
    // School Creation form Validation Script
    $('body').on('click','#create_school',function(){
        var error_fields = false;

        $("form#createuser .form-field.form-required input").not(':button,:hidden').each(function(){
            //alert(1);
          if($(this).val().trim() == ""){
              $(this).closest("tr.form-required").addClass("form-invalid");
              error_fields=true;
          }else{
              $(this).closest("tr.form-required").removeClass("form-invalid");
          }
        });

        if(error_fields == true){
            return false;
        }
    });

    // Ajax call for views count correction in Group Admin Screen
    $.ajax( {
        url : ajaxurl,
        data : {
            'action' : 'group_views'
        },
        dataType : 'json',
        async : false,
        beforeSend: function( xhr ) {
                $(".subsubsub .public .count").text("");
                $(".subsubsub .private .count").text("");
                $(".subsubsub .hidden .count").text("");
        },
        success : function( response ) {
            $(".subsubsub .all .count").text("("+response.data.all+")");
            $(".subsubsub .public .count").text("("+response.data.public+")");
            $(".subsubsub .private .count").text("("+response.data.private+")");
            $(".subsubsub .hidden .count").text("("+response.data.hidden+")");
        },
        error : function() {

            alert( 'There was an issue requesting membership, please contact your site administrator' );
        }
    } );

});
