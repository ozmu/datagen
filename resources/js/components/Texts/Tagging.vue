<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row card-row">
                    <div class="col-md-8">
                        <div class="form-control text" v-html="text.text.replace(/(<span)[|](.+?)[|](.*?)[|](.+?)>(.+?)<\/span>/gi, '$1 $2 $3 $4> $5 </span>').replace(/[|]/gi, ' ')"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="entities">
                            <span v-for="entity in entities" :key="entity.id" class="entity-tag" :class="{'selected entity': entity.id === current.entity.id}" @click="current.entity = entity">{{ entity.entity }}</span>
                        </div>
                        <div class="words">
                            <span v-for="(word, id) in words" :key="id" class="entity-tag entity-word" :class="{'selected word': current.words.includes(word)}" @click="selectWord($event, word)">{{ word.value.replace(/(<([^>]+)>)/ig, "").replace(/[|]/gi, " ").replace("  ", " ") }}</span>
                        </div>
                        <div class="selecteds">
                            <span v-for="(s, id) in selected" :key="id" class="tag" :title="s.entity.entity" :style="'color:#fff;background:' + s.entity.color">
                                {{ s.words.map(word => word.value).join(' ') }} <i class="mdi mdi-close-circle mdi-14px close-icon" @click="removeSelected(s)"></i>
                            </span>
                        </div>
                        <b-icon icon="plus-circle" size="is-medium" @click.native="addEntity" class="add-btn">Ekle</b-icon>
                        <button class="btn btn-primary send-btn" @click="send">Gönder</button>
                    </div>
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
            selectedUpdateType: '',
            entities: []
        }
    },

    computed: {
        words(){
            return this.splitWithIndex(this.text.text, " ")
            //return this.splitWithIndex(this.text.text.replace(/(<([^>]+)>)/ig, "").replace("  ", " "), " ")
        }
    },

    watch: {
        'selected': function(newVal, oldVal){
            if (this.selectedUpdateType === "increase"){
                var last = newVal[newVal.length - 1]
                var entity = last.words.map(word => word.value).join('|')
                var beginIndex = last.words[0].index
                var lastIndex = last.words[last.words.length - 1].index + last.words[last.words.length - 1].value.length
                var beginText = '<span|class="tag"|title="' + last.entity.entity + '"|style="color:#fff;background:' + last.entity.color + '">'
                var endText = '</span>'
                this.text.text = this.text.text.replaceAt(beginIndex, beginText + entity + endText, lastIndex)
            }
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

        // Add splice method to Strings
        String.prototype.splice = function(idx, rem, str) {
            return this.slice(0, idx) + str + this.slice(idx + Math.abs(rem));
        };

        // Add replaceAt method to Strings
        String.prototype.replaceAt = function(index, replacement, lastIndex) {
            return this.substr(0, index) + replacement + this.substr(lastIndex);
        }
    },

    methods: {
        splitWithIndex(str, delim){
            //str = str.replace(/(<([^>]+)>)/ig, "").replace("  ", " ")
            var ret=[]
            var splits=str.split(delim)
            var index=0
            for(var i=0;i<splits.length;i++){
                if (splits[i].length > 0){
                    ret.push({
                        index,
                        value: splits[i]
                    })
                }
                index += splits[i].length+delim.length
            }
            return ret
        },

        selectWord(event, word){
            if (this.current.words.includes(word)){
                this.current.words.splice(this.current.words.indexOf(word), 1)
            }
            else {
                if (event.ctrlKey){
                    if (this.current.words.length){
                        var last = this.current.words[this.current.words.length - 1]
                        var lastIndex = this.words.findIndex(w => w.index === last.index) + 1
                        var wordIndex = this.words.findIndex(w => w.index === word.index)
                        for (lastIndex; lastIndex <= wordIndex; lastIndex++){
                            this.current.words.push(this.words[lastIndex])
                        }
                    }
                    else {
                        this.current.words.push(word) 
                    }
                }
                else {
                    this.current.words = [word]
                } 
            }
        },
        
        addEntity(){
            if (!_.isEmpty(this.current.entity) && this.current.words.length > 0){
                var filtered = this.selected.filter(s => {if (s.words.map(word => word.value).join('|') === this.current.words.map(word => word.value.replace(/(<([^>]+)>)/ig, "").replace("  ", " ")).join('|')) return true})
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
                this.selectedUpdateType = "increase";
                this.selected.push({
                    entity: this.current.entity, 
                    words: _.orderBy(this.current.words, "index")
                })
                this.current = {entity: {}, words: []}
            }
        },

        removeSelected(selected){
            var index = this.selected.indexOf(selected);
            console.log(selected)
            this.selectedUpdateType = "decrease";
            this.selected.splice(index, 1);
            var regex = new RegExp('<span[|]class="tag"[|]title="' + selected.entity.entity + '"[|]style="color:#fff;background:#[A-Za-z0-9]{6}">' + selected.words.map(word => word.value).join('[|]') + '</span>');
            this.text.text = this.text.text.replace(regex, selected.words.map(word => word.value).join(' ')).replace("  ", " ")
            this.current = {entity: {}, words: []}
        },

        send(){
            var regex = new RegExp('<span[|]class="tag"[|]title="(.+?)"[|]style="color:#fff;background:#[A-Za-z0-9]{6}">(.+?)</span>', 'g');
            var tagged_text = this.text.text.replace(regex, ' <START:$1> $2 <END> ').replace(/[|]/g, ' ').replace('  ', ' ')
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
.card {
    height: calc(100vh - 190px);
}
.card-row {
    height: calc(100vh - 230px);
}
.card-row .text {
    height: 100%;
}
/** Entities and words */
.entities, .words, .selecteds {
    padding: 5px 0;
    margin-bottom: 10px;
    overflow-y: hidden;
    overflow-x: auto;
    border: 1px solid #e1e1e1;
    min-height: 30px;
}
.words {
    height: calc(100% - 400px);
    overflow: auto;
}
.words::-webkit-scrollbar {
    width: .35em;
}
.words::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
}
.words::-webkit-scrollbar-thumb {
  background-color: darkgrey;
  outline: 1px solid slategrey;
}
.selected {
    color: #fff;
    padding: 5px;
    border-radius: 5px;
    transition: all ease .4s;
}
.selected.word {
    background: green !important;
}
.selected.entity {
    background: red !important;
}
.selecteds span.tag {
    margin-right: 5px;
    margin-bottom: 5px;
    display: inline-block;
}
.entity-tag {
    cursor: pointer;
    padding: 5px;
    margin-right: 5px;
    background: #e2e2e2;
    display: inline-block;
    border-radius: 5px;
}
.entity-tag.entity-word {
    margin-bottom: 5px;
}
.close-icon {
    margin-left: 5px;
    cursor: pointer;
}
.add-btn {
    cursor: pointer;
    transition: all ease .4s;
}
.add-btn:hover {
    color: #30ab84;
    transition: all ease .4s;
}
.send-btn {
    float: right;
}
</style>