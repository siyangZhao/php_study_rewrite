<?php
  if (isset($_SESSION['errors'])) 
  {
  	 ?>
  	 <div class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-margin-top">
    	 	<div class="am-radius am-alert <?php echo $_SESSION['errors']['state']; ?> " data-am-alert>
  		      <button type="button" class="am-close">&times;</button>
      		    <?php 
      		    	foreach ($_SESSION['errors']['details'] as $value)
      		    	{
      		    		?>
      		    		<p><?php echo $value; ?></p>
      		    		<?php
      		    	}
      		    ?>
  		  </div>
  	 </div>
  	 <?php	
  }
  unset($_SESSION['errors']);
?>
