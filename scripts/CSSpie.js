function createPie(pieName,pieSize,baseColor,numberOfSlices,percentages,colors){
	var sizeNum = parseFloat(pieSize.replace("px",""));
	//Pie Container
	var pieContainer = document.createElement("div");
	pieContainer.id=pieName;
	pieContainer.style.display="inline-block";
	//Pie Background
	var pieBackground = document.createElement("div");
	pieBackground.style.width=pieSize;
	pieBackground.style.height=pieSize;
	pieBackground.style.position="relative";
	pieBackground.style.webkitBorderRadius=pieSize;
	pieBackground.style.mozBorderRadius=pieSize;
	pieBackground.style.borderRadius=pieSize;
	pieBackground.style.backgroundColor=baseColor;
	//Append Background to Container
	pieContainer.appendChild(pieBackground);
	//Loop through Slices
	var beforeDegree = 0;
	var degree = 0;
	for(var i=0;i<numberOfSlices;i++){
		//New Slice
		var newSlice = document.createElement("div");
		newSlice.style.position="absolute";
		newSlice.style.top="0px"; newSlice.style.left="0px";
		newSlice.style.width=pieSize;
		newSlice.style.height=pieSize;
		newSlice.style.webkitBorderRadius=pieSize;
		newSlice.style.mozBorderRadius=pieSize;
		newSlice.style.borderRadius=pieSize;
		newSlice.style.clip="rect(0px,"+sizeNum+"px,"+sizeNum+"px,"+((sizeNum)/2)+"px)";
		//New Slice Pie
		var pie = document.createElement("div");
		pie.style.backgroundColor=colors[i];
		pie.style.position="absolute";
		pie.style.top="0px"; pie.style.left="0px";
		pie.style.width = pieSize;
		pie.style.height = pieSize; 
		pie.style.webkitBorderRadius = pieSize;
		pie.style.mozBorderRadius = pieSize;
		pie.style.borderRadius = pieSize;
		pie.style.clip = "rect(0px, "+((sizeNum)/2)+"px, "+sizeNum+"px, 0px)";
		//Get Percentage
		var piePercentage = percentages[i];
		//Check if Percentage > 50
		if(piePercentage<=50){
			degree = parseFloat((180*piePercentage)/50);
			pie.style.webkitTransform="rotate("+degree+"deg)";
			pie.style.mozTransform="rotate("+degree+"deg)";
			pie.style.transform="rotate("+degree+"deg)";
			newSlice.appendChild(pie);
			//If it's not first slice, then ...
			if(i!=0){
				newSlice.style.webkitTransform="rotate("+beforeDegree+"deg)";
				newSlice.style.mozTransform="rotate("+beforeDegree+"deg)";
				newSlice.style.transform="rotate("+beforeDegree+"deg)";
			}
			pieBackground.appendChild(newSlice);
			beforeDegree += degree;
		}
		else{	
			newSlice.style.clip="rect(0px,"+(sizeNum)+"px,"+(sizeNum)+"px,"+((sizeNum-100)/2)+"px)";
			newSlice.style.webkitTransform="rotate("+beforeDegree+"deg)";
			newSlice.style.mozTransform="rotate("+beforeDegree+"deg)";
			newSlice.style.transform="rotate("+beforeDegree+"deg)";
			pie.style.webkitTransform="rotate(180deg)";
			pie.style.mozTransform="rotate(180deg)";
			pie.style.transform="rotate(180deg)";
			newSlice.appendChild(pie);
			pieBackground.appendChild(newSlice);
			var newSlice = document.createElement("div");
			newSlice.style.position="absolute";
			newSlice.style.top="0px"; newSlice.style.left="0px";
			newSlice.style.width=pieSize;
			newSlice.style.height=pieSize;
			newSlice.style.webkitBorderRadius=pieSize;
			newSlice.style.mozBorderRadius=pieSize;
			newSlice.style.borderRadius=pieSize;
			newSlice.style.clip="rect(0px,"+sizeNum+"px,"+sizeNum+"px,"+((sizeNum)/2)+"px)";
			if(i!=0)
				beforeDegree = beforeDegree-1;
			newSlice.style.webkitTransform="rotate("+(180+beforeDegree)+"deg)";
			newSlice.style.mozTransform="rotate("+(180+beforeDegree)+"deg)";
			newSlice.style.transform="rotate("+(180+beforeDegree)+"deg)";
			if(i!=0)
				beforeDegree = beforeDegree+1;
			var pie = document.createElement("div");
			pie.style.backgroundColor=colors[i];
			pie.style.position="absolute";
			pie.style.top="0px"; pie.style.left="0px";
			pie.style.width = pieSize;
			pie.style.height = pieSize; 
			pie.style.webkitBorderRadius = pieSize;
			pie.style.mozBorderRadius = pieSize;
			pie.style.borderRadius = pieSize;
			pie.style.clip = "rect(0px, "+((sizeNum)/2)+"px, "+sizeNum+"px, 0px)";
			degree = parseFloat(((piePercentage-50)*180)/50);
			if(i!=0)
				degree=degree+1;
			pie.style.webkitTransform="rotate("+degree+"deg)";
			pie.style.mozTransform="rotate("+degree+"deg)";
			pie.style.transform="rotate("+degree+"deg)";
			if(i!=0)
				degree = degree-1;
			newSlice.appendChild(pie);
			pieBackground.appendChild(newSlice);
			beforeDegree += (180+degree);
		}
	}
	return pieContainer;	
}
