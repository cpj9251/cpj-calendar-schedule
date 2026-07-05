/*************
 * JS File for admin JS functions for CPJ Schedule Plugin
 * Author: Paul Jarvis
 * ver: 2.0.0
 * License: gpl. gnu
 */

const ajaxLoaderGif = '<img id="cpj-ajax-loader-gif" src="'+cpj_calendar_admin_ajax_obj.site_url + '/cpj-calendar-appointment-scheduler/ajax-loader/ajax-loader.gif">';

jQuery(document).ready(function($){


    $("#email-content-submit").click(function(){

       $('#cpj-confirm-appt-email').val(tinyMCE.activeEditor.getContent());
    
        if($("#cpj-confirm-appt-email").val().length){
   
            const myData = {
                        'cpj-calendar-admin-nonce':cpj_calendar_admin_ajax_obj.nonce,
                        'action':'cpj_calendar_e-mail_admin',
                        'email-content':$("#cpj-confirm-appt-email").val(),
                        'subject':$("#cpj-confirm-appt-subject").val(),
                        'id':$("#cpj-schedule-email-admin-id").val()
        }

            const requestAdmin = $.ajax({
                        type: 'POST',
                        url:cpj_calendar_admin_ajax_obj.ajax_url,
                        data: myData,
                        error: function(e){ console.log(e);},
                        beforeSend: function(){
                            }
                });
            requestAdmin.done(function(response){
                $("html, body").animate({ scrollTop: 0 }, "slow");

                $(".email-box-left").prepend(`<h3>${response}</h3>`);
            });

        }//end if

        });


    $("#notify-email-submit").click(function(){

        if($("#notify-email").val().length){

        const myData = {
                        'cpj-calendar-admin-nonce':cpj_calendar_admin_ajax_obj.nonce,
                        'action':'cpj_calendar_e-mail_notify_admin',
                        'notify-email':$("#notify-email").val(),
                        'id':$("#cpj-schedule-email-admin-id").val()
        }

            const requestNotifyAdmin = $.ajax({
                        type: 'POST',
                        url:cpj_calendar_admin_ajax_obj.ajax_url,
                        data: myData,
                        error: function(e){ console.log(e);},
                        beforeSend: function(){
                            }
                });
            requestNotifyAdmin.done(function(response){
                $("#notify-box").prepend(`<h3>${response}</h3>`);
            });

        }//end if

    });

    $("#appt-from-email-submit").click(function(){

        let fieldNotEmpty = $("#appt-email-from-field-display-name").val().length + $("#appt-email-from-field").val().length;

        if(fieldNotEmpty > 0){
            const myData = {
                'cpj-calendar-admin-nonce':cpj_calendar_admin_ajax_obj.nonce,
                'action':'cpj_calendar_e-mail_from_admin',
                'from-email':$("#appt-email-from-field").val(),
                'display-name':$("#appt-email-from-field-display-name").val(),
                'id':$("#cpj-schedule-email-admin-id").val()
}

    const requestFromAdmin = $.ajax({
                type: 'POST',
                url:cpj_calendar_admin_ajax_obj.ajax_url,
                data: myData,
                error: function(e){ console.log(e);},
                beforeSend: function(){
                    }
        });
    requestFromAdmin.done(function(response){
        $("#from-field-box").prepend(`<h3>${response}</h3>`);
    });
   
        }

    });

    $("body").on('click','.appt-time-settings-btn, .appt-time-settings-btn-sel',function(){


    const myBtn = $(this);

            const myArr = $(this).val().split('_');

            const myData2 = {
                'cpj-calendar-admin-nonce':cpj_calendar_admin_ajax_obj.nonce,
                'action':'cpj_schedule_time_settings',
                'time-id':myArr[0],
                'day-num':myArr[1],
                'time-num':myArr[2]
}

    const requestFromAdmin2 = $.ajax({
                type: 'POST',
                url:cpj_calendar_admin_ajax_obj.ajax_url,
                data: myData2,
                error: function(e){ console.log(e);},
                beforeSend: function(){
                    }
        });

    requestFromAdmin2.done(function(response){
    
            const myId = response;

            let newVal = myId + '_' + myArr[1] + '_' + myArr[2];

            myBtn.val(newVal);


            if(myId > 0){
                myBtn.removeClass('appt-time-settings-btn').addClass('appt-time-settings-btn-sel');
                msg = 'New Time added.'
            }
            else{
                myBtn.removeClass('appt-time-settings-btn-sel').addClass('appt-time-settings-btn');
                msg = 'Time Removed.';
            }

            $("#return-msg").html(msg);



    });
    
    });

     
        $("body").on("click",".next-mon-btn",function(){

            $("#cpj-admin-time").html('');
                 
            const newReq = $.ajax({
                type: 'POST',
                url:cpj_calendar_admin_ajax_obj.ajax_url,
                data: {'action' : 'cpj_admin_calendar',
                        'cpj_calendar_time_nonce':cpj_calendar_admin_ajax_obj.nonce,
                        'cur-mon-num':$("#cur-mon-num").val(),
                        'mon-direction':'next'
                    },
                error: function(e){ console.log(e);},
                beforeSend: function(){
                    $("#cpj-admin-cal").html(ajaxLoaderGif);
                    }
                });

         newReq.done(function(msg){
                $("#cpj-admin-cal").html(msg);
         });//end request done


        });

           if($("#cpj-admin-cal").length){

                const request = $.ajax({
					type: 'POST',
					url:cpj_calendar_admin_ajax_obj.ajax_url,
					data: {'action' : 'cpj_admin_calendar','cpj_calendar_time_nonce':cpj_calendar_admin_ajax_obj.nonce},
					error: function(e){ console.log(e);},
					beforeSend: function(){
						}
					});

		request.done(function(msg){

            $("#cpj-admin-cal").html(msg);
        });//end request done

     }//end if block exists

        $("body").on("click",".prev-mon-btn",function(){
                    
                    $("#cpj-admin-time").html('');

            const newReqPrev = $.ajax({
                type: 'POST',
                url:cpj_calendar_admin_ajax_obj.ajax_url,
                data: {'action' : 'cpj_admin_calendar',
                        'cpj_calendar_time_nonce':cpj_calendar_admin_ajax_obj.nonce,
                        'cur-mon-num':$("#cur-mon-num").val(),
                        'mon-direction':'prev'
                    },
                error: function(e){ console.log(e);},
                beforeSend: function(){
                    $("#cpj-admin-cal").html(ajaxLoaderGif);
                    }
                });

         newReqPrev.done(function(msg){
                $("#cpj-admin-cal").html(msg);
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
                url:cpj_calendar_admin_ajax_obj.ajax_url,
                data: {'action' : 'cpj_admin_time',
                        'cpj_calendar_time_nonce':cpj_calendar_admin_ajax_obj.nonce,
                        'cur-mon-num':$("#cur-mon-num").val(),
                        'cur-year':$("#cur-year").val(),
                        'cur-day-num':$(this).html()
                    },
                error: function(e){ console.log(e);},
                beforeSend: function(){
                    $("#cpj-admin-time").html(ajaxLoaderGif);
                    }
                });

         timeReq.done(function(msg){
           $("#pick-a-time-text").show();
            $("#cpj-admin-time").html(msg);
         });//end request done


        });

       $('body').on('click','.time-btn,.time-btn-disabled,.time-btn-sel',function(){

        const myBtn = $(this);

            if(myBtn.hasClass("time-btn-sel")){
        
            const selTime = myBtn.val();

            const timeReq = $.ajax({
                type: 'POST',
                url:cpj_calendar_admin_ajax_obj.ajax_url,
                data: {'action' : 'cpj_admin_time_remover',
                        'cpj_calendar_time_nonce':cpj_calendar_admin_ajax_obj.nonce,
                        'sel-time':selTime
                    },
                error: function(e){ console.log(e);},
                beforeSend: function(){
                   // myBtn.html(ajaxLoaderGif);
                    }
                });

         timeReq.done(function(msg){

            if(msg > 0){
                myBtn.removeClass("time-btn-sel");
                myBtn.removeClass("time-btn");

                myBtn.addClass("time-btn-disabled");

            }

          });//end request done

            }//end if
        else{
        
            const delId = myBtn.val();

            const timeReq = $.ajax({
                type: 'POST',
                url:cpj_calendar_admin_ajax_obj.ajax_url,
                data: {'action' : 'cpj_admin_time_adder',
                        'cpj_calendar_time_nonce':cpj_calendar_admin_ajax_obj.nonce,
                        'del-id':delId
                    },
                error: function(e){ console.log(e);},
                beforeSend: function(){
                  //  myBtn.html(ajaxLoaderGif);
                    }
                });

         timeReq.done(function(msg){

            if(msg > 0){
                myBtn.removeClass("time-btn-disabled");
                myBtn.addClass("time-btn-sel");
                myBtn.addClass("time-btn");
            }
            else{
                alert("Cannot remove existing appointment with customer.")
            }

          });//end request done

        }//end else
        
        });

});//end document.ready
