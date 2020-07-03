<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
</head>
<style>
/*.container{
	padding: 20px;
 }
 h1{
	font-size: 23px;
    color: #666;
 }*/
 .content{
	width: 100%;
	float: left;
    margin-top: 20px;
 }
 
/* Star Rating */
.rate {
    float: left;
    height: 46px;
    text-align: left;
}
.rate:not(:checked) > input {
    position:absolute;
    /*top:-9999px;*/
	display: none;
}
.rate:not(:checked) > label {
    float:right;
    width:1em;
    overflow:hidden;
    white-space:nowrap;
    cursor:pointer;
    font-size:40px;
    color:black;
}
.rate:not(:checked) > label:before {
    /*content: 'Ã¢Ëœâ€¦ ';*/
	content: "\2605";
}
.rate > input:checked ~ label, .rate input[checked="checked"] ~ label {
    color: #F26F23;    
}
.rate:not(:checked) > label:hover,
.rate:not(:checked) > label:hover ~ label {
    color: #F26F23;  
}
.rate > input:checked + label:hover,
.rate > input:checked + label:hover ~ label,
.rate > input:checked ~ label:hover,
.rate > input:checked ~ label:hover ~ label,
.rate > label:hover ~ input:checked ~ label {
    color: #F26F23;
}

.overall-rating{
	width: 100%;
	float: left;
	font-size: 14px;
	margin-top: 5px;
	color: #009788;
}

.statusMsg{
	font-size: 16px;
	padding: 10px !important;
	border: 1.5px dashed;
}
.statusMsg.errordiv{
	color: #ff4040;
}
.statusMsg.succdiv{
	color: #00bf6f;
}


</style>
<body>
<?php 
// Include the database config file 
include_once 'dbConfig.php'; 
 
$postID = 1; // It will be changed with dynamic value 
 
// Fetch the post and rating info from database 
$query = "SELECT p.*, COUNT(r.rating_number) as rating_num, FORMAT((SUM(r.rating_number) / COUNT(r.rating_number)),1) as average_rating FROM posts as p LEFT JOIN rating as r ON r.post_id = p.id WHERE p.id = $postID GROUP BY (r.post_id)"; 
$result = $db->query($query); 
$postData = $result->fetch_assoc(); 
?>

<div class="container">
    <h1><?php echo $postData['title']; ?></h1>
    <div class="rate">
        <input type="radio" id="star5" name="rating" value="5" <?php echo ($postData['average_rating'] >= 5)?'checked="checked"':''; ?>>
        <label for="star5"></label>
        <input type="radio" id="star4" name="rating" value="4" <?php echo ($postData['average_rating'] >= 4)?'checked="checked"':''; ?>>
        <label for="star4"></label>
        <input type="radio" id="star3" name="rating" value="3" <?php echo ($postData['average_rating'] >= 3)?'checked="checked"':''; ?>>
        <label for="star3"></label>
        <input type="radio" id="star2" name="rating" value="2" <?php echo ($postData['average_rating'] >= 2)?'checked="checked"':''; ?>>
        <label for="star2"></label>
        <input type="radio" id="star1" name="rating" value="1" <?php echo ($postData['average_rating'] >= 1)?'checked="checked"':''; ?>>
        <label for="star1"></label>
    </div>
    <div class="overall-rating">
        (Average Rating <span id="avgrat"><?php echo $postData['average_rating']; ?></span>
        Based on <span id="totalrat"><?php echo $postData['rating_num']; ?></span> rating)</span>
    </div>
	
    <div class="content"><?php echo $postData['content']; ?></div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script>
$(function() {
    $('.rate input').on('click', function(){
        var postID = <?php echo $postData['id']; ?>;
        var ratingNum = $(this).val();
		
        $.ajax({
            type: 'POST',
            url: 'rating.php',
            data: 'postID='+postID+'&ratingNum='+ratingNum,
            dataType: 'json',
            success : function(resp) {
                if(resp.status == 1){
                    $('#avgrat').text(resp.data.average_rating);
                    $('#totalrat').text(resp.data.rating_num);
					
                    alert('Thanks! You have rated '+ratingNum+' to "<?php echo $postData['title']; ?>"');
                }else if(resp.status == 2){
                    alert('You have already rated to "<?php echo $postData['title']; ?>"');
                }
				
                $( ".rate input" ).each(function() {
                    if($(this).val() <= parseInt(resp.data.average_rating)){
                        $(this).attr('checked', 'checked');
                    }else{
                        $(this).prop( "checked", false );
                    }
                });
            }
        });
    });
});
</script>
</body>
</html>