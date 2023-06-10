/*************
*JS File for admin funcitons for CPJ Calendar Schedule plugin
* AUthor: Paul Jarvis
*Version: 2.0.0
License: gpl gnu
 */

 const ajaxLoaderGif2 = '<img id="cpj-ajax-loader-gif" src="'+cpj_calendar_admin_ajax_obj.site_url + '/cpj-calendar-appointment-scheduler/ajax-loader/ajax-loader.gif">';


 jQuery(document).ready(function($){
 
                if($('.cpj-appt-admin-appt-cust-table-box').length){
                
             const myData = {
                        'cpj-calendar-admin-nonce':cpj_calendar_admin_ajax_obj.nonce,
                        'action':'cpj-appt-admin-appt-list'
        }

        doAjaxStuff(myData,$('.cpj-appt-admin-appt-cust-table-box'));

 
                
                
                }//end length if

        $("#cpj-cust-list-tab,#cpj-appt-list-tab").click(function(){

            if($(this).hasClass("cpj-appt-admin-tabs-sel")){
                return;
            }

            const ajaxAction = ($(this).attr('id') === "cpj-cust-list-tab")?"cpj-appt-admin-cust-list":'cpj-appt-admin-appt-list';

            const myData = {
                        'cpj-calendar-admin-nonce':cpj_calendar_admin_ajax_obj.nonce,
                        'action':ajaxAction
        }

            doAjaxStuff(myData,$('.cpj-appt-admin-appt-cust-table-box'));

           $("#cpj-cust-list-tab,#cpj-appt-list-tab").removeClass('cpj-appt-admin-tabs-sel');

           $(this).addClass('cpj-appt-admin-tabs-sel');

        

        
        });

        $("body").on('click','.appt-list-table tr', function(){

            console.log("id = " + $(this).attr('id'));
       
               const myData = {
                        'cpj-calendar-admin-nonce':cpj_calendar_admin_ajax_obj.nonce,
                        'action':'cpj-appt-cust-details',
                        'cust_id':$(this).attr('id')
        }

        doAjaxStuff(myData, $("#cust-appt-details-win"));

        $("#white-bg").show();

        $("#cust-appt-details-win").show();
        
        });

        $("body").on('click','#cust-details-close-x',function(){
        
                hideWins();
                return false;
        });

        $("body").on('click','#cpj-cust-mod-btn',function(){
        
                const myData = {
                        'cpj-calendar-admin-nonce':cpj_calendar_admin_ajax_obj.nonce,
                        'action':'cpj-appt-cust-mod',
                        'cust_id':$('#cpj-cust-mod-cust-id').val(),
                        'first_name':$('#cpj-cust-mod-first_name').val(),
                        'last_name':$('#cpj-cust-mod-last_name').val(),
                        'phone':$('#cpj-cust-mod-phone').val(),
                        'email':$('#cpj-cust-mod-email').val(),
                         'pref':$('#cpj-cust-mod-pref').val()        
                }

                doAjaxStuff(myData,$('#cust-mod-msg'));
        
        });

        $('body').on('click','.appt-details-delete-btn',function(){
        
            $("#cust-appt-del-appt-confirm-id").val($(this).val());

            $("#white-bg").css('z-index','1015');

            $("#cust-appt-del-appt-confirm-win").show();

            console.log("id = "+ $("#cust-appt-del-appt-confirm-id").val());
        
        });

        $('body').on('click','#cust-appt-del-appt-confirm-btn-no',function(){

                $("#cust-appt-del-appt-confirm-win").hide();
            $("#white-bg").css('z-index','1005');

        });

       $('body').on('click','#cust-appt-del-appt-confirm-btn-yes',function(){



                    const myData = {
                        'cpj-calendar-admin-nonce':cpj_calendar_admin_ajax_obj.nonce,
                        'action':'cpj-appt-del-appt',
                        'appt_id':$('#cust-appt-del-appt-confirm-id').val()
                    }

             //   doAjaxStuff(myData,$('#cust-mod-msg'));
                    
   const requestAdmin = $.ajax({
                        type: 'POST',
                        url:cpj_appt_admin_ajax_obj.ajax_url,
                        data: myData,
                        error: function(e){ console.log(e);},
                        beforeSend: function(){
                        
                            $('#cust-mod-msg').html(ajaxLoaderGif2);
                                
                        }
                });

         requestAdmin.done(function(response){

                $('#cust-mod-msg').html(response);

         

                 $("#white-bg").css('z-index','1005');

                $("#cust-appt-del-appt-confirm-win").hide();

                 const myData2 = {
                        'cpj-calendar-admin-nonce':cpj_calendar_admin_ajax_obj.nonce,
                        'action':'cpj-appt-details-refresh-appt',
                        'cust_id':$('#cpj-cust-mod-cust-id').val()
                    }

                console.log("cust id = " + $('#cpj-cust-mod-cust-id').val());

               

                doAjaxStuff(myData2,$(".appt-details-appt-list-box"));

           });


        });

        $("body").on('click','#cust-details-add-new-appt-btn',function(){
        
                    const cust_id = $(this).val();
                    $('#cust-appt-details-add-new-appt-win').show();
         
                 const myData2 = {
                        'cpj-calendar-admin-nonce':cpj_calendar_admin_ajax_obj.nonce,
                        'action':'cpj-appt-details-load-cal'
                    
                    }               

                doAjaxStuff(myData2,$(".cust-appt-details-add-new-calendar-box"));
        
        });

                $("body").on("click",".next-mon-btn",function(){
               
                 $(".cpj-time-box").html('');
           
            const newReq = $.ajax({
                type: 'POST',
                url:cpj_appt_admin_ajax_obj.ajax_url,
                data: {'action' : 'cpj-appt-details-load-cal',
                        'cur-mon-num':$("#cur-mon-num").val(),
                        'mon-direction':'next'
                    },
                error: function(e){ console.log(e);},
                beforeSend: function(){
                    $(".cust-appt-details-add-new-calendar-box").html(ajaxLoaderGif);
                    }
                });

         newReq.done(function(msg){
                
                $(".cust-appt-details-add-new-calendar-box").html(msg);
         });//end request done


        });

        $("body").on("click",".prev-mon-btn",function(){
                  
                    $(".cpj-time-box").html('');
           
            const newReqPrev = $.ajax({
                type: 'POST',
                url:cpj_appt_admin_ajax_obj.ajax_url,
                data: {'action' : 'cpj-appt-details-load-cal',
                        'cpj_calendar_time_nonce':cpj_appt_admin_ajax_obj.nonce,
                        'cur-mon-num':$("#cur-mon-num").val(),
                        'mon-direction':'prev'
                    },
                error: function(e){ console.log(e);},
                beforeSend: function(){
                    $(".cust-appt-details-add-new-calendar-box").html(ajaxLoaderGif);
                    }
                });

         newReqPrev.done(function(msg){
                
                $(".cust-appt-details-add-new-calendar-box").html(msg);
         });//end request done


        });

            $("body").on("click",".mon-days",function(){

            $(".mon-days").removeClass("mon-days-sel");
            $(this).addClass("mon-days-sel");

            let dateStr = $("#cur-mon-name").val() +" "+$(this).html()+", "+$("#cur-year").val();
            let fullStr = '<div class="sel-date-display">' + dateStr + '</div>';

            $("#pick-a-date-holder").html(dateStr);

            const timeReq = $.ajax({
                type: 'POST',
                url:cpj_appt_admin_ajax_obj.ajax_url,
                data: {'action' : 'cust_details_cal_time',
                        'cpj_calendar_time_nonce':cpj_appt_admin_ajax_obj.nonce,
                        'cur-mon-num':$("#cur-mon-num").val(),
                        'cur-year':$("#cur-year").val(),
                        'cur-day-num':$(this).html()
                    },
                error: function(e){ console.log(e);},
                beforeSend: function(){
                    $(".cust-appt-details-add-new-time-box").html(ajaxLoaderGif);
                    }
                });

         timeReq.done(function(msg){
           $("#pick-a-time-text").show();
            $(".cust-appt-details-add-new-time-box").html(msg);
         });//end request done


        });

        $("body").on('click','.cust-details-add-new-time-btn', function(){

            let selTime = $(this).html();
            $("#pick-a-time-holder").html(selTime);
            $(".cust-details-add-new-time-btn").removeClass("cust-details-add-new-time-btn-sel");
            $(this).addClass("cust-details-add-new-time-btn-sel");

            if(!($(".cust-details-add-new-submit-btn").length)){
                $(".cust-appt-details-add-new-submit-box").append('<button class="cust-details-add-new-submit-btn">Submit</button>');
                $(".cust-details-add-new-submit-btn").show('slow');
            }//ednm if
        });

        $("body").on("click",".cust-details-add-new-submit-btn",function(){
           
            const custFormReq = $.ajax({
                type: 'POST',
                url:cpj_appt_admin_ajax_obj.ajax_url,
                data: {'action' : 'cust-appt-details-add-new-appt',
                        'sel-date_time':$(".cust-details-add-new-time-btn-sel").val(),
                        'cust_id':$('#cpj-cust-mod-cust-id').val()
                    },
                error: function(e){ console.log(e);},
                beforeSend: function(){
                    $(this).html(ajaxLoaderGif);
                    }
                });

                custFormReq.done(function(msg){

                $("#cust-appt-details-add-new-appt-win").hide();
                $(".cust-appt-details-add-new-time-box").html('');
                $(".cust-details-add-new-submit-btn").remove();

                $('#cust-mod-msg').html(msg);

                 const myData2 = {
                        'cpj-calendar-admin-nonce':cpj_calendar_admin_ajax_obj.nonce,
                        'action':'cpj-appt-details-refresh-appt',
                        'cust_id':$('#cpj-cust-mod-cust-id').val()
                    }               

                doAjaxStuff(myData2,$(".appt-details-appt-list-box"));

                
         });//end request done

        });

        $("body").on('click','#cust-details-del-cust-btn', function(){

                $("#white-bg").css('z-index','1015');

                $("#cust-appt-del-cust-confirm-win").show();
        
        });

        $("body").on('click','#cust-appt-del-cust-confirm-btn-no',function(){

                $("#white-bg").css('z-index','1005');
        
                $("#cust-appt-del-cust-confirm-win").hide();
        });

        $("body").on('click','#cust-appt-del-cust-confirm-btn-yes',function(){

                 const myData = {
                        'cpj-calendar-admin-nonce':cpj_calendar_admin_ajax_obj.nonce,
                        'action':'cpj-appt-details-del-cust',
                        'cust_id':$('#cpj-cust-mod-cust-id').val()
                    }   

                    doAjaxStuff(myData,$("#cust-appt-del-cust-deleted-win"));

                    $("#cust-appt-details-win").hide();            
                    $("#cust-appt-del-cust-confirm-win").hide();
                    $("#cust-appt-del-cust-deleted-win").show();

        });

        $("body").on('click','#cust-del-response-ok', function(){
        
                    $("#cust-appt-del-cust-confirm-win").hide();
                    $("#white-bg").css('z-index','1005');
                    $("#white-bg").hide();

             const myData = {
                        'cpj-calendar-admin-nonce':cpj_calendar_admin_ajax_obj.nonce,
                        'action':'cust-appt-details-refresh-list'
                    }   

             doAjaxStuff(myData,$('.cpj-appt-admin-appt-cust-table-box'));

            $(".cpj-appt-admin-tabs").removeClass('cpj-appt-admin-tabs-sel');
            $("#cpj-appt-list-tab").addClass('cpj-appt-admin-tabs-sel');
            $("#cust-appt-del-cust-deleted-win").hide();
        });

        $("body").on('click','#cust-details-cancel-btn',function(){

                 $("#white-bg").css('z-index','1005');
                 $("#white-bg").hide();
                $("#cust-appt-details-win").hide('slow');

             const myData = {
                        'cpj-calendar-admin-nonce':cpj_calendar_admin_ajax_obj.nonce,
                        'action':'cust-appt-details-refresh-list'
                    }   

             doAjaxStuff(myData,$('.cpj-appt-admin-appt-cust-table-box'));

            $(".cpj-appt-admin-tabs").removeClass('cpj-appt-admin-tabs-sel');
            $("#cpj-appt-list-tab").addClass('cpj-appt-admin-tabs-sel');

        
        });


function hideWins(){

 $("#white-bg").hide();
 $("#cust-appt-details-win").hide();


}//end fx
 
 function doAjaxStuff(what,where){
 
    const requestAdmin = $.ajax({
                        type: 'POST',
                        url:cpj_appt_admin_ajax_obj.ajax_url,
                        data: what,
                        error: function(e){ console.log(e);},
                        beforeSend: function(){
                        
                            where.html(ajaxLoaderGif2);
                                
                        }
                });

         requestAdmin.done(function(response){

                where.html(response);

            });

 
 
 
 }
 
 }); //End jquery doc ready