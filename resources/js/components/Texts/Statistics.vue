<template>
    <div>
        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="overlay" v-if="loading">
                        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                    </div>
                    <div class="card-header">
                        <h3 class="title">Detay</h3>
                        <button class="btn btn-primary create-btn" @click="$router.push({name: 'texts-tagging', params: { text_user_id: text.id}});">
                            <b-icon icon="file-edit-outline"></b-icon>
                        </button>
                    </div>
                    <div class="card-body highlighted scrollbar" v-html="highlight">
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="row">
                    <div class="card full-height">
                        <div class="card-body">
                            <div class="overlay" v-if="verifiedRate.loading">
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                            </div>
                            <fusioncharts
                            type="pie3d"
                            width="100%"
                            height="300"
                            dataFormat="json"
                            :dataSource="verifiedRate.dataSource"
                            ></fusioncharts>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="card full-height">
                        <div class="card-body">
                            <div class="overlay" v-if="allTagsRate.loading">
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                            </div>
                            <fusioncharts
                            type="pie3d"
                            width="100%"
                            height="300"
                            dataFormat="json"
                            :dataSource="allTagsRate.dataSource"
                            ></fusioncharts>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="overlay" v-if="loading">
                            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                        </div>
                        <b-table
                        :data="filteredTags"
                        :paginated="true"
                        :per-page="10"
                        width="100%"
                        :striped="true"
                        :pagination-simple="false"
                        sort-icon="arrow-up">
                            <template slot-scope="props">
                                <b-table-column field="entity_mention" label="Entity Mention" width="40%" sortable>
                                    <span>{{ props.row.entity_mention }}</span>
                                </b-table-column>
                                
                                <b-table-column field="entity_type.entity" label="Entity Type (Localized)" width="20%" sortable>
                                    <b-tag type="is-success" :style="'color:#fff;background:' + props.row.entity_type.color">{{ props.row.entity_type.entity }} ({{ props.row.entity_type.localized }})</b-tag>
                                </b-table-column>
                                
                                <b-table-column field="is_verified" label="Verified ?" width="20%" sortable>
                                    <b-tag v-if="props.row.is_verified" type="is-success">Verified</b-tag>
                                    <b-tag v-else type="is-danger">Not Verified</b-tag>
                                </b-table-column>
                                
                                <b-table-column field="verified_at" label="Verified At" width="20%" sortable>
                                    <span>{{ props.row.verified_at }}</span>
                                </b-table-column>
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
            text: {},
            tagFilter: '',
            verifiedRate: {
                loading: true,
                dataSource: {
                    chart: {
                        caption: "Verify Rate",
                        subCaption : "Verified tags and Not verified tags rate",
                        showValues:"1",
                        showPercentInTooltip : "0",
                        enableMultiSlicing:"1",
                        theme: "fusion"
                    },
                    data: null
                }
            },
            allTagsRate: {
                loading: true,
                dataSource: {
                    chart: {
                        caption: "All tags",
                        subCaption : "All marked tags",
                        showValues:"1",
                        showPercentInTooltip : "0",
                        enableMultiSlicing:"1",
                        theme: "fusion"
                    },
                    data: null
                }
            }
        }
    },

    computed: {
        highlight(){
            if (this.text.tagged_text){
                var pattern = new RegExp('<START:(.+?)>(.+?)<END>', 'gi')
                var replaced = this.text.tagged_text
                var match;
                do {
                    match = pattern.exec(replaced)
                    if (match){
                        var entity_mention = this.text.tags.filter(tag => tag.entity_mention === match[2] && tag.entity_type.entity.toLowerCase() === match[1].toLowerCase())
                        if (entity_mention.length){
                            replaced = replaced.replace('<START:' + match[1] + '>' + match[2] + '<END>', '<span style="background:'+ 
                            (entity_mention[0].is_verified ? '#23d160' : '#fd1d4a') + ';color:#fff;padding:5px;border-radius:5px;">&lt;START:' + match[1] + '&gt;' + match[2] + '&lt;END&gt;</span>')
                        }
                    }
                } while (match)
                return replaced
            }
            return this.text
        },

        filteredTags(){
            if (this.tagFilter){
                return this.text.tags
            }
            return this.text.tags
        }
    },

    watch: {
        'text': function(newVal, oldVal){
            if (Object.entries(newVal).length){
                // BEGIN:Verified Rate
                this.verifiedRate.dataSource.data = [
                    {label: 'Verified', value: newVal.tags.filter(tag => tag.is_verified).length, color: '#23d160'},
                    {label: 'Not Verified', value: newVal.tags.filter(tag => !tag.is_verified).length, color: '#ff3860'},
                ]
                this.verifiedRate.loading = false;

                // BEGIN:All Tags Rate Rate
                this.allTagsRate.dataSource.data = Object.entries(_.groupBy(this.text.tags, 'entity_type_id')).map(e => {return {label: e[1][0].entity_type.entity, value: e[1].length, color: e[1][0].entity_type.color}})
                this.allTagsRate.loading = false;

            }
        }
    },

    mounted(){
        var text_user_id = _.isEmpty(this.$route.params) ? null : this.$route.params.text_user_id;
        if (text_user_id){
            axios.get('/data/text/' + text_user_id).then(response => {
                this.text = response.data
                this.loading = false;
            })
        }
        else {
            this.$buefy.snackbar.open({
                message: "Yalnızca bir metin seçilerek istatistik görüntülenebilir!",
                type: 'is-warning',
                position: 'is-top',
                actionText: 'OK',
                indefinite: true,
                onAction: () => {
                    this.$router.push({name: 'texts-tagged'})
                }
            })
        }
    }
}
</script>

<style scoped>
.card.full-height {
    width: 100%;
    margin-bottom: 10px;
}
.card-body.highlighted {
    line-height: 30px;
    height: 607px;
    padding-top: 15px !important;
    overflow-y: auto;
}
.create-btn {
    position: absolute;
    right: 10px;
    top: 10px;
}
</style>