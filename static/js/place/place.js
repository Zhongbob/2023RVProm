let prevID = 0;
let pixelInfo = {};
const selectColorDiv = document.querySelector("#colorpick");
const colorsdiv = document.getElementsByClassName("colors");
const colorPicker = document.querySelector("#colorpicker");
const canvasPicker = document.querySelector("#canvas-picker");
const submitColor = document.querySelector("#submitcolor");
let selectedBox;
let selectedColor;
let pickingCanvasColor = false;
let prevBox;
let prevSelector;
var timeleft = 0
var pixelsLeft;

async function loadData() {
  result = await getRequest("backend/ScavengerHunt/Place/place.inc.php", {}, null, true, true);
  console.log(result)
  return result;
}
function updatePixelsLeft(remainingPixels){
    pixelsLeft = remainingPixels
  document.querySelector("#pixels-left").innerHTML = remainingPixels;

}
async function loadCanvas(data){
    if (data["prevID"] != 0) prevID = data["prevID"];
    if (data["pixelCount"]) updatePixelsLeft(100-Number(data["pixelCount"]));
    for (let i = 0; i < data["X"].length; i++) {
        renderPixel({
          x: data["X"][i],
          y: data["Y"][i],
          color: data["color"][i],
          user: data["user"][i],
        });
        pixelInfo[`${data["X"][i]},${data["Y"][i]}`] = {
          user: data["user"][i],
          color: data["color"][i],
        };
      }
}
async function initCanvas() {
  const data = await loadData();
  await loadCanvas(data);
  initColorPicker();
  updatePlace()
}

function setupStage() {
  // Setting up canvas with Pixi.js
  app = new PIXI.Application(window.innerWidth, window.innerHeight - 60, {
    antialias: false,
    backgroundColor: 0xeeeeee,
  });
  app.stage.sortableChildren = true;

  canvasContainer.appendChild(app.view);
  // create a container for the grid
  // container will be used for zooming
  container = new PIXI.Container();
  // and container to the stage
  app.stage.addChild(container);
  // graphics is the cavas we draw the
  // pixels on, well also move this around
  // when the user drags around
  graphics = new PIXI.Graphics();
  graphics.beginFill(0xffffff, 1);
  graphics.drawRect(
    0,
    0,
    gridSize[0] * squareSize[0],
    gridSize[1] * squareSize[1]
  );
  graphics.interactive = true;
  // setup input listeners
  graphics.on("pointerdown", onDown);
  graphics.on("pointermove", onMove);
  graphics.on("pointerup", onUp);
  graphics.on("pointerupoutside", onUp);
  graphics.on("wheel", onScrollZoom);

  // move graphics so that it's center
  // is at x0 y0
  graphics.position.x = -graphics.width / 2;
  graphics.position.y = -graphics.height / 2;
  container.addChild(graphics);

  gridLines = new PIXI.Graphics();
  gridLines.lineStyle(0.5, 0x888888, 1);
  gridLines.alpha = 0;
  gridLines.position.x = graphics.position.x;
  gridLines.position.y = graphics.position.y;

  for (let i = 0; i <= gridSize[0]; i++) {
    drawLine(
      0,
      i * squareSize[0],
      gridSize[0] * squareSize[0],
      i * squareSize[0]
    );
  }
  for (let j = 0; j <= gridSize[1]; j++) {
    drawLine(
      j * squareSize[1],
      0,
      j * squareSize[1],
      gridSize[1] * squareSize[1]
    );
  }
  container.addChild(gridLines);

  hoverBox = new PIXI.Graphics();
  hoverBox.position.x = graphics.position.x;
  hoverBox.position.y = graphics.position.y;
  container.addChild(hoverBox);

  clickBox = new PIXI.Graphics();
  clickBox.position.x = graphics.position.x;
  clickBox.position.y = graphics.position.y;
  container.addChild(clickBox);

  userNameBox = new PIXI.Graphics();
  userNameBox.position.x = graphics.position.x;
  userNameBox.position.y = graphics.position.y;
  container.addChild(userNameBox);
  userNameText = new PIXI.Text("", {
    fontFamily: 'Arial',
    fontSize: 30,
    fill: 0xffffff // White color
});
userNameText.scale.set(5/30);
  userNameBox.alpha = 0
  userNameBox.addChild(userNameText);
  window.onresize = onResize;

  onResize();
  initCanvas();
}

