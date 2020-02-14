<template>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title"></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Is Deleted</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users" :key="user.id">
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.balance }}</td>
                                <td>{{ user.is_deleted }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            users: []
        }
    },

    mounted(){
        axios.get('/data/admin/users').then(response => {
            this.users = response.data
        })
    }
}
</script>

<style>

</style>