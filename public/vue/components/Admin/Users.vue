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
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Is Deleted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users" :key="user.id">
                                    <td>{{ user.id }}</td>
                                    <td>{{ user.name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>{{ user.balance }}</td>
                                    <td>{{ user.is_deleted }}</td>
                                    <td>
                                        <span @click="edit(user)">Edit</span>
                                        <span @click="destroy(user)">Delete</span>
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

        <!-- BEGIN::Create Modal -->
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

        edit(user){
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