function colorPickerShowHide(show) {
  if (show) {
    selectColorDiv.classList.add("slideUp")
    selectColorDiv.classList.remove("slideDown")
}
  else {
    selectColorDiv.classList.remove("slideUp")
    selectColorDiv.classList.add("slideDown")
}
}

function initColorPicker() {
  let colorlst = [
    '#FF0000', // Red
  '#FF7F50', // Coral
  '#FFA500', // Orange
  '#FFFF00', // Yellow
  '#00FF00', // Lime
  '#008000', // Green
  '#00FFFF', // Cyan
  '#0000FF', // Blue
  '#EE82EE', // Violet
  '#800080', // Purple
  '#FFC0CB', // Pink
  '#FFD2BB', // Skin Tone
  '#A52A2A', // Brown
  '#808080',  // Grey
  '#FFFFFF', // White
  '#000000', // Black
  
  ];
  for (let i = 0; i < colorsdiv.length; i++) {
    colorsdiv[i].style.backgroundColor = colorlst[i];
    colorsdiv[i].onclick = function () {
      updateSelectEle.call(this);
    };
  }
  colorPicker.onclick = function () {
    updateSelectEle.call(this, true);
  };
  colorPicker.oninput = function () {
    updateSelectEle.call(this, true);
  };
  canvasPicker.onclick = function () {
    pickingCanvasColor = !pickingCanvasColor;
    if (pickingCanvasColor) {
      updateColorSelectors(this);
      document.body.classList.add("picking");
    }
    else{
      updateColorSelectors()
      document.body.classList.remove("picking");
    }
  }

}
function rgbStringToHex(rgbString) {
  var match = rgbString.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
  if (match) {
    var r = parseInt(match[1]);
    var g = parseInt(match[2]);
    var b = parseInt(match[3]);
    if (r > 255 || g > 255 || b > 255) {
      throw "Invalid color component";
    }
    return ((r << 16) | (g << 8) | b).toString(16).padStart(6, "0");
  } else {
    throw "Invalid format";
  }
}

function revertColor(pixel) {
  var curPixelInfo = pixelInfo[pixel.join(",")];
  var prevColor = curPixelInfo ? curPixelInfo["color"] : "FFFFFF";
  renderPixel({ x: pixel[0], y: pixel[1], color: prevColor });
}
function updateSelectEle(isColorPicker) {
  //triggered when the user changes the color of the selected tile
  if (isColorPicker) selectedColor = this.value.substring(1);
  else selectedColor = rgbStringToHex(this.style.backgroundColor);
  if (selectedBox == null) return;
  renderPixel({ x: selectedBox[0], y: selectedBox[1], color: selectedColor });
  this.style.border = "";
  updateColorSelectors(this);
}

function updateColorSelectors(curr){
  if (prevSelector) prevSelector.classList.remove("selected-color");
  if (curr) {
    curr.classList.add("selected-color")
    prevSelector = curr;
}
  else prevSelector = null;
}

