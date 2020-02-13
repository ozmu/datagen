Vue.use(httpVueLoader)

const app = new Vue({
    el: '#app',
    components: {
        'home': window.httpVueLoader('../vue/components/Home.vue'),
        'tagging': window.httpVueLoader('../vue/components/Tagging.vue'),
        'tagged': window.httpVueLoader('../vue/components/Tagged.vue'),
    }
})