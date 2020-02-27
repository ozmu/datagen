<template>
    <div class="card">
        <div class="card-body">
            <div class="overlay" v-if="loading">
                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label> Coin Factor </label>
                    <input class="form-control" type="text" v-model="settings.coin_factor">
                </div>
                <div class="form-group">
                    <label> Tag Verify Rate </label>
                    <input class="form-control" type="text" v-model="settings.tag_verify_rate">
                </div>
                <div class="form-group">
                    <label> Text Verify Rate </label>
                    <input class="form-control" type="text" v-model="settings.text_verify_rate">
                </div>
                <div class="form-group">
                    <label> Maximum User For Text </label>
                    <input class="form-control" type="text" v-model="settings.maximum_user_for_text">
                </div>
                <div class="form-group">
                    <label> Balance Calculation Type </label>
                    <select v-model="settings.balance_calculation_type" class="form-control">
                        <option value="verified_texts">Verified Texts</option>
                        <option value="verified_tags">Verified Tags</option>
                    </select>
                </div>
                <button class="btn btn-primary save-btn" @click="save">Save</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            loading: true,
            settings: {},
        }
    },

    mounted(){
        axios.get('/data/admin/settings').then(response => {
            this.settings = response.data.reduce((obj, item) => {
                obj[item.key] = item.value
                return obj
            }, {})
            this.loading = false;
        })
    },

    methods: {
        save(){
            axios.put('/data/admin/settings', {settings: this.settings}).then(response => {
                if (response.status === 200){
                    this.$buefy.snackbar.open({
                        message: response.data,
                        type: 'is-success',
                        position: 'is-top',
                        actionText: 'OK',
                        indefinite: true,
                        onAction: () => {
                            this.$router.go()
                        }
                    })
                }
            })
        }
    }
}
</script>

<style scoped>
.save-btn {
    float: right;
}
</style>