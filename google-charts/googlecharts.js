

//load google visualisations package
google.load('visualization', '1.0', {'packages':['corechart']});


var G_CHARTS = {

	charts: [],
	
	init: function () {
	
		var j;
		var l = this.charts.length;
		for ( j = 0; j < l; j += 1 ) {
			this.addChart( this.charts[ j ] );
		}
	
	},
	
	addChart: function ( dataObj ) {
		
		var callback = function () {
			var chart;
			var data = google.visualization.arrayToDataTable( dataObj.data );
			
			var barchartAreaHeight = data.getNumberOfRows() * 25;	
			var barchartHeight = barchartAreaHeight + 80;
			
			var piechartAreaHeight = data.getNumberOfRows() * 15;
			
			if(piechartAreaHeight<160){piechartAreaHeight=160;}
			
			if ( dataObj.type === 'pie' ) {
				var options = {
					title: dataObj.title,
					backgroundColor: '#f1f1f1',
					chartArea: {
						height: piechartAreaHeight
					}					
				};				
				chart = new google.visualization.PieChart( document.getElementById( dataObj.elementID ) );
			} else if ( dataObj.type === 'bar' ) {
				var options = {
					backgroundColor: '#f1f1f1',
					height: barchartHeight,
					chartArea: {
						height: barchartAreaHeight,
						left:250
					},
					legend: {position: 'none'}



				};				
				chart = new google.visualization.BarChart( document.getElementById( dataObj.elementID ) );
			} else { //table
				chart = new google.visualization.Table( document.getElementById( dataObj.elementID ) );
			}
			chart.draw( data, options );
		};
		
		google.setOnLoadCallback( callback );	
	}
	
};


jQuery( document ).ready( function () {

	G_CHARTS.init();

});

