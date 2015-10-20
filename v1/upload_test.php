<!DOCTYPE html>
<html>
<body>

<form action="campaigns.php" method="post" enctype="multipart/form-data">
	<input type="hidden" name="request" value="submit_story">
	<input type="hidden" name="camid" value="88">
	<input type="hidden" name="apikey" value="123456">
	<input type="hidden" name="title" value="submit_story TEST">
	<input type="hidden" name="desc" value="submit_story DESC">
	
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html>