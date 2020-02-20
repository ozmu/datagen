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
                        :data="users"
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
                                
                                <b-table-column field="name" label="Name" width="25%" sortable>
                                    {{ props.row.name }}
                                </b-table-column>
                                
                                <b-table-column field="email" label="Email" width="25%" sortable>
                                    {{ props.row.email }}
                                </b-table-column>
                                
                                <b-table-column field="balance" label="Balance" width="10%" sortable>
                                    <b-tag type="is-info">
                                        {{ props.row.balance }}
                                        <b-icon icon="currency-try" custom-size="mdi-14px"></b-icon>
                                    </b-tag>
                                </b-table-column>
                                
                                <b-table-column field="is_deleted" label="Deleted" width="10%" sortable>
                                    <b-tag v-if="props.row.is_deleted" type="is-danger">Silindi</b-tag>
                                    <b-tag v-else type="is-success">Kayıtlı</b-tag>
                                </b-table-column>
                                
                                <b-table-column label="Actions" width="25%">
                                    <b-tag type="is-warning" @click.native="editUser(props.row)" title="Edit" class="action">
                                        <b-icon icon="file-edit-outline" custom-size="mdi-18px"></b-icon>
                                    </b-tag>
                                    <b-tag type="is-danger" @click.native="destroyUser(props.row)" title="Delete" class="action">
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
                    <button class="btn btn-primary" @click="store">Kaydet</button>
                </div>
            </div>
        </b-modal>
        <!-- END::Create Modal -->

        <!-- BEGIN::Edit Modal -->
        <b-modal :active.sync="edit.modal" :width="640" scroll="keep">
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
                    <button class="btn btn-primary" @click="update">Kaydet</button>
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
            users: []
        }
    },

    mounted(){
        axios.get('/data/admin/users').then(response => {
            this.users = response.data
            this.loading = false
        })
    },

    methods: {
        store(){
            axios.post('/data/admin/users', this.create.data).then(response => {
                if (response.status === 201){
                    axios.get('/data/admin/users/' + response.data.id).then(response => {
                        this.users.push(response.data)
                    })
                }
                else {
                    console.log('There is an error!')
                }
                this.$buefy.snackbar.open({
                    message: response.status === 201 ? "User successfully created!" : JSON.stringify(response.data),
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

        editUser(user){
            this.edit.modal = true
            axios.get('/data/admin/users/' + user.id).then(response => {
                this.edit.data = response.data
            })
        },

        update(){
            axios.put('/data/admin/users/' + this.edit.data.id, this.edit.data).then(response => {
                this.edit.modal = false;
                this.$buefy.snackbar.open({
                    message: response.data,
                    type: response.status === 200 ? 'is-success' : 'is-warning',
                    position: 'is-top',
                    actionText: response.status === 200 ? 'OK' : 'Retry'
                })
                if (response.status === 200){
                    axios.get('/data/admin/users/' + this.edit.data.id).then(response => {
                        if (response.status === 200){
                            var objIndex = this.users.findIndex(obj => obj.id === response.data.id)
                            this.users[objIndex].name = response.data.name
                            this.users[objIndex].email = response.data.email
                            this.users[objIndex].balance = response.data.balance
                        }
                    })
                    this.edit.modal = false;
                }
            }).catch(e => {
                console.log(e.response)
            })
        },

        destroyUser(user){
            this.$buefy.dialog.confirm({
                message: 'Continue on this task?',
                onConfirm: () => {
                    axios.delete('/data/admin/users/' + user.id).then(response => {
                        if (response.data === 1){
                            this.users.splice(this.users.indexOf(user), 1)
                            this.$buefy.toast.open('User deleted!')
                        }
                    })
                }
            })
        }
    }
}
</script>

<style scoped>
</style>