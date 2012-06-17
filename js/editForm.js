
// Canvas - Context
var canvas;
var context;

// Mouse
var mousePos;
var mouseDown;

// Canvas list (undo)
var container;
var canStack = new Array();
var conStack = new Array();

// Loop
var loopInterval;
var drawing = false;

// Coordinates
var beginCoord;
var endCoord;

// Mode
var mode = "line";
var color = {r:0, g:0, b:0};
var textFont = 1;

// Rectangle
var rectangle = "stroke";

// Modes
function setMode(m) {
  removeOptions();
  mode = m;

  if (mode == "rectangle") {
    rectOptions();
  } else if (mode == "image") {
    imageOptions();
  } else if (mode == "textbox") {
    textboxOptions();
  }
}

function setColor(c) {
  var cs = document.getElementById("colorSelect")
  cs.removeChild(cs.firstChild);
  if (c == "white") {
    color = {r: 255, g:255, b:255};
    cs.setAttribute("onclick", 'setColor("black")');
    cs.appendChild(document.createTextNode("Black"));
  } else {
    color = {r:0, g:0, b:0};
    cs.setAttribute("onclick", 'setColor("white")');
    cs.appendChild(document.createTextNode("white"));
  }
}

// Loop
function startLoop()
{
  stopLoop();
  loopInterval = setInterval(loop, 1000/60);
}
function stopLoop()
{
  clearInterval(loopInterval);
}

function loop() {
  /*context.fillStyle = "rgb(" + Math.floor(255 * Math.random()) + "," + Math.floor(Math.random() * 255) + "," + Math.floor(Math.random() * 255) + ")";
  context.fillRect(0,0,context.width,context.height);*/

  if (mode == "textbox" && conStack.length > 0 && conStack[conStack.length-1]['x1'] != undefined) {
    redrawTextbox();
  }

  if (mouseDown) {
    if (!drawing) {
      canStack.push(document.createElement('canvas'));
      var lastIndex = canStack.length -1;
      canStack[lastIndex].id     = 'imageTemp';
      canStack[lastIndex].width  = canvas.width;
      canStack[lastIndex].height = canvas.height;
      container.appendChild(canStack[lastIndex]);

      conStack.push(canStack[lastIndex].getContext('2d'));

      beginCoord = mousePos;
      drawing = true;
    } else {
      conStack[conStack.length-1].fillStyle = "rgb("+color.r+","+color.g+","+color.b+")";
      conStack[conStack.length-1].strokeStyle = "rgb("+color.r+","+color.g+","+color.b+")";
      conStack[conStack.length-1].lineWidth = 2;
      if (mode == "freehand") {
        drawLine();
        beginCoord = mousePos;
      } else {
        conStack[conStack.length-1].clearRect(0,0,canvas.width,canvas.height);
        if (mode == "line") {
          drawLine();
        } else if (mode == "rectangle") {
          drawRect();
        } else if (mode == "textbox") {
          drawTextbox();
        }
      }
    }
  }
  if (!mouseDown && drawing) {
    drawing = false;

  }
}

// Line
function drawLine() {
  conStack[conStack.length-1].beginPath();
  conStack[conStack.length-1].moveTo(beginCoord.x,beginCoord.y);
  conStack[conStack.length-1].lineTo(mousePos.x,mousePos.y);
  conStack[conStack.length-1].stroke();
}

// Rectangle
/*
fillRect(x,y,width,height) : Draws a filled rectangle
strokeRect(x,y,width,height) : Draws a rectangular outline
clearRect(x,y,width,height) : Clears the specified area and makes it fully transparent
*/
function drawRect() {
  if (rectangle == "stroke") {
    conStack[conStack.length-1].strokeRect(beginCoord.x, beginCoord.y, mousePos.x-beginCoord.x, mousePos.y-beginCoord.y);
  } else if (rectangle == "fill") {
    conStack[conStack.length-1].fillRect(beginCoord.x, beginCoord.y, mousePos.x-beginCoord.x, mousePos.y-beginCoord.y);
  }
}

