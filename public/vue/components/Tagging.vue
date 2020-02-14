<template>
    <div class="col-md-12">
        <div class="row">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">İçerik</h5>
                </div>
                <div class="card-body">
                    <div class="entities">
                        <span v-for="entity in entities" :key="entity.id" :class="{'selected-entity': entity.id === current.entity.id}" @click="current.entity = entity">{{ entity.entity }}</span>
                    </div>
                    <div class="words">
                        <span 
                        v-for="(word, id) in words" 
                        :key="id" 
                        :class="{'selected-word': current.words.includes(word)}" 
                        @click="selectWord($event, word)">{{ word }}</span>
                    </div>
                    <button @click="addEntity">Add</button>
                    <div class="form-group">
                        <label>Mesaj</label>
                        <textarea v-model="text.text" cols="5" rows="2" class="form-control"></textarea>
                    </div>
                    <button @click="send">Gönder</button>
                </div>
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
            this.text.text.replace(entity, '<span class="tag" title="' + last.entity.entity + '"> ' + entity + ' </span>')
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
                this.selected.push({entity, words})
            }
        },

        send(){
            var data = {
                text_id: this.text.id,
                tagged_text: this.text.text
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
/** Entities */
.selected-entity {
    background: red;
    transition: all ease .4s;
}

/** Words */
.selected-words {
    background: green;
    transition: all ease .4s;
}
</style>