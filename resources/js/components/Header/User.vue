<template>
    <ul class="navbar-nav">
        <li class="nav-item dropdown dropdown-user">
            <b-dropdown position="is-bottom-left" aria-role="list">
                <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" slot="trigger" slot-scope="{ active }">
                    <img src="/global_assets/images/placeholders/placeholder.jpg" class="rounded-circle mr-2" height="34" width="34" alt="">
                    <span>{{ user.name }}</span>
                </a>

                <b-dropdown-item aria-role="listitem" class="dropdown-item"><i class="icon-user-plus"></i> My profile</b-dropdown-item>
                <b-dropdown-item aria-role="listitem" class="dropdown-item"><i class="icon-coins"></i> My balance</b-dropdown-item>
                <b-dropdown-item aria-role="listitem" class="dropdown-item"><i class="icon-comment-discussion"></i> Messages <span class="badge badge-pill bg-blue ml-auto">58</span></b-dropdown-item>
                <div class="dropdown-divider"></div>
                <b-dropdown-item aria-role="listitem" class="dropdown-item"><i class="icon-cog5"></i> Account settings</b-dropdown-item>
                <b-dropdown-item aria-role="listitem" class="dropdown-item" @click.native="logout"><i class="icon-switch2"></i> Logout</b-dropdown-item>
            </b-dropdown>
        </li>
    </ul>
</template>

<script>
export default {
    props: ['user'],

    data(){
        return {

        }
    },

    mounted(){
    },


    methods: {
        logout(){
            axios.post('/logout')
            setTimeout(function(){
                window.location.href = "/login"
            }, 50)
        }
    }
}
</script>

<style scoped>

</style>