function drawTextbox() {
  conStack[conStack.length-1].strokeStyle = "rgb(0,0,255)";
  conStack[conStack.length-1].strokeRect(beginCoord.x, beginCoord.y, mousePos.x-beginCoord.x, mousePos.y-beginCoord.y);
  document.getElementById('slideX').value = beginCoord.x;
  document.getElementById('slideY').value = beginCoord.y;
  document.getElementById('slideL').value = mousePos.x - beginCoord.x;
  document.getElementById('slideH').value = mousePos.y - beginCoord.y;

  conStack[conStack.length-1]['x1'] = beginCoord.x;
  conStack[conStack.length-1]['y1'] = beginCoord.y;
  conStack[conStack.length-1]['x2'] = mousePos.x;
  conStack[conStack.length-1]['y2'] = mousePos.y;
  conStack[conStack.length-1].strokeStyle = "rgb(0,0,0)";
  setTextFont();
}

function textboxOptions() {
  var options = document.getElementById("options");

  /*
  conStack[conStack.length-1]['x1'] = beginCoord.x;
  conStack[conStack.length-1]['y1'] = beginCoord.y;
  conStack[conStack.length-1]['x2'] = mousePos.x;
  conStack[conStack.length-1]['y2'] = mousePos.y;
  */

  var div1 = document.createElement("div");
  div1.id = "sliderX";
  var p1 = document.createElement("p");
  p1.appendChild(document.createTextNode("X: 0 "));
  var text1 = document.createElement("input");
  text1.setAttribute("type", "text");
  text1.setAttribute("id", "textX");
  text1.setAttribute("onkeyup", "document.getElementById('slideX').value = this.value * 2; textMoveX()");
  text1.setAttribute("size", "4");
  text1.value = 0;
  p1.appendChild(text1);
  p1.appendChild(document.createElement("br"));
  var slider1 = document.createElement("input");
  slider1.type = "range";
  slider1.id = "slideX";
  slider1.min = "0";
  slider1.max = "480";
  slider1.value = "0";
  slider1.addEventListener('change', textMoveX, false);
  p1.appendChild(slider1);
  div1.appendChild(p1);
  options.appendChild(div1);

  var div2 = document.createElement("div");
  div2.id = "sliderY";
  var p2 = document.createElement("p");
  p2.appendChild(document.createTextNode("Y: 0 "));
  var text2 = document.createElement("input");
  text2.setAttribute("type", "text");
  text2.setAttribute("id", "textY");
  text2.setAttribute("onkeyup", "document.getElementById('slideY').value = this.value * 2; textMoveY()");
  text2.setAttribute("size", "4");
  text2.value = 0;
  p2.appendChild(text2);
  p2.appendChild(document.createElement("br"));
  var slider2 = document.createElement("input");
  slider2.type = "range";
  slider2.id = "slideY";
  slider2.min = "0";
  slider2.max = "256";
  slider2.value = "0"
  slider2.addEventListener('change', textMoveY, false);
  p2.appendChild(slider2);
  div2.appendChild(p2);
  options.appendChild(div2);

  var div3 = document.createElement("div");
  div3.id = "sliderL";
  var p3 = document.createElement("p");
  p3.appendChild(document.createTextNode("length: 0 "));
  var text3 = document.createElement("input");
  text3.setAttribute("type", "text");
  text3.setAttribute("id", "textL");
  text3.setAttribute("onkeyup", "document.getElementById('slideL').value = this.value * 2; textLength()");
  text3.setAttribute("size", "4");
  text3.value = 0;
  p3.appendChild(text3);
  p3.appendChild(document.createElement("br"));
  var slider3 = document.createElement("input");
  slider3.type = "range";
  slider3.id = "slideL";
  slider3.min = "0";
  slider3.max = "480";
  slider3.value = "0";
  slider3.addEventListener('change', textLength, false);
  p3.appendChild(slider3);
  div3.appendChild(p3)
  options.appendChild(div3);

  var div4 = document.createElement("div");
  div4.id = "sliderH";
  var p4 = document.createElement("p");
  p4.appendChild(document.createTextNode("height: 0 "));
  var text4 = document.createElement("input");
  text4.setAttribute("type", "text");
  text4.setAttribute("id", "textH");
  text4.setAttribute("onkeyup", "document.getElementById('slideH').value = this.value * 2; textHeight()");
  text4.setAttribute("size", "4");
  text4.value = 0;
  p4.appendChild(text4);
  p4.appendChild(document.createElement("br"));
  var slider4 = document.createElement("input");
  slider4.type = "range";
  slider4.id = "slideH";
  slider4.min = "0";
  slider4.max = "256";
  slider4.value = "0";
  slider4.addEventListener('change', textHeight, false);
  p4.appendChild(slider4);
  div4.appendChild(p4)
  options.appendChild(div4);
  
  var fontbox = document.createElement("select");
  fontbox.setAttribute("onchange", "changeTextFont()");
  fontbox.setAttribute("id", "fontbox");
  var option1 = document.createElement("option");
  option1.setAttribute("value", "1");
  option1.appendChild(document.createTextNode("Title"));
  var option2 = document.createElement("option");
  option2.setAttribute("value", "2");
  option2.appendChild(document.createTextNode("subtitle"));
  var option3 = document.createElement("option");
  option3.setAttribute("value", "3");
  option3.appendChild(document.createTextNode("text"));

  fontbox.appendChild(option1);
  fontbox.appendChild(option2);
  fontbox.appendChild(option3);
  fontbox.selectedIndex = 0;
  var p5 = document.createElement("p");
  p5.appendChild(document.createTextNode("Font: "));
  p5.appendChild(fontbox);
  options.appendChild(p5);
}

