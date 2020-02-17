<template>
    <div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title"></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                        <button @click="create.modal = true">Create</button>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Text</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="text in texts" :key="text.id">
                                    <td>{{ text.id }}</td>
                                    <td>{{ text.text }}</td>
                                    <td>
                                        <span @click="edit(text)">Edit</span>
                                        <span @click="destroy(text)">Delete</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- BEGIN::Create Modal -->
        <b-modal :active.sync="create.modal" :width="640" scroll="keep">
            <div class="card">
                <div class="card-content">
                    <div class="content">
                        <div class="form-group">
                            <label>Metin</label>
                            <textarea class="form-control" cols="5" rows="3" v-model="create.data.text"></textarea>
                        </div>
                        <button @click="store">Kaydet</button>
                    </div>
                </div>
            </div>
        </b-modal>
        <!-- END::Create Modal -->

        <!-- BEGIN::Create Modal -->
        <b-modal :active.sync="edit.modal" :width="640" scroll="keep">
            <div class="card">
                <div class="card-content">
                    <div class="content">
                        <div class="form-group">
                            <label>Metin</label>
                            <textarea class="form-control" cols="5" rows="3" v-model="edit.data.text"></textarea>
                        </div>
                        <button @click="update">Kaydet</button>
                    </div>
                </div>
            </div>
        </b-modal>
        <!-- END::Create Modal -->
    </div>
</template>

<script>
export default {
    data(){
        return {
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
        })
    },

    methods: {
        store(){
            axios.post('/data/admin/texts', this.create.data).then(response => {
                if (response.status === 200){
                    console.log('Başarılı')
                    axios.get('/data/admin/texts/' + response.data.id).then(response => {
                        this.texts.push(response.data)
                    })
                }
                else {
                    console.log('Bir hata var.')
                }
                console.log(response.data)
                this.create.modal = false;
                this.$buefy.snackbar.open({
                    message: response.data,
                    type: response.status === 200 ? 'is-success' : 'is-warning',
                    position: 'is-top',
                    actionText: response.status === 200 ? 'OK' : 'Retry',
                    indefinite: true,
                    onAction: () => {
                        // Retry denildiğinde yapılacaklar..
                        this.$buefy.toast.open({
                            message: 'Action pressed!',
                            queue: false
                        })
                    }
                })
            }).catch(e => {
                console.log(e.response)
            })
        },

        edit(text){
            this.edit.modal = true
            axios.get('/data/admin/texts/' + text.id).then(response => {
                this.edit.data = response.data
            })
        },

        update(){
            axios.put('/data/admin/texts/' + this.edit.data.id, this.edit.data).then(response => {
                if (response.status === 200){
                    console.log('Başarılı')
                }
                else {
                    console.log('Bir hata var.')
                }
                console.log(response.data)
                this.edit.modal = false;
                this.$buefy.snackbar.open({
                    message: response.data,
                    type: response.status === 200 ? 'is-success' : 'is-warning',
                    position: 'is-top',
                    actionText: response.status === 200 ? 'OK' : 'Retry',
                    indefinite: true,
                    onAction: () => {
                        // Retry denildiğinde yapılacaklar..
                        this.$buefy.toast.open({
                            message: 'Action pressed!',
                            queue: false
                        })
                    }
                })
            }).catch(e => {
                console.log(e.response)
            })
        },

        destroy(text){
            console.log('Silme işlemi başlatılıyor..')
            axios.delete('/data/admin/texts/' + text.id).then(response => {
                console.log(response.data)
            })
        }
    }
}
</script>

<style>

</style>