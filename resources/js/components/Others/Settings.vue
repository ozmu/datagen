<template>
    <div class="card">
        <div class="card-body">
            <div class="overlay" v-if="user.loading">
                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
            </div>
            <div class="form-group">
                <label>İsim</label>
                <input type="text" v-model="user.data.name" class="form-control">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" v-model="user.data.email" class="form-control">
            </div>
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" v-model="user.data.currentPassword" class="form-control">
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" v-model="user.data.newPassword" class="form-control">
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" v-model="user.data.newRetryPassword" class="form-control">
            </div>
            <button class="btn btn-primary" @click="update">Güncelle</button>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            user: {
                loading: true,
                data: {}
            }
        }
    },

    mounted(){
        axios.get('/data/utils/user').then(response => {
            if (response.status === 200){
                this.user.data = response.data
                this.user.loading = false;
            }
        })
    },

    methods: {
        update(){
            var data = {
                id: this.user.data.id,
                name: this.user.data.name,
                email: this.user.data.email
            }
            if (this.user.data.currentPassword || this.user.data.newPassword || this.user.data.newRetryPassword){
                if (this.user.data.newPassword === this.user.data.newRetryPassword){
                    data["current_password"] = this.user.data.currentPassword
                    data["new_password"] = this.user.data.newPassword
                }
                else {
                    this.$buefy.toast.open('Yeni parolalar eşleşmiyor!')
                    return;
                }
            }
            this.$buefy.dialog.confirm({
                message: 'Devam etmek ister misiniz?',
                onConfirm: () => {
                    axios.put('/data/utils/user', data, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").getAttribute('content') 
                        }
                    }).then(response => {
                        this.$buefy.snackbar.open({
                            message: response.data.message,
                            type: response.data.status === 200 ? 'is-success' : 'is-warning',
                            position: 'is-top',
                            actionText: 'OK'
                        })
                    }).catch(e => {
                        if (e.response.status === 422){
                            this.$buefy.snackbar.open({
                                message: e.response.data.errors.join(', '),
                                type: 'is-danger',
                                position: 'is-top',
                                actionText: 'OK'
                            })
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