function changeTextFont() {
  var fontbox = document.getElementById("fontbox");
  textFont = fontbox.options[fontbox.selectedIndex].value;
  setTextFont();
}

function setTextFont() {
  if (conStack[conStack.length-1]['x1'] != undefined) {
    conStack[conStack.length-1]['textFont'] = textFont;
  }
}

function redrawTextbox() {
  var lastIndex = conStack.length-1;
  conStack[lastIndex].clearRect ( 0 , 0 , 480 , 256 );

  conStack[lastIndex].strokeStyle = "rgb(0,0,255)";
  conStack[lastIndex].lineWidth = 2;
  conStack[lastIndex].strokeRect(conStack[lastIndex]['x1'], conStack[lastIndex]['y1'], conStack[lastIndex]['x2']-conStack[lastIndex]['x1'], conStack[lastIndex]['y2']-conStack[lastIndex]['y1']);
  conStack[lastIndex].strokeStyle = "rgb(0,0,0)";

  var sliderX = document.getElementById("sliderX");
  sliderX.childNodes[0].childNodes[0].nodeValue = "X: "+ document.getElementById('slideX').value/2 +" ";
  var sliderY = document.getElementById("sliderY");
  sliderY.childNodes[0].childNodes[0].nodeValue = "Y: "+ document.getElementById('slideY').value/2 +" ";
  var sliderL = document.getElementById("sliderL");
  sliderL.childNodes[0].childNodes[0].nodeValue = "Length: "+ document.getElementById('slideL').value/2 +" ";
  var sliderH = document.getElementById("sliderH");
  sliderH.childNodes[0].childNodes[0].nodeValue = "Height: "+ document.getElementById('slideH').value/2 +" ";
}

function checkX(x) {
  if (x > 480) {
    x = 480;
  }
  if (x < 0) {
    x = 0;
  }
  return x;
}

function checkY(y) {
  if (y > 256) {
    y = 256;
  }
  if (y < 0) {
    y = 0;
  }
  return y;
}

function textMoveX() {
  var x = document.getElementById('slideX').value;
  var lastIndex = conStack.length-1;
  var d = x - conStack[lastIndex]['x1'];
  conStack[lastIndex]['x1'] = checkX(x);
  conStack[lastIndex]['x2'] = checkX(conStack[lastIndex]['x2'] + d);

  redrawTextbox();
}

function textMoveY() {
  var y = document.getElementById('slideY').value;
  var lastIndex = conStack.length-1;
  var d = y - conStack[lastIndex]['y1'];
  conStack[lastIndex]['y1'] = checkY(y);
  conStack[lastIndex]['y2'] = checkY(conStack[lastIndex]['y2'] + d);

  redrawTextbox();
}

function textLength() {
  var l = document.getElementById('slideL').value;
  var lastIndex = conStack.length-1;
  var l1 = conStack[lastIndex]['x2'] - conStack[lastIndex]['x1'];
  conStack[lastIndex]['x2'] = checkX(conStack[lastIndex]['x2'] + (l - l1));

  redrawTextbox();
}

