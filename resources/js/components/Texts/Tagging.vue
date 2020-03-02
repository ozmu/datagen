<template>
    <div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card full-height">
                    <div class="card-body">
                        <div class="overlay" v-if="loading">
                            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                        </div>
                        <div class="tool-menu" v-if="!loading">
                            <span class="tool-item" title="Otomatik kaydetme">
                                <b-icon icon="content-save"></b-icon>
                            </span>
                            <span class="tool-item" title="Yeni Metin" @click="getNewText">
                                <b-icon icon="refresh"></b-icon>
                            </span>
                            <span class="tool-item" title="Hata Bildir">
                                <b-icon icon="alert-rhombus"></b-icon>
                            </span>
                        </div>
                        <highlightable v-if="entities.length" @action="handleAction" :entities="entities">
                            <p id="text" class="text scrollbar"></p>
                        </highlightable>
                    </div>
                    <button class="btn btn-primary send-btn" @click="send" v-if="!Object.keys($route.params).includes('text_user_id')">Gönder</button>
                    <button class="btn btn-primary send-btn" @click="update" v-else>Güncelle</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Highlightable from '../Utils/Highlightable'
export default {
    components: {
        Highlightable
    },

    data(){
        return {
            loading: true,
            div: null,
            tagCount: 1000,
            text: {
                id: null,
                text: ''
            },
            entities: []
        }
    },

    mounted(){
        // Get Entities
        axios.get('/data/utils/entities').then(response => {
            this.entities = response.data
        })

        if (!Object.keys(this.$route.params).includes("text_user_id")){
            // Get Random Text
            this.getNewText();
        }
        else {
            axios.get('/data/text/' + this.$route.params.text_user_id).then(response => {
                if (response.status === 200){
                    var self = this;
                    this.text = response.data
                    document.getElementById('text').innerHTML = this.taggedTextReplace(response.data.tagged_text)
                    var icons = Array.from(document.getElementsByClassName('close-icon'))
                    icons.forEach(function(icon){
                        icon.addEventListener("click", function(){
                            self.unwrap(this.parentElement)
                        })
                    })
                    this.loading = false;
                }
            }).catch(e => {
                console.log(e.response ? e.response : e)
            })
        }
    },

    methods: {
        unwrap(wrapper) {
            var docFrag = document.createDocumentFragment();
            while (wrapper.firstChild) {
                var child = wrapper.removeChild(wrapper.firstChild);
                if (child.nodeType === 3){
                    docFrag.appendChild(child);
                }
            }
            wrapper.parentNode.replaceChild(docFrag, wrapper);
        },

        handleAction (data){
            var self = this;
            var entity = this.entities.filter(e => e.entity === data.type).length ? this.entities.filter(e => e.entity === data.type)[0] : {}
            var selection = data.selection
            var selection_text = selection.toString();
            var span = document.createElement('span');
            span.id = self.tagCount;
            self.tagCount++;
            span.classList.add('selected')
            span.title = entity.entity
            span.style.backgroundColor = entity.color
            span.textContent = selection_text;
            var icon = document.createElement('i');
            icon.classList.add('mdi', 'mdi-close-circle', 'mdi-14px', 'close-icon');
            icon.onclick = function(){
                self.unwrap(this.parentElement)
            }
            span.appendChild(icon)
            var range = selection.getRangeAt(0);
            range.deleteContents();
            range.insertNode(span);
        },

        getNewText(){
            this.loading = true;
            axios.get('/data/text/new').then(response => {
                if (response.status === 200){
                    this.text = response.data
                    document.getElementById('text').innerText = response.data.text
                    this.loading = false;
                }
                else if (response.status === 204){
                    this.$buefy.snackbar.open({
                        message: "Tüm yazılar etiketlenmiştir!",
                        type: 'is-warning',
                        position: 'is-top',
                        actionText: 'OK'
                    })
                    this.$router.push({name: 'texts-tagged'})
                }
            })
        },

        taggedTextReplace(taggedText){
            var pattern = new RegExp('<START:(.+?)>(.+?)<END>', 'gi')
            var match;
            do {
                match = pattern.exec(taggedText)
                if (match){
                    var e = this.entities.filter(entity => entity.entity === match[1])
                    var entity = e.length ? e[0] : {}
                    taggedText = taggedText.replace('<START:' + match[1] + '>' + match[2] + '<END>', '<span id="' + this.tagCount + '" class="selected" title="' + 
                    match[1] + '" style="background-color: ' + entity.color + '">' + match[2] + '<i class="mdi mdi-close-circle mdi-14px close-icon"></i></span>')
                    this.tagCount++;
                }
            } while (match)
            return taggedText
        },

        send(){
            var text = document.getElementById('text')
            if (text.getElementsByTagName('span').length){
                this.$buefy.dialog.confirm({
                    message: 'Devam etmek ister misiniz?',
                    onConfirm: () => {
                        var html = text.innerHTML;
                        var pattern = new RegExp('<span id="([0-9]{4})" class="selected" title="(.+?)" style="(.+?)">(.+?)<i class="mdi mdi-close-circle mdi-14px close-icon"></i></span>', 'gi')
                        var taggedText = html.replace(pattern, ' <START:$2>$4<END> ')
                        var data = {
                            text_id: this.text.id,
                            tagged_text: taggedText
                        }
                        axios.post('/data/text', data).then(response => {
                            if (response.status === 200){
                                this.$buefy.snackbar.open({
                                    message:  response.data.message,
                                    type: 'is-success',
                                    position: 'is-top',
                                    actionText: 'OK',
                                    indefinite: true,
                                    onAction: () => {
                                        this.loading = true;
                                        this.getNewText();
                                        document.getElementById('text').innerHTML = ""
                                    }
                                })
                            }
                        }).catch(e => {
                            console.log(e.response)
                            this.$buefy.snackbar.open({
                                message:  e.response.data.message,
                                type: 'is-warning',
                                position: 'is-top',
                                actionText: 'OK'
                            })
                        })
                    }
                })
            }
            else {
                this.$buefy.snackbar.open({
                    message: "En az bir adet Entity işaretlenmelidir!",
                    type: 'is-warning',
                    position: 'is-top',
                    actionText: 'OK'
                })
            }
        },

        update(){
            var text = document.getElementById('text')
            if (text.getElementsByTagName('span').length){
                this.$buefy.dialog.confirm({
                    message: 'Devam etmek ister misiniz?',
                    onConfirm: () => {
                        var html = text.innerHTML;
                        var pattern = new RegExp('<span id="([0-9]{4})" class="selected" title="(.+?)" style="(.+?)">(.+?)<i class="mdi mdi-close-circle mdi-14px close-icon"></i></span>', 'gi')
                        var taggedText = html.replace(pattern, ' <START:$2>$4<END> ')
                        var data = {
                            text_id: this.text.id,
                            tagged_text: taggedText
                        }
                        axios.put('/data/text', data).then(response => {
                            if (response.status === 200){
                                this.$buefy.snackbar.open({
                                    message:  response.data.message,
                                    type: 'is-success',
                                    position: 'is-top',
                                    actionText: 'OK',
                                    indefinite: true,
                                    onAction: () => {
                                        this.loading = true;
                                        this.getNewText();
                                        document.getElementById('text').innerHTML = ""
                                    }
                                })
                            }
                        }).catch(e => {
                            console.log(e.response)
                            this.$buefy.snackbar.open({
                                message:  e.response.data.message,
                                type: 'is-warning',
                                position: 'is-top',
                                actionText: 'OK'
                            })
                        })
                    }
                })
            }
            else {
                this.$buefy.snackbar.open({
                    message: "En az bir adet Entity işaretlenmelidir!",
                    type: 'is-warning',
                    position: 'is-top',
                    actionText: 'OK'
                })
            }
        }
    }
}
</script>

<style scoped>
.card.full-height {
    height: calc(100vh - 190px);
    margin-top: 10px;
}
.card-row {
    height: calc(100vh - 230px);
}
.card-row .text {
    overflow-y: auto;
    line-height: 30px;
}
p.text {
    line-height: 30px;
    text-align: justify;
    padding: 20px 50px;
    max-height: calc(100vh - 270px);
    overflow-y: auto;
}
.send-btn {
    float: right;
}
.tool-menu {
    position: absolute;
    top: 0px;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #e1e1e1;
    padding: 10px;
    border-radius: 10px;
    box-shadow: 0 8px 6px -6px #000;
}
.tool-menu .tool-item {
    cursor: pointer;
    transition: all ease .4s;
}
.tool-menu .tool-item:hover {
    color: #2196F3;
    transition: all ease .4s;
}
</style>