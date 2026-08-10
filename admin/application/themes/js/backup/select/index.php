<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<meta name="author" content="thanhz" />

	<title>Untitled 1</title>
    <link rel="stylesheet" href="css/vietiso.select.css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>
    <script src="http://gsgd.co.uk/sandbox/jquery/easing/jquery.easing.1.3.js" type="text/javascript"></script>
    <script src="vietiso.select.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            
            $('.classz').vietisoSelect();
            $('.classz-2').vietisoSelect({
                num: 2,
                timeOpen: 0,
                timeClose: 0,
                colorHover: 'red'
            });
            
        });
    </script>
    <style type="text/css">
        .classz { margin-right: 300px;  margin-bottom: 100px;}
    </style>
</head>

<body>
<div id="container" style="margin: 50px;">
<form method="post" action="">

<div class="classz">
    <select name="test">
        <option value="value1">Some text 1</option>
        <option value="value2">Some text 2</option>
        <option value="value3">Some text 3</option>
        <option value="value4">Some text 4</option>
        <option selected="selected" value="sometext5">Some text 5</option>
        <option value="value6">Some text 6</option>
        <option value="value7">Some text 7</option>
        <option value="value8">Some text 8</option>
        <option value="value9">Some text 9</option>
        <option value="value10">Some text 10 Some text 10</option>
        <option value="value11">Some text 11</option>
        <option value="value12">Some text 12</option>
        <option value="value13">Some text 13</option>
        <option value="value14">Some text 14</option>
        <option value="value15">Some text 15</option>
    </select>
    <select name="test2">
        <option value="value12" selected="selected">Some text 12</option>
        <option value="">Some text 22</option>
        <option>Some text 32</option>
        <option>Some text 42</option>
        <option>Some text 5</option>
        <option>Some text 6</option>
        <option>Some text 7</option>
        <option>Some text 8</option>
        <option>Some text 9</option>
        <option>Some text 10 Some text 10</option>
        <option>Some text 11</option>
        <option>Some text 12</option>
        <option>Some text 13</option>
        <option>Some text 14</option>
        <option>Some text 15</option>
    </select>
</div>
    
<div class="classz-2">
    <select name="test3">
        <option value="value12" selected="selected">Some text 12</option>
        <option value="">Some text 22</option>
        <option>Some text 32</option>
        <option>Some text 42</option>
        <option>Some text 5</option>
        <option>Some text 6</option>
        <option>Some text 7</option>
        <option>Some text 8</option>
        <option>Some text 9</option>
        <option>Some text 10 Some text 10</option>
        <option>Some text 11</option>
        <option>Some text 12</option>
        <option>Some text 13</option>
        <option>Some text 14</option>
        <option>Some text 15</option>
    </select>
</div> 
      <input type="submit" />
</form>
      
      <?php
        echo $_POST['test']."<br />".$_POST['test2']."<br />".$_POST['test3'];
      ?>
</div>
</body>
</html>