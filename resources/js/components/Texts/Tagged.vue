<template>
    <div>
        <ul>
            <li v-for="(text, id) in taggedTexts" :key="id" @click="redirect(text)">
                {{ text }}
            </li>
        </ul>
        <button v-if="nextPageUrl" @click="more">More</button>
    </div>
</template>

<script>
export default {
    data(){
        return {
            taggedTexts: [],
            nextPageUrl: "/data/text/last"
        }
    },

    mounted(){
        this.more()
    },

    methods: {
        more(){
            if (this.nextPageUrl){
                axios.get(this.nextPageUrl).then(response => {
                    this.taggedTexts = [...this.taggedTexts, ...response.data.data]
                    this.nextPageUrl = response.data.next_page_url
                })
            }
        },

        all(){
            axios.get('/data/text/last?scope=all').then(response => {
                this.taggedTexts = response.data.data
            })
        },

        redirect(text){
            this.$router.push({name: 'texts-tagging', params: { text_user_id: text.id}});
        }
    }
}
</script>

<style scoped>

</style>