function textHeight() {
  var h = document.getElementById('slideH').value;
  var lastIndex = conStack.length-1;
  var h1 = conStack[lastIndex]['y2'] - conStack[lastIndex]['y1'];
  conStack[lastIndex]['y2'] = checkY(conStack[lastIndex]['y2'] + (h - h1));

  redrawTextbox();
}



function rectOptions() {
  var options = document.getElementById("options");
  var stroke = document.createElement("BUTTON");
  stroke.setAttribute("onClick", 'setRectMode("stroke")');
  stroke.appendChild(document.createTextNode("Outline"));
  options.appendChild(stroke);

  var fill = document.createElement("button");
  fill.setAttribute("onClick", 'setRectMode("fill")');
  fill.appendChild(document.createTextNode("Fill"));
  options.appendChild(fill);
}

function setRectMode(m) {
  rectangle = m;
}

// Images
function imageOptions() {
  var options = document.getElementById("options");
  //<input type="file" id="files" name="files[]" />
  var files = document.createElement("input");
  files.setAttribute("type", "file");
  files.setAttribute("id", "files");
  files.setAttribute("name", "files[]");
  files.addEventListener('change', handleFileSelect, false);
  options.appendChild(files);
  //<input type="range" id="slide" min="1" max="480" value="240" />
  options.appendChild(document.createTextNode("Resize: "));
  var slider = document.createElement("input");
  slider.type = "range";
  slider.id = "slide";
  slider.min = "1";
  slider.max = "480";
  slider.value = "240";
  slider.addEventListener('change', imageResize, false);
  options.appendChild(slider);
  //<input type="range" id="threshold" min="0" max="255" value="127" />
  options.appendChild(document.createTextNode("Threshold: "));
  var threshold = document.createElement("input");
  threshold.setAttribute("type", "range");
  threshold.setAttribute("id", "threshold");
  threshold.setAttribute("min", "0");
  threshold.setAttribute("max", "255");
  threshold.setAttribute("value", "127");
  threshold.addEventListener('change', imageThreshold, false);
  options.appendChild(threshold);
}

//document.getElementById('slide').onchange = function() {
function imageResize() {
  val = document.getElementById('slide').value;

  var lastIndex = conStack.length-1;
  conStack[lastIndex].width = val;
  conStack[lastIndex].height = 256 * val / 480;

  conStack[lastIndex].clearRect ( 0 , 0 , 480 , 256 );

  var ratioX = conStack[lastIndex].width / conStack[lastIndex].originalImg.width;
  var ratioY = conStack[lastIndex].height / conStack[lastIndex].originalImg.height;
  var ratio = ratioX;

  if (ratioY < ratioX) {
    ratio = ratioY;
  }
  var imgW = conStack[lastIndex].originalImg.width * ratio;
  var imgH = conStack[lastIndex].originalImg.height * ratio;
  conStack[lastIndex].drawImage(conStack[lastIndex].originalImg,0,0, imgW, imgH);
  
  /*alert(ctx.width);
  alert(ctx.height);*/

  var thres = document.getElementById('threshold').value;


  var imgPixels = conStack[lastIndex].getImageData(0, 0, imgW, imgH);
  for(var y = 0; y < imgPixels.height; y++){
    for(var x = 0; x < imgPixels.width; x++){
      var i = (y * 4) * imgPixels.width + x * 4;
      var avg = (imgPixels.data[i] + imgPixels.data[i + 1] + imgPixels.data[i + 2]) / 3;
      if (avg >= thres) {
        avg = 255;
      } else {
        avg = 0;
      }
      imgPixels.data[i] = avg;
      imgPixels.data[i + 1] = avg;
      imgPixels.data[i + 2] = avg;
    }
  }
  conStack[lastIndex].putImageData(imgPixels, 0, 0, 0, 0, imgW, imgH);
}

