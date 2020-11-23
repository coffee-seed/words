console.log(words);
words_area.innerHTML="";
function ajax_delete_request(id){
	var xhr = new XMLHttpRequest();
	xhr.open("GET", "/delete.php?id="+id, true);
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
function deleter(id){
	if(confirm("Удалить выбранное слово?")){
		ajax_delete_request(id);
	}
}
words.forEach(word => words_area.innerHTML=words_area.innerHTML+"<img src="+word['image']+
" onclick='deleter("+word['id'] +") '>"
);