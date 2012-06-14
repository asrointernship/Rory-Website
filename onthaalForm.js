function zoTerug(status) {
	var button = document.getElementById("statusButton");
	if (status == "zoterug") {
		button.setAttribute("onclick", "zoTerug(\"terug\");");
		button.childNodes[0].nodeValue = "Open";
	} else {
		button.setAttribute("onclick", "zoTerug(\"zoterug\");");
		button.childNodes[0].nodeValue = "Zo Terug";
	}

	$.ajax({
    type: "POST",
    url: "/final/onthaalJS.php",
    dataType: "json",
    traditional: true,
    data: {
      status: JSON.stringify(status)
    },
    success: function(data){
        // done
    }
	});
}

function deleteMededeling(id, nr) {
	var list = document.getElementById("mededelingenList");
	list.deleteRow(nr);
	var cells = list.getElementsByTagName("button");

	for (i = nr; i < cells.length; i ++) {
		var oc = cells[i].getAttribute("onclick");
		var i1 = oc.indexOf(",");
		cells[i].setAttribute("onclick", "deleteMededeling(" + oc.substr(17, i1-17) + ", " + nr + ")");
		nr++;
	}

	$.ajax({
    type: "POST",
    url: "/final/onthaalJS.php",
    dataType: "json",
    traditional: true,
    data: {
      mededeling: JSON.stringify(id)
    },
    success: function(data){
        // done
    }
	});
}

function selectText() {
	var text1 = document.getElementById("mededeling");
	text1.focus();
	text1.select();
}