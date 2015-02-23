<?php

class gCHARTS {


	function __construct ()
	{
		//$this->enqueueScripts();
	}

	
	function enqueueScripts ()
	{
		$pluginFolder = plugins_url( '/google-charts/', dirname(__FILE__) );
		wp_enqueue_script( 'google-charts', 'https://www.google.com/jsapi' );
		wp_enqueue_script( 'gcharts-custom-js', $pluginFolder . 'googlecharts.js', array( 'jquery' ) );
	}
	
	
	function draw ( $chartType, $data, $elementID, $keyName = 'Keys', $valName = 'Values', $title = 'Chart:' )
	{
		
		if ( ! is_array( $data ) ) {
			return;
		}
		
		$c = 1;
		$dataCount = count( $data );
		
		$jsArray = "[ '" .$keyName. "', '" .$valName. "' ],";
		
		foreach ( $data as $i => $values ) 
		{
			$jsArray .= "[ '" .$values[0]. "', " .$values[1]. " ]" . ( $c < $dataCount ? ", " : "" );
			$c++;
		}
		?>
		
		<script>		
		jQuery( document ).ready( function () {
			G_CHARTS.charts.push({
				type:		'<?php echo $chartType; ?>',
				data: 		[ <?php echo $jsArray; ?> ],
				elementID:	'<?php echo $elementID; ?>',
				title:		'<?php echo $title; ?>'
			});
		});
		</script>		
		
		<?php
		
		// Draw the element
		$myStyle="";
		if($chartType=="pie")
		{
			$myStyle = 'width: 600px; height: 300px;';
		}
		if($chartType=="bar")
		{
			$myStyle = 'width: 95%;';
		}		
		echo '<div style="'.$myStyle.'" id="'.$elementID.'"></div>';

		
		
	}
	

}
?>