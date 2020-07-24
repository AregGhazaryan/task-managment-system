<template>
    <div id="app">
        <Navbar />
        <flash-message class="flash"></flash-message>
        <router-view />
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import Navbar from "./components/Navbar.vue";

export default {
    components: {
        Navbar
    },

    computed: {
        ...mapGetters(["isLogged"])
    },
};
</script>
