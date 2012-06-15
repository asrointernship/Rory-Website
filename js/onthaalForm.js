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

function deleteForm(id, nr) {
	var list = document.getElementById("delformList");
	list.deleteRow(nr);
	var cells = list.getElementsByTagName("button");

	for (i = nr; i < cells.length; i ++) {
		var oc = cells[i].getAttribute("onclick");
		var i1 = oc.indexOf(",");
		cells[i].setAttribute("onclick", "deleteForm(" + oc.substr(11, i1-11) + ", " + nr + ")");
		nr++;
	}

	$.post(
    "/final/onthaalJS.php",
    {deleteForm: JSON.stringify(id)},
    function() {
    	window.location.reload();
    },
    "json");
}

function getTextBoxFromForm(select) {
	var option = select.options[select.selectedIndex].value;
	$.post(
    "/final/onthaalJS.php",
    {form: JSON.stringify(option)},
    function(data){
    	//alert("test");
    	//var struct = JSON.parse(data);
    	var ediv = document.getElementById("editTextboxes");
    	var p1 = document.createElement("p");
    	p1.appendChild(document.createTextNode("test"));
    	ediv.appendChild(p1);
    	while (ediv.hasChildNodes()) {
        	ediv.removeChild(ediv.lastChild);
        }
    	for (i = 0; i < data["data"].length; i ++) {
    		var invoer = document.createElement("input");
    		invoer.setAttribute("type", "text");
    		invoer.setAttribute("id", data["data"][i]["id"]);
    		invoer.setAttribute("value", data["data"][i]["content"]);
    		if (data["data"][i]["font"] == 1) {
    			ediv.appendChild(document.createTextNode("Title: "));
    		} else if(data["data"][i]["font"] == 2) {
    			ediv.appendChild(document.createTextNode("Subtitle: "));
    		} else if (data["data"][i]["font"] == 3) {
    			ediv.appendChild(document.createTextNode("Text: "));
    		}
    		ediv.appendChild(invoer);
    		ediv.appendChild(document.createElement("br"));
    	}
	}, "json");
}

function saveTextboxes() {
	var ediv = document.getElementById("editTextboxes");
	var ajaxData = new Array();
	var inputs = ediv.getElementsByTagName("input");
	for (i = 0; i < inputs.length; i ++) {
		ajaxData.push(new Array(inputs[i].id, inputs[i].value));
	}
	$.post(
    "/final/onthaalJS.php",
    {saveText: JSON.stringify(ajaxData)},
    function() { alert("Saved.")},
    "json");
}

function saveActiveScreens() {
	var ajaxData = new Array();
	ajaxData.push(document.getElementById("screen1Sel").value);
	ajaxData.push(document.getElementById("screen2Sel").value);
	ajaxData.push(document.getElementById("screen3Sel").value);

	$.post(
    "/final/onthaalJS.php",
    {saveScreens: JSON.stringify(ajaxData)},
	function() {alert("Saved.")},
    "json");
}

function selectText() {
	var text1 = document.getElementById("mededeling");
	text1.focus();
	text1.select();
}

function validFormName() {
    var n = document.getElementById("formName").value;
    $.ajax({
    type: "POST",
    url: "/final/onthaalJS.php",
    dataType: "json",
    traditional: true,
    data: {
      validform: JSON.stringify(n)
    },
    success: function(data){
        if (data == 1) {
        	transmit();
        } else {
        	alert("Form name taken.");
        }
    }
	});
 }