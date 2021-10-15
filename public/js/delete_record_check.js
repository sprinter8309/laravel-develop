var posts_index_deletes = document.querySelectorAll(".posts-index-action-delete");

console.dir(posts_index_deletes);

for (var i = 0; i < posts_index_deletes.length; i++) {
    
    posts_index_deletes[i].onclick = function(event) {     
        
        answer = confirm("Вы точно хотите удалить статью?");
        if (answer) {
            return;
        }
        event.preventDefault();
    }
}
