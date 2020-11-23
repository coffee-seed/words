words_area.innerHTML="";
words.forEach(word => words_area.innerHTML=words_area.innerHTML+"<img src="+word['image']+
" onclick='var audio = new Audio(\" "+word['sound'] +" \"); audio.play();'>"
);