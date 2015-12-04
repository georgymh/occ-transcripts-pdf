**Easy OCC Transcripts** is a helpful tool for students at Orange Coast College to access their transcripts. Using a simple two-step process, students can download their unofficial transcripts as a PDF file straight onto their device for printing, saving, or sending as an email attachment.

##Why This Exist

- Unofficial transcripts are often required as part of an internship or scholarship application for students. 

- Students are often asked to bring a copy to their meetings with academic counselors as well. 

- Although accessing unofficial transcripts through the school portal can be simple, the ability to print or download the document not as easy. 

- Most of the computers on campus are unable to properly format the documents for printing, and often produce incomplete or blank pages due to irregular framing.

**Easy OCC Transcripts** solves all of these problems and makes printing students transcripts an easy process.

##How It Works

1. A student types and submits their student portal *username* and *password*

1. The credentials are used to replicate a login action into the student portal

1. Logged in, the script traverses up to the transcript page and requests the transcripts in HTML format

1. The HTML is parsed, cleaned up, and slightly modified

1. A PDF is generated from the HTML and offered as a download


##What Is Used

- cURL

- PHP's DomDocument

- [dompdf](https://github.com/dompdf/dompdf)

- [Bootstrap](http://getbootstrap.com/)

- [jQuery](https://jquery.com/)


##Who Made This

- Georgy Marrero - https://github.com/georgymh

- Linda Lam - https://github.com/linda-lam
