<?php
session_start();
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    echo "Please login first. <a href='index.php'>Click here</a>";
}
$uname = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lag="en">
<head>
    <meta charset="UTF-8"/>
    <title>Home</title>
    <meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="home page">
    <meta name="author" content="Wangyue Wang">

    <!-- Compressed CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/foundation/6.0.6/foundation.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.0/css/foundation-datepicker.min.css">

    <!-- Compressed JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/foundation/6.0.6/foundation.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/foundation-datepicker/1.5.0/js/foundation-datepicker.min.js"></script>
    <script type="text/javascript" src="moment.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAADtFA_1sG5GvDFYP6oTTCdZAPHfFLteo&libraries=places"></script>
</head>
<body>
    <div class="grid-x">
        <div class="cell large-6 large-offset-3" style="margin-top: 10px" style="text-align: center">
            <div>
                <div style="display: inline-block;">Welcome! Your username is <?php echo $uname?>.</div>
                <div style="float: right;">
                    <a href="logout.php" style="text-decoration: underline;float: right;margin-left: 10px">logout</a>
                    <a href="addNote.php" style="text-decoration: underline;float: right; margin-left: 10px">add note</a>
                    <a href="profile.php" style="text-decoration: underline;float: right; margin-left: 10px">profile</a>
                    <a href="friend.php" style="text-decoration: underline;float: right;">friend</a>
                </div>
                <div style="border-style: solid;border-width: 1px; border-color:#696969;margin-top: 30px;padding: 5px 20px">
                    <p style="font-weight: bold;font-size: 20px">Filter</p>
                    <form>
                        tags(split by comma): <input type="text" id="tags" value="" ><br/>
                        location: <input type='text' name='location' id="location" list='location-list' style="margin-bottom: 0px" value="">
                        <ul class="vertical dropdown menu" id='location-list' data-dropdown-menu style="display: none;border-style: solid;border-width: 1px;">
                        </ul>
                        <br/>
                        <div>
                            radius: <input type='number' id='radius' min='0' value="0" style="width: 35%;display: inline-block;">
                            <div style="float: right; width: 45%">
                                See who: <input type='radio' name='scope' id='scope-all' value='1' checked="checked"> All 
                                <input type='radio' name='scope' id='scope-friend' value='0'> Friend <br/>
                            </div>
                        </div>
                        <div style="width: 45%;display: inline-block;">
                            Time Start: <a href="#" class="button tiny" id="dp1">Change</a>
                            <input type="text" name="timeFrom" placeholder="click to choose time" id="startDate" readonly="readonly"><br/>
                        </div>
                        <div style="width: 45%;float: right;">
                            Time End: <a href="#" class="button tiny" id="dp2">Change</a>
                            <input type="text" name="timeTo" placeholder="click to choose time" id="endDate" readonly="readonly"><br/> 
                        </div>
                        <div class="alert alert-box"  style="display:none;" id="alert"> <strong>Oh snap!</strong></div>
                        <input type="text" name="lng" value="-73.9965" style="display: none;" id="lng">
                        <input type="text" name="lat" value="40.7295" style="display: none;" id="lat">
                    </form>
                    <div style="text-align: center;">
                        <a href="#" class="button" id="filter" style="display: inline-block;width: 20%">Filter</a>
                    </div>
                    <div id="service-helper"></div>
                </div>
                <div>
                    <ul style="list-style-type: none;margin-top: 50px" id="filtered">
                        
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript">
$('#dp1').fdatepicker({
    format: 'mm-dd-yyyy hh:ii',
    disableDblClickSelection: true,
    language: 'vi',
    pickTime: true
});

$('#dp2').fdatepicker({
    format: 'mm-dd-yyyy hh:ii',
    disableDblClickSelection: true,
    language: 'vi',
    pickTime: true
});

var startDate = new Date();
var endDate = new Date();
$('#startDate').val(moment().format("YYYY-MM-DD HH:mm:ss"));
$('#endDate').val(moment().format("YYYY-MM-DD HH:mm:ss"));

$('#dp1').fdatepicker()
  .on('changeDate', function (ev) {
  if (ev.date.valueOf() > endDate.valueOf()) {
    $('#alert').show().find('strong').text('The start date can not be greater then the end time or current time');
  } else {
    $('#alert').hide();
    startDate = new Date(ev.date);
    $('#startDate').val(moment($('#dp1').data('date'),"MM-DD-YYYY HH:mm").format("YYYY-MM-DD HH:mm")+":00");
  }
  $('#dp1').fdatepicker('hide');
});

$('#dp2').fdatepicker()
  .on('changeDate', function (ev) {
  if (ev.date.valueOf() < startDate.valueOf()) {
    $('#alert').show().find('strong').text('The end date can not be less then the start date or current time');
  } else {
    $('#alert').hide();
    endDate = new Date(ev.date);
    $('#endDate').val(moment($('#dp2').data('date'),"MM-DD-YYYY HH:mm").format("YYYY-MM-DD HH:mm")+":00");
  }
  $('#dp2').fdatepicker('hide');
});

  $('#location').on("keyup paste change",function(event){
    var search = $('#location').val();
    var key = "AIzaSyAADtFA_1sG5GvDFYP6oTTCdZAPHfFLteo";
    $("#location-list").empty();
    var request = {
        query: search,
        fields: ['formatted_address', 'name', 'geometry'],
    };
    var service = new google.maps.places.PlacesService($('#service-helper').get(0));
    service.findPlaceFromQuery(request, callback);
  });

  $('#location').focus(function(){
    $('#location-list').show();
  });

  $('#location-list').on("click","li",function(ev){
    var lng = $(this).attr('lng');
    var lat = $(this).attr('lat');
    $('#location').val(($(this).text()));
    $('#lng').attr("value",lng);
    $('#lat').attr("value",lat);
    //console.log($('#lng').val());
    $('#location-list').hide();
  });

function callback(data){
    if (data != null) {
        var list = $("#location-list");
        list.empty();
        var size = 5;
        if(data.length < 5) {
            size = data.length;
        }
        for(var i = 0; i < size; i++) {
            var place = data[i];
            list.append("<li lng='"+place.geometry.location.lng()+"' lat='"+place.geometry.location.lat()+"'>"+"<a href='#'>"+place.formatted_address+"</a></li>");
        }   
    }   
}

$('#filter').on("click", function(){
    var data = {
        tags: document.getElementById("tags").value,
        timeFrom: document.getElementById("startDate").value, 
        timeTo: document.getElementById("endDate").value,
        latitude: document.getElementById("lat").value,
        longitude: document.getElementById("lng").value,
        radius: document.getElementById("radius").value,
        scope: document.getElementById('scope-all').checked?1:0
    }
    $.ajax({
        type: "POST",
        url: "handle/filter.php",
        contentType: "application/json",
        dataType: "json",
        data: JSON.stringify(data),
        success: function(result) {
            console.log(result[0]['noteid']);
            $("#filtered").empty();
            for(var i = 0; i < result.length; i++) {
               $("#filtered").append('<li style="padding: 5px 15px;border-style: solid;border-width: 1px 0px;border-color:#696969"><p>Content: '
               +result[i]['content']+'</p><p>Created Time: '
               +result[i]['time']+'</p><p>Tags: '
               +result[i]['taglist']+'</p></li>'); 
            }
        }, 
        error: function(requestObject, error, errorThrown){
            console.log("Error with Ajax Post Request:" + error);
            console.log(errorThrown);
        }
    });
});
</script>