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
                        <b-table
                        :data="users"
                        :selected.sync="selected"
                        :paginated="true"
                        :per-page="10"
                        :pagination-simple="false"
                        sort-icon="arrow-up"
                        focusable>
                            <template slot-scope="props">
                                <b-table-column field="id" label="ID" sortable>
                                    {{ props.row.id }}
                                </b-table-column>
                                
                                <b-table-column field="name" label="Name" sortable>
                                    {{ props.row.name }}
                                </b-table-column>
                                
                                <b-table-column field="email" label="Email">
                                    {{ props.row.email }}
                                </b-table-column>
                                
                                <b-table-column field="balance" label="Balance" sortable>
                                    {{ props.row.balance }}
                                </b-table-column>
                                
                                <b-table-column field="is_deleted" label="Deleted" sortable>
                                    {{ props.row.is_deleted }}
                                </b-table-column>
                                
                                <b-table-column label="Actions" sortable>
                                    <span @click="editUser(user)">Edit</span>
                                    <span @click="destroy(user)">Delete</span>
                                </b-table-column>
                            </template>
                        </b-table>
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
                            <label>İsim</label>
                            <input type="text" class="form-control" v-model="create.data.name">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" v-model="create.data.email">
                        </div>
                        <div class="form-group">
                            <label>Parola</label>
                            <input type="password" class="form-control" v-model="create.data.password">
                        </div>
                        <button @click="store">Kaydet</button>
                    </div>
                </div>
            </div>
        </b-modal>
        <!-- END::Create Modal -->

        <!-- BEGIN::Edit Modal -->
        <b-modal :active.sync="edit.modal" :width="640" scroll="keep">
            <div class="card">
                <div class="card-content">
                    <div class="content">
                        <div class="form-group">
                            <label>İsim</label>
                            <input type="text" class="form-control" v-model="edit.data.name">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" v-model="edit.data.email">
                        </div>
                        <div class="form-group">
                            <label>Parola</label>
                            <input type="password" class="form-control" v-model="edit.data.password">
                        </div>
                        <button @click="update">Kaydet</button>
                    </div>
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
            selected: {},
            create: {
                modal: false,
                data: {}
            },
            edit: {
                modal: false,
                data: {}
            },
            users: []
        }
    },

    mounted(){
        axios.get('/data/admin/users').then(response => {
            this.users = response.data
        })
    },

    methods: {
        store(){
            axios.post('/data/admin/users', this.create.data).then(response => {
                if (response.status === 200){
                    console.log('Başarılı')
                    axios.get('/data/admin/users/' + response.data.id).then(response => {
                        this.users.push(response.data)
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

        editUser(user){
            this.edit.modal = true
            axios.get('/data/admin/users/' + user.id).then(response => {
                this.edit.data = response.data
            })
        },

        update(){
            axios.put('/data/admin/users/' + this.edit.data.id, this.edit.data).then(response => {
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

        destroy(user){
            console.log('Silme işlemi başlatılıyor..')
            axios.delete('/data/admin/users/' + user.id).then(response => {
                console.log(response.data)
            })
        }
    }
}
</script>

<style>

</style>