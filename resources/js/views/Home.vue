<template>
    <div>
        <Welcome v-if="!this.$store.getters.isLogged"/>
        <Tasks v-if="this.$store.getters.isLogged"/>
    </div>
</template>
<script>
import Welcome from '../components/Welcome.vue';
import Tasks from '../components/Tasks.vue';

export default {
    components:{
        Welcome,
        Tasks
    }    
}
</script>
