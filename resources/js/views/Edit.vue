<template>
    <div class="container bg-white shadow-lg mt-4 p-4">
        <h1>Edit a Task</h1>
        <hr />
        <form v-on:submit.prevent="submit">
            <div class="row">
                <div class="col">
                    <label for="name">Task Name</label>
                    <input
                        type="text"
                        class="form-control"
                        id="name"
                        v-model="name"
                        placeholder="Enter Task Name"
                        required
                        maxlength="255"
                    />
                </div>
                <div class="col">
                    <label for="name">Status</label>
                    <select v-model="st" class="form-control">
                        <option
                            v-for="status in this.statuses"
                            v-bind:key="status.id"
                            :value="status.id"
                            v-bind:selected="status.id == task.status_id"
                            :style="`background-color:${status.color}; color:white;`"
                        >
                            {{ status.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col">
                    <label for="assignees">Assignees</label>
                    <select
                        id="assignees"
                        v-model="assignees"
                        multiple
                        required
                    >
                        <option
                            :value="user.id"
                            v-for="user in this.users"
                            v-bind:key="user.id"
                        >
                            {{ user.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <VueEditor v-model="description" />
            </div>
            <button class="btn btn-success mx-auto d-block mt-4" type="submit">
                Submit
            </button>
        </form>
    </div>
</template>
<script>
import SlimSelect from "slim-select";
import { VueEditor } from "vue2-editor";

export default {
    components: { VueEditor },
    data() {
        return {
            task: false,
            users: null,
            statuses: null,
            status: null,
            select: false,
            st: "",
            assignees: [],
            errors: [],
            name: "",
            description: "",
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content")
        };
    },
    created() {
        axios.get(`/api/task/${this.$route.params.id}`).then(response => {
            this.task = response.data.task;
            this.name = this.task.name;
            this.description = this.task.description;
        });
        axios.get("/api/task/create").then(response => {
            this.users = response.data.users;
            this.statuses = response.data.statuses;
           
        });
         
    },
    mounted() {
        new SlimSelect({
                select: "#assignees"
            });
    },
    methods: {
        submit() {
            const userInfo = localStorage.getItem("user");
            let self = this;
            const userData = JSON.parse(userInfo);
            axios
                .put(
                    `/api/task/${this.task.id}`,
                    {
                        user: userData,
                        name: this.name,
                        description: this.description,
                        assignees: this.assignees,
                        status: this.st,
                        _token: this.csrf
                    },
                    {
                        headers: {
                            Authorization: `Bearer ${userData.token}`
                        }
                    }
                )
                .then(res => {
                    this.flash("Task successfully updated!", "success", {
                        timeout: 3000,
                        beforeDestroy() {
                            self.$router.push("/");
                        }
                    });
                })
                .catch(err => {
                    this.flash(err, "error");
                });
        }
    }
};
</script>
