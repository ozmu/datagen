<template>
    <div class="col-md-12">
        <div class="row">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">İçerik</h5>
                </div>
                <div class="card-body">
                    <div class="entities">
                        <span v-for="entity in entities" :key="entity.id">{{ entity.entity }}</span>
                    </div>
                    <div class="words">
                        <span v-for="(word, id) in words" :key="id">{{ word }}</span>
                    </div>
                    <div class="form-group">
                        <label>Mesaj</label>
                        <textarea v-model="text.text" cols="5" rows="2" class="form-control"></textarea>
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
            text: {
                id: null,
                text: ''
            },
            entities: []
        }
    },

    computed: {
        words(){
            return this.text.text.replace(/(<([^>]+)>)/ig, "").replace("  ", " ").split(" ").filter(text => text.length > 0)
        }
    },

    mounted(){
        // Get Entities
        axios.get('/data/utils/entities').then(response => {
            this.entities = response.data
        })

        // Get Random Text
        axios.get('/data/text/new').then(response => {
            this.text = response.data
        })
    }
}
</script>

<style>

</style>