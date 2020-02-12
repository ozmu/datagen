const app = new Vue({
    el: '#app',
    components: {
        'home': window.httpVueLoader('../vue/components/Home.vue')
    }
})