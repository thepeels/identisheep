<!-- ROT13 Code and Decode Email addresses with different link text also ------->
<script language="JavaScript" type="text/javascript">
  function decode(a) {
      return a.replace(/[a-zA-Z]/g, function(c){
          return String.fromCharCode((c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
      })
  };
  function openMailer(element) {  //basic mailto; version
      var y = decode("znvygb:nqzva@vqragvfurrc.pbz");
      element.setAttribute("href", y);
      element.setAttribute("onclick", "");
      //element.firstChild.nodeValue = "Clicked!";
  };
  function subjectMailer(element) {  // mailto: with subject set
      var x = element.getAttribute('href');
      var y = decode("znvygb:whyvrgpbeyrl@tznvy.pbz?pp=whyvrg@whyvrgpbeyrl.pbz") + '&Subject=' + x;
      //put on email address, use, and then paste in the result!! easy
      element.setAttribute("href", y);
      element.setAttribute("onclick", "");
      //element.firstChild.nodeValue = "Clicked!";
  };
  function shopMailer(element) {  //subject and body text attached
      //var x = element.getAttribute('href');
      var y = decode("znvygb:whyvrgpbeyrl@tznvy.pbz?pp=whyvrg@whyvrgpbeyrl.pbz") +
          '&subject=Shop%20enquiry&body=I%20wish%20to%20purchase%20the%20following%20items%20from%20your%20shop%3A%0A%0A%0AMy%20address%20is%3A' ;
      //put on email address, use, and then paste in the result!! easy
      element.setAttribute("href", y);
      element.setAttribute("onclick", "");
      //element.firstChild.nodeValue = "Clicked!";
  };
//             abcdefghijklmnopqrstuvwxyz
//             nopqrstuvwxyzabcdefghijklm   ROT13
//znvygb:fnyrf_whyvrgpbeyrl@ovtsbbg.pbz
</script>