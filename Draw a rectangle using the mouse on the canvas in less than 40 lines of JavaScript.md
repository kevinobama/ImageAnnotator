One thing that is a lot easier than people think is using the mouse to interact with the canvas tag. To show how easy, I wrote some code that allows a user to draw a rectangle on the canvas. This will be the start of a more complicated drawing program.

All we need for the HTML is this:

1
2
3
4
5
6
7
8
9
10
11
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Draw a rectangle!</title>
</head>

<body>
<canvas id="canvas" width="500" height="500"></canvas>
</body>
</html>
Now let’s get on to the fun stuff. The first thing we’ll do is declare our variables:

1
2
3
4
var canvas = document.getElementById('canvas'),
    ctx = canvas.getContext('2d'),
    rect = {},
    drag = false;
If you’ve worked with the canvas tag at all before then the first two variables should look familiar, we are finding the canvas tag in the DOM using it’s id and then setting the drawing context of the canvas to 2D. Next is sq which is an object that we’ll use to store the info for the rectangle we draw. And finally, drag, which is a Boolean that we use to check if the user is drawing the rectangleor not.

Now let’s add our listeners which we’ll use to run our functions:

1
2
3
4
5
function init() {
  canvas.addEventListener('mousedown', mouseDown, false);
  canvas.addEventListener('mouseup', mouseUp, false);
  canvas.addEventListener('mousemove', mouseMove, false);
}
We have three event listeners, one for when the mouse button is pressed, one for when it’s released and finally one for when the mouse moves. All three have been added to the canvas, not the window or document, so that a user can click and move the mouse around the page with out triggering our drawing functions. The mouseDown function is called first, so let’s write that one:

1
2
3
4
5
function mouseDown(e) {
  rect.startX = e.pageX - this.offsetLeft;
  rect.startY = e.pageY - this.offsetTop;
  drag = true;
}
When the mouse button is pressed down, the function mouseDown will find the position of our mouse using e.pageX and e.pageY and then subtract the offset from the left and top if our canvas isn’t in the left top corner. Finally, we set the drag Boolean to true. Next is the mouseUp function:

1
2
3
function mouseUp() {
  drag = false;
}
About as simple a function as we can get. When the user releases the mouse button, drag is set back to false because they can’t be dragging to create a rectangle if the button isn’t pressed. And now the function that will power everything, mouseMove:

1
2
3
4
5
6
7
8
function mouseMove(e) {
  if (drag) {
    rect.w = (e.pageX - this.offsetLeft) - rect.startX;
    rect.h = (e.pageY - this.offsetTop) - rect.startY ;
    ctx.clearRect(0,0,canvas.width,canvas.height);
    draw();
  }
}
In mouseMove, we first check to see if drag is true, if it’s false, the just means the user is moving their mouse around the canvas, if it’s true, then it means they want to draw a rectangle. If drag is true, then we need to follow the position of the mouse and calculating the width and height of the rectangle. To do this we need to subtract startX and startY from the current position of the mouse. Next we clear the canvas because if the drag is true and mouse is moving, that means the user is still drawing the canvas. Finally, we call a function called draw to actually draw our rectangle:

1
2
3
function draw() {
  ctx.fillRect(rect.startX, rect.startY, rect.w, rect.h);
}
With the draw function, we draw the rectangle using the four points that we figured out using the mouseDown and mouseMove functions. The starting x and y coordinates are rect.startX and rect.startY and the width and height are set with rect.w and rect.h. This will be updating every time the mouse moves, so if the user shrinks or grows the rectangle, it will draw immediately. And of course, don’t forget to call:

1
init();
At the bottom, so that the event listeners to the canvas. Check out the demo here. View source for the complete code.