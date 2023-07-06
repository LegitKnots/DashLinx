function updateTime() {
  var date = new Date();
  var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
  var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
  var dayOfWeek = days[date.getDay()];
  var month = months[date.getMonth()];
  var dayOfMonth = date.getDate();
  var year = date.getFullYear();
  var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'PM' : 'AM';
  hours = hours % 12;
  hours = hours ? hours : 12;
  minutes = minutes < 10 ? '0' + minutes : minutes;
  var timeString = dayOfWeek + ' ' + month + ' ' + dayOfMonth + ', ' + year + ' ' + hours + ':' + minutes + ' ' + ampm;
  document.getElementById("time").innerHTML = timeString;
}
    setInterval(updateTime, 5);


function handleScroll() {
  var scrollPosition = window.scrollY;
  
  var titleElement = document.querySelector('.title');
  var titleContentElements = document.querySelectorAll('h2.titlecontent, h3.titlecontent');
  
  if (scrollPosition > 150) {
    titleElement.classList.add('title-scrolled');
    titleContentElements.forEach(function(element) {
      element.classList.add('titlecontent-scrolled');
    });
  } else {
    titleElement.classList.remove('title-scrolled');
    titleContentElements.forEach(function(element) {
      element.classList.remove('titlecontent-scrolled');
    });
  }
}

window.addEventListener('scroll', handleScroll);