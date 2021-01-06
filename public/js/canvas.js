//Class canvas
//
class Canvas {

    constructor() {
        this.initVariable();
        this.initEvents();
    }

    initVariable() {
        this.canvas = document.getElementById('canvas');
        this.ctx = this.canvas.getContext("2d");
        this.isMouseClicked = false;
        this.isMouseInCanvas = false;
        this.prevX = 0;
        this.currX = 0;
        this.prevY = 0;
        this.currY = 0;
    }

    initEvents() {
        this.canvas.addEventListener("mousemove", (e) => this.onMouseMove(e));
        this.canvas.addEventListener("mousedown", (e) => this.onMouseDown(e));
        this.canvas.addEventListener("mouseup", () => this.onMouseUp());
        this.canvas.addEventListener("mouseout", () => this.onMouseOut());
        this.canvas.addEventListener("mouseenter", (e) => this.onMouseEnter(e));
        //Mobile events 
        this.canvas.addEventListener("touchstart", () => this.onMouseDown());
        this.canvas.addEventListener("touchmove", (e) => this.onMouseMove(e));
        this.canvas.addEventListener("touchend", (e) => this.onMouseUp());
        // document.getElementById('btn_erase').addEventListener("click", () => this.erase());
    }

    onMouseDown(e) {
        this.isMouseClicked = true;
        this.updateCurrentPosition(e);
    }

    onMouseUp() {
        this.isMouseClicked = false;
    }

    onMouseEnter(e) {
        this.isMouseInCanvas = true;
        this.updateCurrentPosition(e);
    }

    onMouseOut() {
        this.isMouseInCanvas = false;
    }

    onMouseMove(e) {
        if (this.isMouseClicked && this.isMouseInCanvas) {
            this.updateCurrentPosition(e);
            this.draw();
        }
    }

    updateCurrentPosition(e) {
        this.prevX = this.currX;
        this.prevY = this.currY;
        this.currX = e.clientX - this.canvas.offsetLeft;
        this.currY = e.clientY - this.canvas.offsetTop;
    }

    draw() {
        this.ctx.beginPath();
        this.ctx.moveTo(this.prevX, this.prevY);
        this.ctx.lineTo(this.currX, this.currY);
        this.ctx.strokeStyle = "black";
        this.ctx.lineWidth = 2;
        this.ctx.stroke();
        this.ctx.closePath();
    }

    erase() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }

    isCanvasEmpty() {
        let emptyCanvas = document.createElement('canvas');
        emptyCanvas.width = this.canvas.width;
        emptyCanvas.height = this.canvas.height;

        return this.canvas.toDataURL() === emptyCanvas.toDataURL();
    }
}