//document.getElementById('threshold').onchange = function() {
function imageThreshold() {
  var t = document.getElementById('threshold').value;

  var lastIndex = conStack.length-1;
  var ratioX = conStack[lastIndex].width / conStack[lastIndex].originalImg.width;
  var ratioY = conStack[lastIndex].height / conStack[lastIndex].originalImg.height;
  var ratio = ratioX;

  var thres = document.getElementById('threshold').value;

  if (ratioY < ratioX) {
    ratio = ratioY;
  }
  var imgW = conStack[lastIndex].originalImg.width * ratio;
  var imgH = conStack[lastIndex].originalImg.height * ratio;
  conStack[lastIndex].drawImage(conStack[lastIndex].originalImg,0,0, imgW, imgH);

  var imgPixels = conStack[lastIndex].getImageData(0, 0, imgW, imgH);
  for(var y = 0; y < imgPixels.height; y++){
    for(var x = 0; x < imgPixels.width; x++){
      var i = (y * 4) * imgPixels.width + x * 4;
      var avg = (imgPixels.data[i] + imgPixels.data[i + 1] + imgPixels.data[i + 2]) / 3;
      if (avg >= thres) {
        avg = 255;
      } else {
        avg = 0;
      }
      imgPixels.data[i] = avg;
      imgPixels.data[i + 1] = avg;
      imgPixels.data[i + 2] = avg;
    }
  }
  conStack[lastIndex].putImageData(imgPixels, 0, 0, 0, 0, imgW, imgH);
}

function handleFileSelect(evt) {
  var files = evt.target.files; // FileList object

  // create canvas + context
  canStack.push(document.createElement('canvas'));
  var lastIndex = canStack.length -1;
  canStack[lastIndex].id     = 'imageTemp';
  canStack[lastIndex].width  = canvas.width;
  canStack[lastIndex].height = canvas.height;
  container.appendChild(canStack[lastIndex]);

  conStack.push(canStack[lastIndex].getContext('2d'));

  var val = document.getElementById('slide').value;
  conStack[lastIndex].width = val;
  conStack[lastIndex].height = 256 * (val / 480);
  /*conStack[lastIndex].clearRect(0,0,canvas.width,canvas.height);
  conStack[lastIndex].fillRect(0,0,50,50);*/

  // Only process image files.
  if (!files[0].type.match('image.*')) {
    alert("Can't read this file.");
    return;
  }

  var file = files[0]; // this is the file I want!!
  var filereader = new FileReader();
  filereader.file = file;
  filereader.onload = function(e) {
    var file = this.file; // there it is!
  }

  var url = window.URL || window.webkitURL;
  var img = new Image();
  img.src = url.createObjectURL(file);

  var thres = document.getElementById('threshold').value;

  //buffer.getContext('2d').drawImage(ctx, 0, 0);
  //ctx.drawImage(img, 0, 0);
  img.onload = function() {
    conStack[lastIndex].originalImg = img;
    var ratioX = conStack[lastIndex].width / img.width;
    var ratioY = conStack[lastIndex].height / img.height;
    var ratio = ratioX;

    if (ratioY < ratioX) {
      ratio = ratioY;
    }
    var imgW = img.width * ratio;
    var imgH = img.height * ratio;
    conStack[lastIndex].drawImage(img,0,0, imgW, imgH);
    
    /*alert(ctx.width);
    alert(ctx.height);*/

    var imgPixels = conStack[lastIndex].getImageData(0, 0, imgW, imgH);
    for(var y = 0; y < imgPixels.height; y++){
      for(var x = 0; x < imgPixels.width; x++){
        var i = (y * 4) * imgPixels.width + x * 4;
        var avg = (imgPixels.data[i] + imgPixels.data[i + 1] + imgPixels.data[i + 2]) / 3;
        if (avg >= thres) {
          avg = 255;
        } else {
          avg = 0;
        }
        imgPixels.data[i] = avg;
        imgPixels.data[i + 1] = avg;
        imgPixels.data[i + 2] = avg;
      }
    }
    conStack[lastIndex].putImageData(imgPixels, 0, 0, 0, 0, imgW, imgH);
  }
}

// Undo
function undo() {
  if (conStack.length > 0) {
    conStack[conStack.length-1].clearRect(0,0,canvas.width, canvas.height);
    conStack.pop();
    canStack.pop();
  }
}

// General
function removeOptions() {
  var options = document.getElementById("options");
  while(options.hasChildNodes()) options.removeChild(options.firstChild);
}

