<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">İçerik</h5>
            </div>
            <div class="card-body">
                <div class="entities">
                    <span v-for="entity in entities" :key="entity.id" class="entity-tag" :class="{'selected entity': entity.id === current.entity.id}" @click="current.entity = entity">{{ entity.entity }}</span>
                </div>
                <div class="words">
                    <span 
                    v-for="(word, id) in words" 
                    :key="id" 
                    class="entity-tag"
                    :class="{'selected word': current.words.includes(word)}" 
                    @click="selectWord($event, word)">{{ word }}</span>
                </div>
                <button @click="addEntity">Add</button>
                <div class="form-group">
                    <label>Mesaj</label>
                    <div class="form-control" v-html="text.text"></div>
                </div>
                <button @click="send">Gönder</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            text: {
                id: null,
                text: ''
            },
            current: {
                entity: {},
                words: []
            },
            selected: [],
            entities: []
        }
    },

    computed: {
        words(){
            return this.text.text.replace(/(<([^>]+)>)/ig, "").replace("  ", " ").split(" ").filter(text => text.length > 0)
        }
    },

    watch: {
        'selected': function(newVal, oldVal){
            var last = newVal[newVal.length - 1]
            var entity = last.words.join(' ')
            this.text.text = this.text.text.replace(entity, '<span class="tag" title="' + last.entity.entity + '"> ' + entity + ' </span>')
        }
    },

    mounted(){
        // Get Entities
        axios.get('/data/utils/entities').then(response => {
            this.entities = response.data
        })

        // Get Random Text
        axios.get('/data/text/new').then(response => {
            this.text = response.data
        })
    },

    methods: {
        selectWord(event, word){
            if (this.current.words.includes(word)){
                this.current.words.splice(this.current.words.indexOf(word), 1)
            }
            else {
                if (event.ctrlKey){
                    this.current.words.push(word) 
                }
                else {
                    this.current.words = [word]
                } 
            }
        },
        
        addEntity(){
            if (this.current.entity && this.current.words.length > 0){
                var filtered = this.selected.filter(s => {if (s.entity === this.current.entity && s.words.join(':') === this.current.words.join(':')) return true})
                if (filtered.length){
                    this.$buefy.snackbar.open({
                        message: "Entity already added!",
                        type: 'is-warning',
                        position: 'is-top',
                        actionText: 'OK'
                    })
                    this.current = {entity: {}, words: []}
                    return
                }
                this.selected.push({
                    entity: this.current.entity, 
                    words: this.current.words
                })
                this.current = {entity: {}, words: []}
            }
        },

        send(){
            var tagged_text = this.text.text.replace(/<span class="tag" title="(.+?)"> (.+?) <[/]span>/, ' <START:$1> $2 <END> ').replace('  ', ' ')
            var data = {
                text_id: this.text.id,
                tagged_text: tagged_text
            }
            axios.post('/data/text', data).then(response => {
                if (response.data.status === 200){
                    console.log("Success!")
                }
                else {
                    console.log("Error!")
                }
            })
        }
    }
}
</script>

<style scoped>
/** Entities and words */
.selected {
    color: #fff;
    padding: 5px;
    border-radius: 5px;
    transition: all ease .4s;
}
.selected.word {
    background: green;
}
.selected.entity {
    background: red;
}
.entity-tag {
    cursor: pointer;
    padding: 5px;
    margin-right: 5px;
}
</style>