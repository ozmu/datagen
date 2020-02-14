<template>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Ayarlar</h5>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                <div class="row">
                    <div class="form-group" v-for="setting in settings" :key="setting.id">
                        <label> {{ setting.key }}</label>
                        <input type="text" v-model="setting.value" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <button @click="save">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            settings: []
        }
    },

    mounted(){
        axios.get('/data/settings').then(response => {
            this.settings = response.data
        })
    },

    methods: {
        save(){
            axios.put('/data/settings', {settings: this.settings}).then(response => {
                console.log(response.data)
            })
        }
    }
}
</script>

<style>

</style>