<button type="button" onclick='setMode("line")'>Line</button>
<button type="button" onclick='setMode("rectangle")'>Rectangle</button>
<button type="button" onclick='setMode("freehand")'>Freehand</button>
<button type="button" onclick='setMode("textbox")'>Textbox</button>
<button type="button" onclick='setColor("white")' id="colorSelect">White</button>
<button type="button" onclick='setMode("image")'>Image</button>
<button type="button" onclick='undo()'>Undo</button>
<button type="button" onclick="transmit()">Transmit</button>
<div id="options"></div>
<div id="canvasContainer">
<canvas id="canvas" width="480" height="256"></canvas>
</div>