window.onload = function(){
  canvas = document.getElementById('canvas');
  context = canvas.getContext('2d');
  container = canvas.parentNode;

  context.width = canvas.width;
  context.height = canvas.height;
  context.clearRect(0,0,context.width, context.height);

  canvas.addEventListener("mousedown", function(){
      mouseDown = true;
  }, false);

  canvas.addEventListener("mouseup", function(){
      mouseDown = false;
  }, false);

  canvas.addEventListener('mousemove', function(evt){
      mousePos = getMousePos(canvas, evt);
      /*var message = "Mouse position: " + mousePos.x + "," + mousePos.y;*/
  }, false);



  startLoop();
}

function getMousePos(canvas, evt){
  // get canvas position
  var obj = canvas;
  var top = 0;
  var left = 0;
  while (obj && obj.tagName != 'BODY') {
      top += obj.offsetTop;
      left += obj.offsetLeft;
      obj = obj.offsetParent;
  }

  // return relative mouse position
  var mouseX = evt.clientX - left + window.pageXOffset;
  var mouseY = evt.clientY - top + window.pageYOffset;
  return {
      x: mouseX,
      y: mouseY
  };
}

// Transmit
function transmit()
{

  var tempCanvas = document.createElement('canvas');
  tempCanvas.id     = 'imageTrasmit';
  tempCanvas.width  = canvas.width;
  tempCanvas.height = canvas.height;
  container.appendChild(tempCanvas);

  var ajaxData = {};
  ajaxData.textbox = new Array();

  var j =0;
  for (i = 0; i < canStack.length; i++) {
    if (conStack[i]['x1'] != undefined) {
      ajaxData.textbox[j] = {x1: 0, x2: 0, y1: 0, y2: 0};
      ajaxData.textbox[j]['x1'] = conStack[i]['x1']/2;
      ajaxData.textbox[j]['y1'] = conStack[i]['y1']/2;
      ajaxData.textbox[j]['x2'] = conStack[i]['x2']/2;
      ajaxData.textbox[j]['y2'] = conStack[i]['y2']/2;
      ajaxData.textbox[j]['textFont'] = conStack[i]['textFont'];
      j ++;
    } else {
      tempCanvas.getContext('2d').drawImage(canStack[i],0,0);
    }
  }

  var oldCanvas = tempCanvas.toDataURL("image/png");
  var tctx = tempCanvas.getContext('2d');
  var img = new Image();
  img.src = oldCanvas;
  img.onload = function (){
    tctx.fillStyle = "rgb(240,240,240)";
    tctx.fillRect(0,0,240,128);
    tctx.drawImage(img, 0, 0, 240, 128);
    var imageData = tctx.getImageData(0, 0, 240, 128);
    var data = imageData.data;
    ajaxData.coords = new Array();
    var i = 0;

    for (var y = 0; y < 128; y++) {
      // loop through each column
      for (var x = 0; x < 240; x++) {
        var red = data[((240 * y) + x) * 4];
        var green = data[((240 * y) + x) * 4 + 1];
        var blue = data[((240 * y) + x) * 4 + 2];
        //var alpha = data[((240 * y) + x) * 4 + 3];
        //alert("red: " + red + " green: " + green + " blue: " + blue + " alpha: " + alpha);
        var alpha = (red + green + blue) / 3
        if (alpha < 200) {
          ajaxData.coords[i] = {};
          ajaxData.coords[i]['x'] = x;
          ajaxData.coords[i]['y'] = y;
          //coords[i]['a'] = alpha;
          i++;
        }
      }
    }
    tctx.clearRect(0, 0, canvas.width, canvas.height);
    container.removeChild(tempCanvas);
    alert(JSON.stringify(ajaxData));
    jQuery_send_data(JSON.stringify(document.getElementById("formName").value), JSON.stringify(ajaxData.coords), JSON.stringify(ajaxData.textbox));
    alert("Done.");
  }
}

function jQuery_send_data(name, coords, textbox) {
$.ajax({
    type: "POST",
    url: "/final/test5.php",
    dataType: "json",
    traditional: true,
    data: {
      name: name,
      coords: coords,
      textbox: textbox,
    },
    success: function(data){
        alert("Done.");
    }
});
}