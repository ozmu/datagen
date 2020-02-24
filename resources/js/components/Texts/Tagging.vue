<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="overlay" v-if="loading">
                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                </div>
                <div class="row card-row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="entities scrollbar">
                                <div style="display: inherit">
                                    <span 
                                    v-for="entity in entities" 
                                    :key="entity.id" 
                                    class="entity-tag" 
                                    :class="{'selected entity': entity.id === current.entity.id}" 
                                    @click="current.entity = entity"
                                    :style="entity.id === current.entity.id ? 'background:' + entity.color : ''" :title="entity.entity">{{ entity.localized }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-control text scrollbar" v-if="text.text" v-html="text.text.replace(/(<span)[|](.+?)[|](.*?)[|](.+?)>(.+?)<\/span>/gi, '$1 $2 $3 $4> $5 </span>').replace(/[|]/gi, ' ')"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="words scrollbar">
                            <span v-for="(word, id) in words" :key="id" class="entity-tag entity-word" :class="{'selected word': current.words.includes(word)}" @click="selectWord($event, word)">{{ word.value.replace(/(<([^>]+)>)/ig, "").replace(/[|]/gi, " ").replace("  ", " ") }}</span>
                        </div>
                        <div class="selecteds scrollbar">
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
            loading: true,
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
        var self = this;
        document.addEventListener('keypress', function(e){
            if (e.key === 'Enter'){
                self.addEntity()
            }
        })
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
                    /*
                    this.text = response.data.text
                    var pattern = new RegExp("<START:(.+?)> (.+?) <END>", "gi")
                    var matches = response.data.tagged_text.match(pattern)
                    for (var match in matches){
                        var typeMatch = matches[match].match(new RegExp(":(.+?)>", "i"))[1] // Entity Type
                        var entityMatch = matches[match].match(new RegExp("> (.+?) <", "i"))[1] // Entity Mention
                        console.log("typeMatch: ", typeMatch)
                        console.log("entityMatch: ", entityMatch)
                        var type = this.entities.filter(type => type.entity === typeMatch)
                        if (type.length){
                            this.current.entity = type[0]
                            var splitted = entityMatch.split(' ')
                            if (splitted.length > 1){
                                for (var s in splitted){
                                    var filtered = this.words.filter(word => word.value === splitted[s])
                                    console.log("filtered: ", filtered)
                                    if (filtered.length === 1){
                                        this.current.words.push(filtered[0])
                                        this.addEntity()
                                    }
                                }
                            }
                            else if (splitted.length === 1){
                                var filtered = this.words.filter(word => word.value === splitted[0])
                                if (filtered.length === 1){
                                    this.current.words.push(filtered[0])
                                    this.addEntity()
                                }
                            }
                        }
                    }
                    */
                    // console.log(response.data.tagged_text.replace(pattern, '<span|class="tag"|title="$1"|style="color:#fff;background:#asdads">$2</span>'))
                    var tagPattern = new RegExp('<START:(.+?)> (.+?) <END>', 'gi')
                    var spanPattern = new RegExp('<span[|]class="tag"[|]title="(.+?)"[|]style="color:#fff;background:#[A-Za-z0-9]{6}">(.+?)<\/span>', 'gi')
                    var replaced = response.data.tagged_text.replace(tagPattern, '<span|class="tag"|title="$1"|style="color:#fff;background:#000000">$2</span>')
                    var match = spanPattern.exec(replaced)
                    while (match != null){
                        var matchPattern = new RegExp('(<span[|]class="tag"[|]title="' + match[1] + '"[|]style="color:#fff;background:)(#000000)(">)' + match[2] + '(<\/span>)', 'i')
                        replaced = replaced.replace(matchPattern, '$1' + this.entities.filter(e => e.entity === match[1])[0].color + '$3' + match[2].replace(/ /gi, '|') + '$4')
                        match = spanPattern.exec(replaced)
                    }
                    this.text = response.data.text
                    this.text.text = replaced
                    this.loading = false;
                    /*
                    var match = spanPattern.exec(this.text.text)
                    while (match != null){
                        var matchPattern = new RegExp('(<span[|]class="tag"[|]title="' + match[1] + '"[|]style="color:#fff;background:)(#[A-Za-z0-9]{6})(">)' + match[2] + '(<\/span>)', 'i')
                        this.selected.push({
                            entity: this.entities.filter(e => e.entity === match[1])[0],
                            words: this.words.filter(w => w.value.includes(match[2].replace(/ /gi, '|')))
                        })
                        match = spanPattern.exec(this.text.text)
                    }
                    */
                }
            }).catch(e => {
                console.log(e.response)
            })
        }

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
        /*
        getSelection(event){
            var selection = window.getSelection()
            if (selection.toString().length){
                console.log(selection)
                var range = selection.getRangeAt(0);
                console.log('Range start: ', range.startOffset)
                console.log('Range end: ', range.endOffset)
                var beginText = `<span|style="background:red">` + selection.toString()
                var endText = `</span>`
                //this.text.text = this.text.text.splice(range.startOffset, selection.toString().length, beginText + endText)
            }
            else {

            }
        },
        */

        getNewText(){
            axios.get('/data/text/new').then(response => {
                if (response.status === 200){
                    this.text = response.data
                    this.loading = false;
                }
                else if (response.status === 204){
                    this.$buefy.snackbar.open({
                        message: "All texts done!",
                        type: 'is-warning',
                        position: 'is-top',
                        actionText: 'OK'
                    })
                    this.$router.push({name: 'texts-tagged'})
                }
            })
            this.current = {entity: {}, words: []}
        },

        splitWithIndex(str, delim){
            //str = str.replace(/(<([^>]+)>)/ig, "").replace("  ", " ")
            var ret=[]
            var splits= str ? str.split(delim) : [""]
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
                        var lastIndex = this.words.findIndex(w => w.index === last.index)
                        var wordIndex = this.words.findIndex(w => w.index === word.index)
                        if (lastIndex < wordIndex){
                            lastIndex++;
                            for (lastIndex; lastIndex <= wordIndex; lastIndex++){
                                this.current.words.push(this.words[lastIndex])
                            }
                        }
                        else {
                            lastIndex--;
                            for (lastIndex; lastIndex >= wordIndex; lastIndex--){
                                this.current.words.push(this.words[lastIndex])
                            }
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
                //var filtered = this.selected.filter(s => {if (s.words.map(word => word.value).join('|') === this.current.words.map(word => word.value.replace(/(<([^>]+)>)/ig, "").replace("  ", " ")).join('|')) return true})
                var filtered = this.selected.filter(s => s.words[0].index === this.current.words[0].index)
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
            else {
                this.$buefy.snackbar.open({
                    message: "Please select entity and type!",
                    type: 'is-warning',
                    position: 'is-top',
                    actionText: 'OK'
                })
            }
        },

        /*
        printSelected(selected){
            var pattern = new RegExp('<span[|]class="tag"[|]title="(.+?)[|]style="color:#fff;background:#[A-Za-z0-9]{6}">(.+?)</span>', 'i')
            var values = selected.words.map(word => word.value)
            if (pattern.test(values)){
                console.log('Selected: ', selected)
                return selected.words[0].value.match(pattern)[2].replace(/[|]/g, ' ')
            }
            else {
                return values.join(' ')
            }
        },
        */

        removeSelected(selected){
            var index = this.selected.indexOf(selected);
            this.selectedUpdateType = "decrease";
            this.selected.splice(index, 1);
            var regex = new RegExp('<span[|]class="tag"[|]title="' + selected.entity.entity + '"[|]style="color:#fff;background:#[A-Za-z0-9]{6}">' + selected.words.map(word => word.value).join('[|]') + '</span>');
            this.text.text = this.text.text.replace(regex, selected.words.map(word => word.value).join(' ')).replace("  ", " ")
            this.current = {entity: {}, words: []}
        },

        send(){
            if (this.selected.length){
                this.$buefy.dialog.confirm({
                    message: 'Continue on this task?',
                    onConfirm: () => {
                        var regex = new RegExp('<span[|]class="tag"[|]title="(.+?)"[|]style="color:#fff;background:#[A-Za-z0-9]{6}">(.+?)</span>', 'g');
                        var tagged_text = this.text.text.replace(regex, ' <START:$1> $2 <END> ').replace(/[|]/g, ' ').replace('  ', ' ')
                        var data = {
                            text_id: this.text.id,
                            tagged_text: tagged_text
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
                    message: "Selected entities must at least 1!",
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
.card {
    height: calc(100vh - 190px);
}
.card-row {
    height: calc(100vh - 230px);
}
.card-row .text {
    overflow-y: auto;
    height: calc(100vh - 290px);
}
/** Entities and words */
.entities, .words, .selecteds {
    padding: 10px;
    margin-bottom: 10px;
    overflow-y: hidden;
    overflow-x: auto;
    border: 1px solid #e1e1e1;
    min-height: 30px;
}
.entities {
    overflow-x: auto;
    display: inherit;
}
.words, .selecteds {
    overflow-y: auto;
    height: 45%;
}
.scrollbar::-webkit-scrollbar {
    width: .35em;
    height: .35em;
}
.scrollbar::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
}
.scrollbar::-webkit-scrollbar-thumb {
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
    box-shadow: 0 0 10px #000;
}
.selecteds span.tag {
    margin-right: 5px;
    margin-bottom: 5px;
    line-height: 20px;
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