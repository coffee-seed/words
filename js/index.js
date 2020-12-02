words_area.innerHTML="";
words.forEach(word => words_area.innerHTML=words_area.innerHTML+"<img src="+word['image']+
" onclick='this.style.border=\"2vh #0FBB0F solid\"; that=this; setTimeout(function(){that.style.border=\"none\";},350); var audio = new Audio(\" "+word['sound'] +" \"); audio.play();'>"
);