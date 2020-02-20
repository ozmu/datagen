<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="overlay" v-if="loading">
                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                </div>
                <div class="row">
                    <div class="table-full">
                        <button @click="$router.push({name: 'texts-tagging'})">Create</button>
                        <b-table
                        :data="taggedTexts"
                        :paginated="true"
                        :per-page="10"
                        width="100%"
                        :striped="true"
                        :pagination-simple="false"
                        sort-icon="arrow-up">
                            <template slot-scope="props">
                                <b-table-column label="Original Text" width="30%" sortable>
                                    <span :title="props.row.text.text">{{ props.row.text.text | truncate(200) }}</span>
                                </b-table-column>
                                
                                <b-table-column label="Tags" width="20%" sortable>
                                    <b-tag :type="tag.is_verified ? 'is-success' : 'is-danger'" v-for="(tag,id) in props.row.tags" :key="id" :title="tag.type">{{ tag.entity }}</b-tag>
                                </b-table-column>
                                
                                <b-table-column label="Tagged Text" width="40%" sortable>
                                    <span :title="props.row.tagged_text">{{ props.row.tagged_text | truncate(200) }}</span>
                                </b-table-column>
                                
                                <b-table-column label="Actions" width="10%">
                                    <b-tag type="is-warning" @click.native="redirect(props.row)" title="Edit" class="action">
                                        <b-icon icon="file-edit-outline" custom-size="mdi-18px"></b-icon>
                                    </b-tag>
                                    <b-tag type="is-danger" @click.native="destroy(props.row)" title="Delete" class="action">
                                        <b-icon icon="close-circle" custom-size="mdi-18px"></b-icon>
                                    </b-tag>
                                </b-table-column>
                            </template>
                        </b-table>
                    </div>
                </div>
            </div>
        </div>
        <button v-if="nextPageUrl" @click="more">More</button>
    </div>
</template>

<script>
export default {
    data(){
        return {
            loading: true,
            taggedTexts: [],
            nextPageUrl: "/data/text/last"
        }
    },

    mounted(){
        this.more()
    },

    methods: {
        more(){
            if (this.nextPageUrl){
                this.loading = true;
                axios.get(this.nextPageUrl).then(response => {
                    this.taggedTexts = [...this.taggedTexts, ...response.data.data]
                    this.nextPageUrl = response.data.next_page_url
                    this.loading = false;
                })
            }
        },

        all(){
            this.loading = true;
            axios.get('/data/text/last?scope=all').then(response => {
                this.taggedTexts = response.data.data
                this.loading = false;
            })
        },

        redirect(text){
            this.$router.push({name: 'texts-tagging', params: { text_user_id: text.id}});
        },
        
        destroy(text){
            this.$buefy.dialog.confirm({
                message: 'Continue on this task?',
                onConfirm: () => {
                    axios.delete('/data/text/destroy', {
                        data: {
                            id: text.id
                        }
                    }).then(response => {
                        console.log(response)
                        if (response.data === 1){
                            this.taggedTexts.splice(this.taggedTexts.indexOf(text), 1)
                            this.$buefy.toast.open('Text deleted!')
                        }
                    }).catch(e => {
                        console.log(e.response)
                    })
                }
            })
        }
    },

    filters: {
        truncate: function(value, limit){
            if (value.length > limit) {
                value = value.substring(0, (limit - 3)) + '...';
            }
            return value
        }
    }
}
</script>

<style scoped>
.card-body {
  min-height: calc(100vh - 195px);
}
span.tag.is-danger, span.tag.is-success {
    color: #fff;
    margin-right: 5px;
    margin-bottom: 5px;
}
</style>