<template>
    <div class="jumbotron container bg-white shadow-lg mt-4">
        <h1>Login</h1>
        <hr>
        <form class="form-group" @submit.prevent="login">
            <div class="form-group">
                <label for="email">Email address</label>
                <input class="form-control" type="email" name="email" v-model="email" />
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" v-model="password" />
            </div>

            <button type="submit" class="d-block m-auto btn btn-primary">Login</button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            email: "",
            password: ""
        };
    },
    created() {
        const userInfo = localStorage.getItem("user");
        if (userInfo) {
            const userData = JSON.parse(userInfo);
            this.$store.commit("setUserData", userData);
        }
    },
    methods: {
        login() {
            this.$store
                .dispatch("login", {
                    email: this.email,
                    password: this.password
                })
                .then(() => {
                    this.$router.push({ name: "Home" });
                })
                .catch(err => {
                    console.log(err);
                });
        }
    }
};
</script>
