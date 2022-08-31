class CommentElement extends HTMLElement{
    connectedCallback(){
        this.innerHTML = "Bonjour le monde"
    }
}

customElements.define('post-comments',CommentElement)