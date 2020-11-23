exit.onclick = function () {
	var xhr = new XMLHttpRequest();
	xhr.open("GET", "/exit.php", true);
	xhr.onload = function (e) {
  		if (xhr.readyState === 4) {
   		location.reload();
  		}
	};
	xhr.onerror = function (e) {
 		console.error(xhr.statusText);
	};
	xhr.send(null); 
}