function unselectTile(newTile) {
  if (pickingCanvasColor){
    var curPixelInfo = pixelInfo[newTile.join(",")];
    var prevColor = curPixelInfo ? curPixelInfo["color"] : "FFFFFF";
    colorPicker.value = "#" + prevColor;
    pickingCanvasColor = false;
    document.body.classList.remove("picking");
    updateSelectEle.call(colorPicker, true);
    return;
  }
  if (selectedBox != null) {
    revertColor(selectedBox);
  }
  if (newTile != null) {
    selectedBox = newTile;
    if (selectedColor != null) {
      renderPixel({
        x: selectedBox[0],
        y: selectedBox[1],
        color: selectedColor,
      });
    }
    highlightPixel(newTile[0],newTile[1]);
    colorPickerShowHide(true);
  }
}
function loading() {
  document.querySelector("#loading").style.display = "block";
  document.querySelector("#main-text").style.display = "none";
}

function doneLoading(setMainText) {
  document.querySelector("#loading").style.display = "none";
  if (setMainText) document.querySelector("#main-text").style.display = "block";
}

function permColor(pixel, color) {
  var key = pixel.join(",");
  if (key in pixelInfo) {
    pixelInfo[key]["color"] = color;
  } else {
    pixelInfo[key] = { color: color, user: "You" };
  }
}
function permUser(pixel, user) {
    var key = pixel.join(",");
    if (key in pixelInfo) {
        pixelInfo[key]["user"] = user;
    } else {
        pixelInfo[key] = { color: "FFFFFF", user: user };
    }
}

function getInfo(pixel){
  var key = pixel.join(",");
  if (key in pixelInfo) {
    return pixelInfo[key];
  }
  return null;
}


function colorTile() {
  if (selectedBox == null) return;
  
  if (selectedColor != null) {
    const tempBox = selectedBox;
    const tempColor = selectedColor;
    postRequest(
      "backend/ScavengerHunt/Place/place.php",
      { color: selectedColor, X: selectedBox[0], Y: selectedBox[1] },
      (result) => {
        switch (result) {
          case 0:
            doneLoading(true);
            alert("Suspicious activity! Please Relogin");
            revertColor(tempBox);
            break;
          case 1:
            doneLoading(true);
            alert("Error! Please Relogin");
            revertColor(tempBox);
            break;
          case 3:
            doneLoading();
            permColor(tempBox, tempColor);
            updatePixelsLeft(pixelsLeft-1);
            break;
          default:
            doneLoading(false);
            const timeleft = result["timeleft"];
            revertColor(tempBox);
            alert(`Please wait ${timeleft}s`);
        }
      },
      true,
      true
    );
  }
}

function moveSelectedTile(move, moveDir) {
    var x = selectedBox[0];
    var y = selectedBox[1];
    if (moveDir == "x") x += move;
    else y += move;
    if (
      x >= 0 &&
      x < gridSize[0] &&
      y >= 0 &&
      y < gridSize[1]
    ) {
      unselectTile([x, y]);
      tweenTo(-x* squareSize[0] - squareSize[0]/2, -y* squareSize[1] - squareSize[1]/2,0.1);
    }
  }

document.addEventListener("keydown", function (event) {
  //moves the selected tile accroding to the key pressed
  if (event.keyCode == 13) {
    colorTile();
  }
  if (event.keyCode == 65 || event.keyCode == 37) {
    //q: which keycode corresponds to 65 and 37?
    //a: 65 is a and 37 is left arrow
    moveSelectedTile(-1, "x");
  }
  if (event.keyCode == 87 || event.keyCode == 38) {
    moveSelectedTile(-1, "y");
  }
  if (event.keyCode == 68 || event.keyCode == 39) {
    moveSelectedTile(1, "x");
  }
  if (event.keyCode == 40 || event.keyCode == 83) {
    moveSelectedTile(1, "y");
  }
});

submitColor.onclick = function () {
  colorTile();
}

async function updatePlace() {
    var result = await getRequest(
      `backend/ScavengerHunt/Place/placeupdate2.php`,
      { prevID },
      null,true,true
    );
    if (typeof result != "object") return 
    loadCanvas(result)
    await sleep(2000)
    updatePlace()

}
  
window.addEventListener('contextmenu', function(e) {
    e.preventDefault();
});
