<template>
    <div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card full-height">
                    <div class="card-body">
                        <div class="overlay" v-if="loading">
                            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                        </div>
                        <highlightable v-if="entities.length" @action="handleAction" :entities="entities">
                            <p id="text" class="text scrollbar">{{ text.text }}</p>
                        </highlightable>
                    </div>
                    <button class="btn btn-primary send-btn" @click="send">Gönder</button>
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
                    console.log(response.data)
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
            axios.get('/data/text/new').then(response => {
                if (response.status === 200){
                    this.text = response.data
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
            this.current = {entity: {}, words: []}
        },

        /** Necessary */
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
                            console.log(response.data)
                            if (response.status === 200){
                                this.$buefy.snackbar.open({
                                    message:  response.data.message,
                                    type: 'is-success',
                                    position: 'is-top',
                                    actionText: 'OK',
                                    indefinite: true,
                                    onAction: () => {
                                        this.selected = []
                                        this.selectedUpdateType = ""
                                        this.loading = true;
                                        this.getNewText();
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

        /** Necessary */
        update(){
            if (this.selected.length){
                this.$buefy.dialog.confirm({
                    message: 'Devam etmek ister misiniz?',
                    onConfirm: () => {
                        var regex = new RegExp('<span[|](id="tag-[0-9]{4}"[|])?class="tag"[|]title="(.+?)"[|]style="color:#fff;background:#[A-Za-z0-9]{6}">(.+?)</span>', 'g');
                        var tagged_text = this.text.text.replace(regex, ' <START:$2> $3 <END> ').replace(/(<span(.+?)>)|(<\/span>)/ig, '').replace(/[|]/g, ' ').replace('  ', ' ')
                        var data = {
                            text_id: this.$route.params.text_user_id,
                            tagged_text: tagged_text
                        }
                        axios.put('/data/text', data).then(response => {
                            console.log(response.data)
                            if (response.status === 200){
                                this.$buefy.snackbar.open({
                                    message:  response.data.message,
                                    type: 'is-success',
                                    position: 'is-top',
                                    actionText: 'OK',
                                    indefinite: true,
                                    onAction: () => {
                                        this.selected = []
                                        this.selectedUpdateType = ""
                                        this.getNewText();
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
    padding: 0 50px;
    max-height: calc(100vh - 270px);
    overflow-y: auto;
}
.send-btn {
    float: right;
}
</style>