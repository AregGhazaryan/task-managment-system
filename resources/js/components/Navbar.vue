<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <router-link class="navbar-brand" to="/">
            <img src="../assets/logo.png" style="max-height:30px">
        </router-link>
        <button
            class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarColor02"
            aria-controls="navbarColor02"
            aria-expanded="false"
            aria-label="Toggle navigation"
            wfd-invisible="true"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor02">
            <ul class="navbar-nav mr-auto d-flex justify-content-between">
                <li
                    class="nav-item"
                    v-for="link in this.links"
                    v-bind:key="link.id"
                >
                    <router-link class="nav-link" :to="link.url">
                        {{link.label}}
                    </router-link>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto d-flex justify-content-between">
                <li class="nav-item">
                    <router-link
                        class="nav-link"
                        to="/login"
                        v-if="!this.$store.getters.isLogged"
                        >Login</router-link
                    >
                </li>
                <li v-if="this.user" class="nav-item active">
                    <a class="nav-link">
                    {{this.user.name}}
                    </a>
                </li>
            </ul>

            <button
                class="btn btn-outline-light my-2 my-sm-0"
                type="button"
                @click="logout()"
                v-if="this.$store.getters.isLogged"
            >
                Logout
            </button>
        </div>
    </nav>
</template>
<script>
export default {
    data() {
        return {
            user: undefined,
            links: [{ id: 1, url: "/", label: "Home" }, {id: 2, url: '/dashboard', label: "Dashboard"}]
        };
    },
    methods: {
        logout() {
            this.$store.dispatch("logout");
        }
    },
    created(){
        let data = localStorage.getItem("user");
        let json = JSON.parse(data);
        this.user = json.user;
    },
    mounted(){
        console.log(this.user.name);
    }
};
</script>
<style>
.cls-1 {
    font-size: 179.36px;
    fill: #fff;
    font-family: Righteous-Regular, Righteous;
    letter-spacing: -0.01em;
}

.cls-2 {
    letter-spacing: 0em;
}
</style>
