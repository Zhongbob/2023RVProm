const canvasContainer = document.getElementById('canvas');
// get url variables
var urlParams = new URLSearchParams(window.location.search);
const gridSize = urlParams.get('level') == "comp"?[50,50]:[100, 100];
const squareSize = [10, 10];
let app;
let dragging = false;
let mouseDown = false;
let start;
let graphicsStart;
let zoomed = false;
let coolCount = 0;
let coolInterval;
let scale = 1;
let currentlyWriting;
let ready = true;
let body = document.body;
const zoomLevel = 3;
let graphics;
let hoverBox;
let clickBox;
let userNameBox;
let userNameText;
let curHoverTimer;
let initialDistance;
let curTouches = [];
const zoomInBtn = document.querySelector(".zoom-in")
const zoomOutBtn = document.querySelector(".zoom-out")
function getCorresPixel(x, y) {
    return [Math.floor(x/squareSize[0]), Math.floor(y/squareSize[1])];
}

function highlightPixel(x,y,event){
    event = event || "click"
    if (event == "click"){
        const color = 0xDA6061
        const opacity = 1
        clickBox.clear()
        clickBox.lineStyle(1.5, color, opacity);
        clickBox.drawRect(x*squareSize[0],y*squareSize[1],squareSize[0],squareSize[1])
    }
    else if (event == "hover"){
        const color = 0x71ADFC
        const opacity = 1
         hoverBox.clear()
        hoverBox.lineStyle(1, color, opacity);
        hoverBox.drawRect(x*squareSize[0],y*squareSize[1],squareSize[0],squareSize[1])
    }
}
function onResize(e) {
    // resize the canvas to fill the screen
    app.renderer.resize(window.innerWidth, window.innerHeight);
    // center the container to the new
    // window size.
    container.position.x = window.innerWidth / 2;
    container.position.y = window.innerHeight / 2;
}


function drawLine(x, y, x2, y2) {
    gridLines.moveTo(x, y);
    gridLines.lineTo(x2, y2);
}
function clearCanvas(){
    graphics.clear()
    graphics.beginFill(0xffffff, 1);
    graphics.drawRect(
        0,
        0,
        gridSize[0] * squareSize[0],
        gridSize[1] * squareSize[1]
    );
}
function displayUser(pixel){
    if (pixel == null){
        userNameBox.alpha = 0
        return
    }
    const info = getInfo(pixel)
    const [x,y] = pixel
    if (info){
        const user = info.user
        userNameText.text = user
        userNameText.x = (x*squareSize[0] + squareSize[0]/2 + 5); // Position text inside the background
        userNameText.y = (y*squareSize[1]+ squareSize[0]/2 + 1.5);
 
        userNameBox.clear()
        userNameBox.beginFill(0x000000); // Black color
        userNameBox.drawRoundedRect(
            x*squareSize[0] + squareSize[0]/2,
         y*squareSize[1]+ squareSize[0]/2, 
         userNameText.width + 10, 
         10, 10); // Rounded rectangle
        userNameBox.endFill();
        
        
        TweenMax.to(userNameBox, 0.5, { alpha: 1, ease: Power3.easeInOut });
    }
}
function onDown(e) {
    // Pixi.js adds all its mouse listeners
    // to the window, regardless of which
    // element they are assigned to inside the
    // canvas. So to avoid zooming in when 
    // selecting a color we first check if the
    // click is not withing the bottom 60px where
    // the color options are
    if (e.data.global.y < window.innerHeight - 60 && ready) {
        // We save the mouse down position
        start = { x: e.data.global.x, y: e.data.global.y };
        // And set a flag to say the mouse
        // is now down
        mouseDown = true;
    }
}
function onMove(e) {
    let pos = e.data.global;
    if (mouseDown) {
        if (!dragging) {
            // If not currently dragging, check if mouse move more than 5px
            if (Math.abs(start.x - pos.x) > 5 || Math.abs(start.y - pos.y) > 5) {
                // if so means user is dragging
                graphicsStart = { x: graphics.position.x, y: graphics.position.y };
                dragging = true;
            }
        }
        if (dragging) {
            // if dragging, move the canvas
            moveTo(e.data.global.x - start.x, e.data.global.y - start.y,graphicsStart);
        }
    }
    // code for revealing username when hovering over a pixel
    let position = e.data.getLocalPosition(graphics);
    let [x,y] = getCorresPixel(position.x, position.y);
    if ([x,y] != selectedBox){
        displayUser()
        if (curHoverTimer) clearTimeout(curHoverTimer);
        curHoverTimer = setTimeout(()=>{displayUser([x,y])},500)
    }
    highlightPixel(x,y,"hover")
}

