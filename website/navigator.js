<!-- Expanding Menu Script Starts Here -->
//<--!
// netscape version

    function toggle(element) {
      if (null == element){
        return;
      }
      var targetId = element.id + "_detail";
      targetElement = document.getElementById(targetId);
      if (null != targetElement){
        if (targetElement.style.display == "none") {
          targetElement.style.display = "";
          element.src="minus.gif";
        }
        else{
          targetElement.style.display = "none";
          element.src="plus.gif";
        }
      }
    }

    function clickHandlerNS(e) {
      toggle(e.target);
    }

    // explorer version
    function clickHandlerIE() {
      toggle(window.event.srcElement);
    }

    function collapse_all() {
      var l = document.images;
      var i = l.length;
      while(i > 0){
        i = i - 1;
        var image = l[i];
        image.style.display = "";
        toggle(image);
      }
    }

    function initialize() {
      if(navigator.appName.indexOf("Netscape") != -1){
        document.onclick = clickHandlerNS;
        collapse_all();
      }
      else
      if(navigator.appName.indexOf("Microsoft") != -1){
        document.onclick = clickHandlerIE;
        collapse_all();
      }
      else
      if (navigator.appnName.indexOf("Konqueror") >= 0){
        document.onclick = clickHandlerIE;
        collapse_all();
      }
    }

    function navigate(target) {
        window.top.navigate(target)
    }
//-->

