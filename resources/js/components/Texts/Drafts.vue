<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="overlay" v-if="loading">
                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                </div>
                <div class="row">
                    <div class="table-full">
                        <button class="btn btn-primary create-btn" @click="$router.push({name: 'texts-tagging'})">
                            <b-icon icon="plus-circle"></b-icon>
                        </button>
                        <input v-model="search" type="text" class="form-control search-text" placeholder="Ara..">
                        <b-table
                        v-if="filteredTaggedTexts.length"
                        :data="filteredTaggedTexts"
                        :paginated="true"
                        :per-page="10"
                        width="100%"
                        :striped="true"
                        :pagination-simple="false"
                        sort-icon="arrow-up">
                            <template slot-scope="props">
                                <b-table-column label="Metin" width="30%" sortable>
                                    <span :title="props.row.text.text">{{ props.row.text.text | truncate(200) }}</span>
                                </b-table-column>
                                
                                <b-table-column label="Etiketlenmiş metin" width="30%" sortable>
                                    <span :title="props.row.tagged_text">{{ props.row.tagged_text | truncate(200) }}</span>
                                </b-table-column>
                                
                                <b-table-column label="Oluşturulma" width="15%" sortable>
                                    <b-tag type="is-info" :title="props.row.created_at">{{ moment(props.row.created_at).fromNow() }}</b-tag>
                                </b-table-column>
                                
                                <b-table-column label="Son güncelleme" width="15%" sortable>
                                    <b-tag type="is-info"  :title="props.row.updated_at">{{ moment(props.row.updated_at).fromNow() }}</b-tag>
                                </b-table-column>
                                
                                <b-table-column label="İşlemler" width="10%">
                                    <b-tag type="is-warning" @click.native="redirect(props.row)" title="Düzenle" class="action">
                                        <b-icon icon="file-edit-outline" custom-size="mdi-18px"></b-icon>
                                    </b-tag>
                                    <b-tag type="is-danger" @click.native="destroy(props.row)" title="Sil" class="action">
                                        <b-icon icon="close-circle" custom-size="mdi-18px"></b-icon>
                                    </b-tag>
                                </b-table-column>
                            </template>
                            <template slot="empty">
                                <section class="section">
                                    <div class="content has-text-grey has-text-centered">
                                        <p>
                                            <b-icon
                                                icon="emoticon-sad"
                                                size="is-large">
                                            </b-icon>
                                        </p>
                                        <p>Kayıt Bulunamadı!</p>
                                    </div>
                                </section>
                            </template>
                        </b-table>
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
            search: '',
            taggedTexts: [],
            nextPageUrl: "/data/text/drafts"
        }
    },

    computed: {
        filteredTaggedTexts(){
            if (this.search){
                return this.taggedTexts.filter(t => t.text.text.toLowerCase().includes(this.search.toLowerCase()) || t.tagged_text.toLowerCase().includes(this.search.toLowerCase()))
            }
            return this.taggedTexts
        }
    },

    mounted(){
        this.more()
    },

    methods: {
        moment(){
            return moment();
        },

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

        redirect(text){
            this.$router.push({name: 'texts-tagging', params: { draft_id: text.id}});
        },
        
        destroy(text){
            this.$buefy.dialog.confirm({
                message: 'Devam etmek ister misiniz?',
                onConfirm: () => {
                    axios.delete('/data/draft/destroy', {
                        data: {
                            id: text.id
                        }
                    }).then(response => {
                        console.log(response)
                        if (response.data === 1){
                            this.taggedTexts.splice(this.taggedTexts.indexOf(text), 1)
                            this.$buefy.toast.open('Taslak silindi!')
                        }
                    }).catch(e => {
                        console.log(e.response)
                    })
                }
            })
        },

        sortTags(a,b){
            return a.length - b.length
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
.create-btn, .search-text {
    float: right;
}
.search-text {
    width: 200px;
    margin-right: 10px;
    padding: 20px;
}
span.tag.is-danger, span.tag.is-success {
    color: #fff;
    margin-right: 5px;
    margin-bottom: 5px;
}
</style>