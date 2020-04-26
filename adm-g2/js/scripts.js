//MAKE SURE PASSWORD IS LONG ENOUGH
function validateUserForm() {
  	var x = document.forms["user"]["passwd"].value;
		var y = document.forms["user"]["cb"].value;
  	if (x < 8 && y == "on") {
    	alert("Web Password must be 8 digits long!");
    	return false;
  		}
		}
//END


//VALIDATE IF FACILITY IS CHOOSEN WHEN ADDING ZONE
function validateZoneForm() {
  	var x = document.forms["add_zone"]["facility"].value;
  	if (x == "0") {
    	alert("Choose a facility!");
    	return false;
  		}
		}
//END

// DAY OF WEEK FUNCTION
function daysofWeek(id){
    var sum = 128;
    var children = $("div *").filter(function() {return(this.id == id + "_check");});
    for (var i = 0; i < children.length; i++) {
      if (children[i].checked) {
        sum = sum + parseInt(children[i].value);
      }
    }
    $("#" + id + "_total").val(sum);
  }


// GET FORMS FOR DIFFERENT KIND OF IOT
function showIotForm(str) {
    if (str == "") {
        document.getElementById("form_iot").innerHTML = "";
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("form_iot").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("POST","ajax.php?iot_type=" + str, true);
        xmlhttp.send();
    }
}

$(document).ready(function(){

  // GET NEW FORM THAT CORRESPOND WITH KIND OF IOT
  $("#iot_type").change(function(){
      var zone_type_id = $(this).val();

      $.ajax({
          url: 'ajax.php',
          type: 'post',
          data: {iot_type:zone_type_id},
          dataType: 'html',
          success:function(response){
            result=response;
            $("#form_iot").empty();
            $("#form_iot").append(result)
            return result;
          }
      });
    });
    //END

    //ZONE CONTENT TYPE
    $("#zone_type").change(function(){
        var zone_type_id = $(this).val();

        $.ajax({
            url: 'ajax.php',
            type: 'post',
            data: {get_z_content:zone_type_id},
            dataType: 'json',
            success:function(response){

                var len = response.length;

                $("#zone_content").empty();
                for( var i = 0; i<len; i++){
                    var id = response[i]['id'];
                    var name = response[i]['name'];

                    $("#zone_content").append("<option value='"+id+"'>"+name+"</option>");

                }
            }
        });
    });
    //END

    // GET IOTS BY IOT TYPE ID
    $(document).on('change', '.iot_type_seg_temp', function(){
    //$("#iot_type_seg_temp").change(function(){
        var id = $(this).attr('id');
        var ibt_id_tag = $("#" + id + "_ibt");
        var type_id = $(this).val();
        $.ajax({
            url: 'ajax.php',
            type: 'post',
            data: {get_iot_by_type:type_id},
            dataType: 'json',
            success:function(response){
                var len = response.length;
                ibt_id_tag.empty();
                for( var i = 0; i<len; i++){
                    var id = response[i]['id'];
                    var name = response[i]['name'];
                    ibt_id_tag.append("<option value='"+id+"'>"+name+"</option>");
                }
            }
        });
    });
    //END

    // GET ALL ZONES RELATED TO FACILITY ID
    $("#fac_id").change(function(){
        var fac_id = $(this).val();
        $.ajax({
            url: 'ajax.php',
            type: 'post',
            data: {get_zones:fac_id},
            dataType: 'json',
            success:function(response){
                var len = response.length;
                $("#zone_id").empty();
                for( var i = 0; i<len; i++){
                    var id = response[i]['id'];
                    var name = response[i]['name'];
                    $("#zone_id").append("<option value='"+id+"'>"+name+"</option>");
                }
            }
        });
    });
    //END

    // GET NEW SECURITY STRING
    $("#change_sec").click(function(){
        $.ajax({
            url: 'ajax.php',
            type: 'post',
            data: {get_sec_string:"1"},
            dataType: 'html',
            success:function(response){
                $("#sec_string").empty();
                $("#sec_string").val(response);
            }
        });
    });
    //END

    // REMOVE ALERT ITEM AND UPDATE TABLE
    $(document).on('change', '.status', function(){
        // ID OF ROW
        var id = $(this).attr('id');
        var status = $("#" + id).val();
        var status2 = $(this).val();
        var facility = $("#facility_id").val();
        var alert_type = $(this).siblings($("#alert_type")).val();
        var alert_name = $(this).siblings().next($("#alert_name")).val();
        var set_status = $("#set_status").val();

        $.ajax({
            url: 'ajax.php',
            type: 'post',
            data: {change_id:id,change_status:status2,status:set_status,alert_type:alert_type,facility_id:facility,alert_name:alert_name},
            dataType: 'html',
            success:function(response){
                $(alert_name).empty();
                $(alert_name).html(response);
                $(alert_name).trigger("chosen:updated");
            }
        });
    });

    // ON CLICK UPDATE OF TIME SEGMENTS
    $('.submit_template').on("click", function() {
        // SUBMITTING AND UPDATING TIMESEGMENTS IN TEMPLATE
        var form = this.form;
        var post_request = $(form).serialize() + "&todo=" + $(this).val();
        $.post("ajax.php", post_request, function(response) {
            //$("#listing").load(location.href + " #listing");
            alert(response);
            location.reload();
        });
        return false;
    });

    //ON CLICK ADD / UPDATE OF TIME SCHEDULE FOR IOT
    $('.submit_time_schedule').on("click", function() {
        // GET THIS FORM
        //alert("hello");
        var form = this.form;
        var post_request = $(form).serialize() + "&todo=" + $(this).val();
        $.post("ajax.php", post_request, function(response) {
            // REPLY
            alert(response);
            location.reload();
        });
        return false;
    });

    // ON CLICK IMPORT TIME SEGMENTS FROM TEMPLATE TO IOT
    $('.import_template').on("click", function() {
        var form = this.form;
        $.post("ajax.php", $(form).serialize(), function(response) {
            alert(response);
            location.reload();
        });
        return false;
    });

    // UPDATE IOT TABLE EVERY #### MILISECONDS TO FOLLOW LIVE INPUT
    if ($('#iot_div').length > 0){
    setInterval(function() {
       $("#iot_div").load(' #iot_div');
    }, 3000);
    }


});