function onUp(e) {
    // clear the .dragging class from DOM
    body.classList.remove('dragging');
    // ignore the mouse up if the mouse down
    // was out of bounds (e.g in the bottom
    // 60px)
    if (mouseDown && ready) {
        // clear mouseDown flag
        mouseDown = false;
        // if the dragging flag was never set
        // during all the mouse moves then this 
        // is a click
        if (!dragging) {
            // if a color has been selected and
            // the view is zoomed in then this
            // click is to draw a new pixel
            let position = e.data.getLocalPosition(graphics);
            // round the x and y down
            let [x,y] = getCorresPixel(position.x, position.y);
            if (!pickingCanvasColor) toggleZoom(e.data.global,true);
            unselectTile([x,y])
            
        }
        dragging = false;
    }
}
function moveTo(x, y,initial) {
    initial = initial ?? { x: graphics.position.x, y: graphics.position.y }
    graphics.position.x = (x / scale) + initial.x;
    graphics.position.y = (y / scale) + initial.y;
    gridLines.position.x = (x / scale) + initial.x;
    gridLines.position.y = (y / scale) + initial.y;
    hoverBox.position.x = (x / scale) + initial.x;
    hoverBox.position.y = (y / scale) + initial.y;
    clickBox.position.x = (x / scale) + initial.x;
    clickBox.position.y = (y / scale) + initial.y;
    userNameBox.position.x = (x / scale) + initial.x;
    userNameBox.position.y = (y / scale) + initial.y;
}

function tweenTo(x, y, duration) {
    TweenMax.to(graphics.position, duration, { x, y, ease: Power3.easeInOut });
    TweenMax.to(gridLines.position, duration, { x, y, ease: Power3.easeInOut });
    TweenMax.to(hoverBox.position, duration, { x, y, ease: Power3.easeInOut });
    TweenMax.to(clickBox.position, duration, { x, y, ease: Power3.easeInOut });
    TweenMax.to(userNameBox.position, duration, { x, y, ease: Power3.easeInOut });
}



function renderPixel(pixel) {
    let x = pixel.x;
    let y = pixel.y;
    let color = pixel.color;
    
    // draw the square on the graphics canvas
    graphics.beginFill(parseInt('0x' + color), 1);
    graphics.drawRect(x * squareSize[0], y * squareSize[1], squareSize[0], squareSize[1]);
}
function toggleZoom(offset, forceZoom) {
    // Store the scale before the zoom
    const prevScale = scale

    // the zoomed keeps track of wheter the user should be zoomed in or not
    zoomed = forceZoom !== undefined ? forceZoom : !zoomed;
    let x = offset.x - (window.innerWidth / 2);
    let y = offset.y - (window.innerHeight / 2);
    scale = zoomed ? zoomLevel : 1;

    // opacity is used to fade in and out the grid lines
    let opacity = zoomed ? 1 : 0;

    // Tween the scale of the container
    TweenMax.to(container.scale, 0.5, { x: scale, y: scale, ease: Power3.easeInOut });
    let newX = zoomed ? graphics.position.x - x/prevScale : graphics.position.x + x/prevScale;
    let newY = zoomed ? graphics.position.y - y/prevScale : graphics.position.y + y/prevScale;
    
    // fade in/out the grid lines
    TweenMax.to(gridLines, 0.5, { alpha: opacity, ease: Power3.easeInOut });

    // We want to zoom in to a point, so this function helps us move
    tweenTo(newX, newY, 0.5);
}

function instantZoom(zoomAmt){
    container.scale.set(zoomAmt)
}
function zoomBy(amount) {
    scale += amount;
    scale = Math.min(Math.max(0.1, scale),4);
    let opacity = scale > 1.5 ? 1 : 0;
    TweenMax.to(container.scale, 0.5, { x: scale, y: scale, ease: Power3.easeInOut });
    TweenMax.to(gridLines, 0.5, { alpha: opacity, ease: Power3.easeInOut });
}


function onScrollZoom(event){
    if (event.deltaY < 0) {
        zoomBy(0.2);
    } else {
        zoomBy(-0.2);
    }
}

zoomInBtn.onclick = () => zoomBy(0.5)
zoomOutBtn.onclick = () => zoomBy(-0.5)

document.addEventListener('dblclick', function(event) {
    event.preventDefault();
  }, { passive: false });
  



