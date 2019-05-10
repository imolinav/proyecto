let canvas = document.createElement("canvas");
canvas.setAttribute("id", "grid_bg_effect");
document.getElementById("noise-grid-bg").appendChild(canvas);

let Square = function(x, y, size, resizeFactor, minOpacity, maxOpacity, transitionSpeed, angle = Math.random() * Math.PI * 2) {
    this.x = x;
    this.y = y;
    this.size = size;
    this.resizeFactor = resizeFactor;
    this.minOpacity = minOpacity;
    this.maxOpacity = maxOpacity;
    this.angle = angle; /* Used to swing between min and max*/
    this.transitionSpeed = transitionSpeed;
};
Square.prototype = {
    constructor: Square,
    update: function() {
        this.angle += this.transitionSpeed;
        this.resize = Math.cos(this.angle) * this.resizeFactor; /* Set resize factor */
        let opacity = Math.cos(this.angle) * (this.maxOpacity - this.minOpacity) + (this.minOpacity + this.maxOpacity) / 2;
        this.opacity = Math.max(Math.min(opacity, 1), 0);  /* Set opacity of square */
        this.color = Math.round(255*this.opacity);
        this.fill = "rgb(" + this.color + "," + this.color + "," + this.color + ")";
    },
    render: function(ctx) {
        ctx.fillStyle = this.fill;
        ctx.fillRect(this.x - this.resize * this.size, this.y - this.resize * this.size, this.size + this.resize * this.size, this.size + this.resize * this.size);
    }
};

let squares = [];
let animationLoop = null;
function init(squares) {
    let canvas = document.getElementById("grid_bg_effect");
    let ctx = canvas.getContext("2d");
    let W = (canvas.width = window.innerWidth);
    let H = (canvas.height = window.innerHeight);

    /* Settings */
    let maxSquaresPerHeight = 40;
    let maxSquaresPerWidth = 80;
    let minSize = 15;
    let resizeFactor = 0; /* Try changing to 0.15 :) */
    let transitionSpeed = 0.05; /* Transition step speed (the smaller the slowest) */

    let size = Math.max(minSize, Math.ceil(H / maxSquaresPerHeight), Math.ceil(W / maxSquaresPerWidth)); /* Calculate square size in pixels */
    let columns = Math.ceil(W / size); /* Calculate columns count */
    let rows = Math.ceil(H / size); /* Calculate rows count */
    let y = 0;
    let count = 0;
    for (let row = 0; row < rows; row++) {
        let x = 0;
        for (let col = 0; col < columns; col++) {
            let opacity1 = Math.random();
            let opacity2 = Math.random();
            if (count < squares.length) {
                /* Square already present, update size but keep angle*/
                squares[count] = new Square(x, y, size, resizeFactor, Math.min(opacity1, opacity2), Math.max(opacity1, opacity2), transitionSpeed, squares[count].angle);
            } else {
                /* Need new square */
                let square = new Square(x, y, size, resizeFactor, Math.min(opacity1, opacity2), Math.max(opacity1, opacity2), transitionSpeed);
                squares.push(square);
                count++;
            }
            x += size;
        }
        y += size;
    }

    for (let i = count; count < squares.length; i++) {
        /* Remove extra squares */
        squares.pop();
    }
    let offscreenCanvas = document.createElement('canvas');
    let offscreenCanvasCtx = offscreenCanvas.getContext("2d", { alpha: false});
    cancelAnimationFrame(animationLoop);
    animationLoop = null;
    requestAnimationFrame(function loop() {
        animationLoop = requestAnimationFrame(loop);
        /* Pre-render */
        offscreenCanvas.width = W;
        offscreenCanvas.height = H;

        for (let i = 0; i < squares.length; i++) {
            let square = squares[i];
            square.update();
            square.render(offscreenCanvasCtx);
        }
        ctx.clearRect(0,0,W,H);
        ctx.drawImage(offscreenCanvas, 0, 0);
    });
}

init(squares);

window.onresize = function() {
    init(squares);
};