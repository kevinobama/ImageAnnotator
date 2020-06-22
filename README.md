# Simple Web Based Image Annotator
![Image Annotator](https://raw.githubusercontent.com/klaxxon/ImageAnnotator/master/screenshot2.png)

Web based image annotator that creates Darknet/YOLO txt files for machine learning systems. Only two files needed!  Currently, the processing file uses PHP with the GD extension.

I was looking for a very simple, web based way to take a collection of images and produce annotations from shared contributors over the web.  This is the very beginning so beware of missing features, bugs and security issues.

Creates YOLO/Darknet style .txt files:<br/>
<pre>
0 0.025 0.90416666666667 0.10625 0.1
0 0.73125 0.86458333333333 0.0171875 0.03125
3 0.353125 0.925 0.10625 0.20625
</pre>
  
<h2>Features</h2>
Images are shuffled before serving to the webpage.<br/>
Scroll wheel zooming is supported.<br/>
Segemented parts of image are displayed and zoomed<br/>


<h2>TODO</h2>
Add security
Review current annotations
Work on full resolution images


<h2>Install</h2>

All you need is PHP with gd image extension.

There are only two files that need web access: index.html and process.php.
Within the web directory containing the two files, create a "data" directory.  This is the operational directory for a training set.  This way, it can simply be linked or copied out.  In the future, you will be able to select a training set to annotate.

Create a classes.txt file in your data directory containing a class name per line, no special characters since this name will be used as IDs in the javascript.<B><i>All annotations are indexes to this file.  Only add new classes to end of file, otherwise you will need to redo all annotations for the new class order.</i></b>

Put all of your images in a data/images directory.

The webserver needs write access to this directory.

<h3>Directory Structure</h3>
<pre>
index.html
process.php
data--\
      classes.txt
      images--\
              image1.jpg
              image2.jpg
                   .
                   .
                   .
</pre>

<h8>To create a sequence of images from a video, you can use the following ffmpeg command:<br/>
<code>ffmpeg -i video.mp4 -vf fps=1 image%d.png</code><br/>
Where the fps=# is the number of images you want per second of video.
</h8>

The annotation files will be placed into the images directory with the same name as the image and a .txt extension.

<h2>Do Over</h2>

To clear out any annotations associated with an image, simply delete the associated .txt file.
