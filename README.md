**Easy OCC Transcripts** (live [here](https://mighty-eyrie-49519.herokuapp.com)) is a helpful tool for students at Orange Coast College to access their transcripts. Using a simple two-step process, students can download their unofficial transcripts as a PDF file straight onto their device for printing, saving, or sending as an email attachment.

##Why This Exist

**The ability to print or download the unofficial transcripts is not an easy task and often requires staff assistance.**

The majority of the time, students at Orange Coast College struggle to print or download their transcripts, resulting on delayed academic counselors meetings, missed internships applications, and failure to address important scholarships requirements.*

As a solution, **Easy OCC Transcripts** was created as an *open source* and *free* software for our students to print their transcripts without any hassle.

*The reason why this happens is because modern web browsers are unable to properly format and render the document for printing due to irregular framing.

##How It Works

1. A student types and submits their *username* and *password*

1. The credentials are used to replicate a login action through the student portal

1. Once the user is logged in, the script navigates to the transcripts page and requests it in HTML format

1. The HTML is parsed, cleaned up, and slightly modified to make it look better

1. A PDF is generated (in memory) from the HTML and offered as a download to the student


##What Is Used

- cURL

- PHP's DomDocument

- [dompdf](https://github.com/dompdf/dompdf)

- [Bootstrap](http://getbootstrap.com/)

- [jQuery](https://jquery.com/)


##Who Made This

- Georgy Marrero - https://github.com/georgymh

- Linda Lam - https://github.com/linda-lam
