<?php
session_start();
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('location:home.php');
}
?>

<!DOCTYPE html>
<html lag="en">
<head>
    <meta charset="UTF-8"/>
    <title>Add Note</title>
    <meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="addNote">
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
		<div class="cell large-4 small-4 large-offset-4" style="margin-top: 20px">
			<h3 style="font-weight: bold;margin-bottom: 20px ">Add a note</h1>
			<form method='POST' action='add.php'>
				content: <br/><textarea rows='10' cols='100' name='content' style="resize: none;" value=""></textarea><br/>
				tags(split by comma): <input type="text" name="tags" value=""><br/>
				location: <input type='text' name='location' id="location" list='location-list' style="margin-bottom: 0px" value="">
				<ul class="vertical dropdown menu" id='location-list' data-dropdown-menu style="display: none;border-style: solid;border-width: 1px;">
				</ul>
				<br/>
				radius: <input type='number' name='radius' min='0' value="0"><br/>
				Who can see: <input type='radio' name='scope' value='1' checked="checked"> All 
				<input type='radio' name='scope' value='0'> Friend <br/>
				Allow comment: <input type='radio' name='comment' value='1' checked="checked"> Yes 
				<input type='radio' name='comment' value='0'> No <br/>
				Time Start: <a href="#" class="button tiny" id="dp1">Change</a>
				<input type="text" name="timeFrom" placeholder="click to choose time" id="startDate" readonly="readonly"><br/>
				Time End: <a href="#" class="button tiny" id="dp2">Change</a>
				<input type="text" name="timeTo" placeholder="click to choose time" id="endDate" readonly="readonly"><br/>
				<div class="alert alert-box"  style="display:none;" id="alert">	<strong>Oh snap!</strong></div>
				Day Start(Monday is 1, Tuesday is 2 ...): <input type="number" name="numberFrom" min="1" max="7" value="1"><br/>
				Day End(Monday is 1, Tuesday is 2 ...): <input type="number" name="numberTo" min="1" max="7" value="1"><br/>
				Repeat: <input type="radio" name="repeat" value='1'> Yes 
				<input type="radio" name="repeat" value="0" checked="checked"> No 
				<p style="color: #4698e1">Rule: the note will be shown between the start date and the end date, after the end date, if the repeated is yes, if will be shown every day of week you choose.</p>
				<div style="text-align: center;">
                    <input type="submit" value="Add" class="button" style="display: inline-block;margin-top: 50px">
                </div>
                <input type="text" name="lng" value="-73.9965" style="display: none;" id="lng">
                <input type="text" name="lat" value="40.7295" style="display: none;" id="lat">
			</form>
			<div id="service-helper"></div>
		</div>
	</div>
</body>


<script>
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
</script>




