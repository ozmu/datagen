<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="overlay" v-if="loading">
                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                </div>
                <div class="row">
                    <div class="table-full">
                        <button @click="create.modal = true">Create</button>
                        <b-table
                        :data="texts"
                        :paginated="true"
                        :per-page="10"
                        width="100%"
                        :striped="true"
                        :pagination-simple="false"
                        sort-icon="arrow-up">
                            <template slot-scope="props">
                                <b-table-column field="id" label="ID" width="5%" sortable>
                                    {{ props.row.id }}
                                </b-table-column>
                                
                                <b-table-column field="text" label="Text" width="85%">
                                    {{ props.row.text }}
                                </b-table-column>
                                
                                <b-table-column label="Actions" width="10%">
                                    <b-tag type="is-warning" @click.native="editText(props.row)" title="Edit" class="action">
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

        <!-- BEGIN::Create Modal -->
        <b-modal :active.sync="create.modal" :width="640" scroll="keep">
            <div class="card-content">
                <div class="content">
                    <div class="form-group">
                        <label>Metin</label>
                        <textarea class="form-control" cols="5" rows="3" v-model="create.data.text"></textarea>
                    </div>
                    <button @click="store">Kaydet</button>
                </div>
            </div>
        </b-modal>
        <!-- END::Create Modal -->

        <!-- BEGIN::Edit Modal -->
        <b-modal :active.sync="edit.modal" :width="640" scroll="keep">
            <div class="card-content">
                <div class="content">
                    <div class="form-group">
                        <label>Metin</label>
                        <textarea class="form-control" cols="5" rows="3" v-model="edit.data.text"></textarea>
                    </div>
                    <button @click="update">Kaydet</button>
                </div>
            </div>
        </b-modal>
        <!-- END::Edit Modal -->
    </div>
</template>

<script>
export default {
    data(){
        return {
            loading: true,
            create: {
                modal: false,
                data: {}
            },
            edit: {
                modal: false,
                data: {}
            },
            texts: []
        }
    },

    mounted(){
        axios.get('/data/admin/texts').then(response => {
            this.texts = response.data
            this.loading = false;
        })
    },

    methods: {
        store(){
            axios.post('/data/admin/texts', this.create.data).then(response => {
                if (response.status === 201){
                    axios.get('/data/admin/texts/' + response.data.id).then(response => {
                        this.texts.push(response.data)
                    })
                }
                else {
                    console.log('There is an error!')
                }
                this.$buefy.snackbar.open({
                    message: response.status === 201 ? "Text successfully created!" : JSON.stringify(response.data),
                    type: response.status === 201 ? 'is-success' : 'is-warning',
                    position: 'is-top',
                    actionText: response.status === 201 ? 'OK' : 'Retry',
                })
                if (response.status === 201){
                    this.create.modal = false;
                }
                this.create.modal = false;
            }).catch(e => {
                this.$buefy.snackbar.open({
                    message: e.response.data.message,
                    type: 'is-danger',
                    position: 'is-top',
                    actionText: 'OK'
                })
                console.log(e.response)
            })
        },

        editText(text){
            axios.get('/data/admin/texts/' + text.id).then(response => {
                this.edit.data = response.data
            })
            this.edit.modal = true;
        },

        update(){
            axios.put('/data/admin/texts/' + this.edit.data.id, this.edit.data).then(response => {
                this.$buefy.snackbar.open({
                    message: response.data,
                    type: response.status === 200 ? 'is-success' : 'is-warning',
                    position: 'is-top',
                    actionText: response.status === 200 ? 'OK' : 'Retry'
                })
                if (response.status === 200){
                    axios.get('/data/admin/texts/' + this.edit.data.id).then(response => {
                        if (response.status === 200){
                            var objIndex = this.texts.findIndex(obj => obj.id === response.data.id)
                            this.texts[objIndex].text = response.data.text
                        }
                    })
                    this.edit.modal = false;
                }
            }).catch(e => {
                console.log(e.response)
            })
        },

        destroy(text){
            this.$buefy.dialog.confirm({
                message: 'Continue on this task?',
                onConfirm: () => {
                    axios.delete('/data/admin/texts/' + text.id).then(response => {
                        if (response.data === 1){
                            this.texts.splice(this.texts.indexOf(text), 1)
                            this.$buefy.toast.open('Text deleted!')
                        }
                    })
                }
            })
        }
    }
}
</script>

<style>

</style>