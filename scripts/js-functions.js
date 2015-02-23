


<!-- layervis - generic togggler for show/hide on any divs by id-->
function toggleLayerVis(id){
if (document.getElementById) {
	if (this.document.getElementById(id).style.display=="none")
		(this.document.getElementById(id).style.display="block") ;
	else
		(this.document.getElementById(id).style.display="none") ;
	}
else if (document.all) {
	if (this.document.all[id].style.display=="none")
		(this.document.all[id].style.display="block") ;
	else
		(this.document.all[id].style.display="none") ;
	}
else if (document.layers) {
	if (this.document.layers[id].style.display=="none")
		(this.document.layers[id].style.display="block") ;
	else
		(this.document.layers[id].style.display="none") ;
	}
}

